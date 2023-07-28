<?php

class BAF_faqtfw_Admin
{

    protected static $instance = null;
    protected $plugin_screen_hook_suffix = null;
    public $plugin_slug;

    private function __construct()
    {

        if (!class_exists('BWL_Advanced_Faq_Manager') || !class_exists('WooCommerce') || FAQTFW_PARENT_PLUGIN_INSTALLED_VERSION < '1.5.9') {
            add_action('admin_notices', [$this, 'baf_faqtfw_version_update_admin_notice']);
            return false;
        }


        $plugin = BAF_faqtfw::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        $post_types = 'product';
        $this->includedFiles();
        // Load public-facing style sheet and JavaScript.
        add_action('admin_enqueue_scripts', [$this, 'baf_faqtfw_admin_enqueue_scripts']);
        // After manage text we need to add "custom_post_type" value.
        add_filter('manage_' . $post_types . '_posts_columns', [$this, 'faqtfw_custom_column_header']);

        // After manage text we need to add "custom_post_type" value.
        add_action('manage_' . $post_types . '_posts_custom_column', [$this, 'faqtfw_display_custom_column'], 10, 1);


        // Quick & Bulk Edit Section.

        add_action('bulk_edit_custom_box', [$this, 'faqtfw_product_quick_edit_box'], 10, 2);
        add_action('quick_edit_custom_box', [$this, 'faqtfw_product_quick_edit_box'], 10, 2);

        add_action('save_post', [$this, 'faqtfw_product_save_quick_edit_data'], 10, 2);
        add_action('wp_ajax_manage_wp_posts_using_bulk_edit_faqtfw', [$this, 'manage_wp_posts_using_bulk_edit_faqtfw']);
    }

    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function includedFiles()
    {
        require_once(FAQTFW_DIR . 'includes/autoupdater/WpAutoUpdater.php');
        require_once(FAQTFW_DIR . 'includes/autoupdater/installer.php');
        require_once(FAQTFW_DIR . 'includes/autoupdater/updater.php');
    }

    //Version Manager:  Update Checking

    public function baf_faqtfw_version_update_admin_notice()
    {

        echo '<div class="updated"><p>You need to download & install both '
            . '<b><a href="http://downloads.wordpress.org/plugin/woocommerce.zip" target="_blank">WooCommerce Plugin</a></b> & '
            . '<b><a href="https://1.envato.market/baf-wp" target="_blank">BWL Advanced FAQ Manager Plugin</a></b> '
            . 'to use <b>FAQ Tab For WooCommerce - Advanced FAQ Addon</b>. Minimum version <b>1.5.9</b> required ! </p></div>';
    }

    public function baf_faqtfw_admin_enqueue_scripts($hook)
    {

        // We only load this JS script in product add/edit page.

        $current_post_type = "";

        if (isset($_GET['post_type']) && $_GET['post_type'] == "product") {

            $current_post_type = "product";
        } else if (isset($_GET['post']) && get_post_type($_GET['post']) === 'product') {

            $current_post_type = "product";
        } else {

            $current_post_type = "";
        }

        if ($current_post_type == "product") {

            wp_enqueue_script('baf-cmb-admin-main', BAF_WC_PLUGIN_DIR . 'includes/baf-cmb-framework/admin/js/baf_cmb.js', ['jquery', 'jquery-ui-core', 'jquery-ui-sortable'], false, false);
            wp_enqueue_style('baf-cmb-admin-style', BAF_WC_PLUGIN_DIR . 'includes/baf-cmb-framework/admin/css/baf_cmb.css', [], false, 'all');
            wp_enqueue_script($this->plugin_slug . '-admin', BAF_WC_PLUGIN_DIR . 'assets/scripts/admin.js', ['jquery'], BAF_faqtfw::VERSION, TRUE);

            wp_localize_script(
                $this->plugin_slug . '-admin',
                'BafFtfwcAdminData',
                [
                    'product_id' => FAQTFW_PLUGIN_CC_ID,
                    'installation' => get_option(FAQTFW_PLUGIN_INSTALLATION_TAG)
                ]
            );
        } else {

            return;
        }
    }

    function faqtfw_cmb_framework(array $meta_boxes)
    {

        $args = [
            'post_status' => 'publish',
            'post_type' => 'bwl_advanced_faq',
            'orderby' => 'title',
            'order' => 'ASC',
            'posts_per_page' => -1
        ];

        $loop = new WP_Query($args);

        $faqftw_faq_post_ids = [];

        if ($loop->have_posts()) :

            while ($loop->have_posts()) :

                $loop->the_post();

                $baf_title = ucfirst(get_the_title());

                $baf_post_id = get_the_ID();

                $faqftw_faq_post_ids[$baf_post_id] = $baf_title;

            endwhile;

        endif;

        wp_reset_query();


        $fields = [
            ['id' => 'baf_woo_tab_hide_status', 'name' => esc_html__('Hide FAQ Tab?', 'baf-faqtfw'), 'type' => 'checkbox'],
            ['id' => 'faqftw_faq_post_ids', 'name' => esc_html__('Click Add New Button to Add FAQ Items', 'baf-faqtfw'), 'type' => 'select', 'options' => $faqftw_faq_post_ids, 'allow_none' => true, 'sortable' => true, 'repeatable' => true],
        ];

        $meta_boxes[] = [
            'title' => esc_html__('WooCommerce FAQ Item Settings', 'baf-faqtfw'),
            'pages' => 'product',
            'fields' => $fields,
            'priority' => 'high'
        ];

        return $meta_boxes;
    }

    function faqtfw_custom_column_header($columns)
    {

        return array_merge(
            $columns,
            [
                'faqftw_faq_post_ids' => esc_html__('Total FAQ', 'baf-faqtfw'),
                'baf_woo_tab_hide_status' => esc_html__('FAQ Tab Status', 'baf-faqtfw')
            ]
        );
    }

    function faqtfw_display_custom_column($column)
    {

        // Add A Custom Image Size For Admin Panel.

        global $post;

        switch ($column) {

            case 'faqftw_faq_post_ids':


                $get_faqftw_faq_post_ids = apply_filters('filter_baftfwc_content_data', get_post_meta($post->ID, 'faqftw_faq_post_ids'));
                $faqftw_faq_post_ids = (int) count($get_faqftw_faq_post_ids);
                echo '<div id="faqftw_faq_post_ids-' . $post->ID . '" >&nbsp;' . $faqftw_faq_post_ids . '</div>';

                break;

            case 'baf_woo_tab_hide_status':

                $baf_woo_tab_hide_status = (get_post_meta($post->ID, "baf_woo_tab_hide_status", true) == "") ? "" : get_post_meta($post->ID, "baf_woo_tab_hide_status", true);

                // FAQ Display Status In Text.

                $baf_woo_tab_hide_status_in_text = ($baf_woo_tab_hide_status == 1) ? esc_html__("Hidden", "baf-faqtfw") : esc_html__("Visible", "baf-faqtfw");

                echo '<div id="baf_woo_tab_hide_status-' . $post->ID . '" data-status_code="' . $baf_woo_tab_hide_status . '" >' . $baf_woo_tab_hide_status_in_text . '</div>';

                break;
        }
    }

    /*---Bulk & Quick Edit Section---*/

    function faqtfw_product_quick_edit_box($column_name, $post_type)
    {

        global $post;

        switch ($post_type) {

            case $post_type:

                switch ($column_name) {

                    case 'baf_woo_tab_hide_status':

                        $baf_woo_tab_hide_status = (get_post_meta($post->ID, "baf_woo_tab_hide_status", true) == "") ? "" : get_post_meta($post->ID, "baf_woo_tab_hide_status", true);
?>


                        <fieldset class="inline-edit-col-left">
                            <div class="inline-edit-col">
                                <div class="inline-edit-group">
                                    <label class="alignleft">

                                        <span class="checkbox-title"><?php esc_html_e('Hide FAQ Tab?', 'baf-faqtfw'); ?></span>
                                        <select name="baf_woo_tab_hide_status">
                                            <option value="3"><?php esc_html_e('- No Change -', 'baf-faqtfw'); ?></option>
                                            <option value="1"><?php esc_html_e('Yes', 'baf-faqtfw'); ?></option>
                                            <option value="2"><?php esc_html_e('No', 'baf-faqtfw'); ?></option>
                                        </select>
                                    </label>

                                </div>
                            </div>
                        </fieldset>


                    <?php
                        break;
                }

                break;
        }
    }

    function faqtfw_product_save_quick_edit_data($post_id, $post)
    {

        // pointless if $_POST is empty (this happens on bulk edit)
        if (empty($_POST))
            return $post_id;

        // verify quick edit nonce
        if (isset($_POST['_inline_edit']) && !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
            return $post_id;

        // don't save for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        // dont save for revisions
        if (isset($post->post_type) && $post->post_type == 'revision')
            return $post_id;

        switch ($post->post_type) {

            case $post->post_type:

                /**
                 * Because this action is run in several places, checking for the array key
                 * keeps WordPress from editing data that wasn't in the form, i.e. if you had
                 * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
                 */
                $custom_fields = ['baf_woo_tab_hide_status'];

                foreach ($custom_fields as $field) {

                    if (array_key_exists($field, $_POST)) {

                        update_post_meta($post_id, $field, $_POST[$field]);
                    }
                }

                break;
        }
    }

    function faqtfw_product_bulk_edit_box($column_name, $post_type)
    {

        global $post;

        switch ($post_type) {

            case $post_type:

                switch ($column_name) {

                    case 'baf_woo_tab_hide_status':
                    ?>
                        <fieldset class="inline-edit-col-right">
                            <div class="inline-edit-col">
                                <div class="inline-edit-group">
                                    <label class="alignleft">
                                        <span class="checkbox-title"><?php esc_html_e('Hide FAQ Tab?', 'baf-faqtfw'); ?></span>
                                        <select name="baf_woo_tab_hide_status">
                                            <option value="3"><?php esc_html_e('- No Change -', 'baf-faqtfw'); ?></option>
                                            <option value="1"><?php esc_html_e('Yes', 'baf-faqtfw'); ?></option>
                                            <option value="2"><?php esc_html_e('No', 'baf-faqtfw'); ?></option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

<?php
                        break;
                }

                break;
        }
    }

    function manage_wp_posts_using_bulk_edit_faqtfw()
    {

        // we need the post IDs
        $post_ids = (isset($_POST['post_ids']) && !empty($_POST['post_ids'])) ? $_POST['post_ids'] : NULL;

        // if we have post IDs
        if (!empty($post_ids) && is_array($post_ids)) {

            // Get the custom fields

            $custom_fields = ['baf_woo_tab_hide_status'];

            foreach ($custom_fields as $field) {

                // if it has a value, doesn't update if empty on bulk
                if (isset($_POST[$field]) && trim($_POST[$field]) != "") {

                    // update for each post ID
                    foreach ($post_ids as $post_id) {

                        if ($_POST[$field] == 2) {

                            update_post_meta($post_id, $field, "");
                        } elseif ($_POST[$field] == 1) {

                            update_post_meta($post_id, $field, 1);
                        } else {
                            // do nothing
                        }
                    }
                }
            }
        }
    }
}
