<?php

class BAF_faqtfw
{

    const VERSION = FAQTFW_ADDON_CURRENT_VERSION;

    protected $plugin_slug = "baf-faqtfw";

    protected static $instance = null;

    private function __construct()
    {

        if (class_exists('BwlFaqManager\\Init')
            && class_exists('WooCommerce')
            && FAQTFW_PARENT_PURCHASE_STATUS == 1
        ) {

            // Load public-facing style sheet and JavaScript.
            add_action('init', [$this, 'load_plugin_textdomain']);
            add_action('wp_head', [$this, 'baf_faqtfw_custom_scripts']);
            add_action('wp_enqueue_scripts', [$this, 'faqtfwEnqueueScripts']);

            add_filter('woocommerce_product_tabs', [$this, 'faqtfwAddCustomProductTab']);

            $this->include_files();
        }
    }

    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since 1.0.0
     */
    private static function single_activate()
    {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    function include_files()
    {

        include_once FAQTFW_DIR . 'includes/baf-wc-helpers.php';
        include_once FAQTFW_DIR . 'includes/public/class-faqtfw-addon.php';
        include_once FAQTFW_DIR . 'includes/public/shortcode/baf-faqtfw-shortcodes.php';
    }

    public function baf_faqtfw_custom_scripts()
    {

        $faqftw_options = get_option('faqftw_options');

        $faqftw_faq_counter = 1;

        if (isset($faqftw_options['faqftw_faq_counter']) && $faqftw_options['faqftw_faq_counter'] == 0) {

            $faqftw_faq_counter = 0;
        }
        ?>
<script type="text/javascript">
var faqftw_faq_counter = '<?php echo $faqftw_faq_counter; ?>';
</script>

        <?php
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
    }

    /**
     * Enqueues front-end styles and scripts
     *
     * @since 1.0.0
     */
    public function faqtfwEnqueueScripts()
    {
        wp_enqueue_script($this->plugin_slug . '-frontend', BAF_WC_PLUGIN_DIR . 'assets/scripts/frontend.js', ['jquery'], self::VERSION);
    }

    /**
     * Register custom tab
     *
     * @since: 1.0.0
     */
    public function faqtfwAddCustomProductTab($tabs)
    {

        global $product;

        // Get Data From Option Panel Settings.
        $faqftw_options = get_option('faqftw_options');

        // Initialize Values.

        $baf_woo_tab_hide_status = 1; // Enable Auto hide
        $faqftw_tab_title = esc_html__("FAQ ", "baf-faqtfw"); // Set the title of FAQ Tab.

        if (isset($faqftw_options['faqftw_tab_title']) && $faqftw_options['faqftw_tab_title'] != "") {
            $faqftw_tab_title = esc_html($faqftw_options['faqftw_tab_title']); // Introduced in version 1.0.1
        }

        /*---Start Tab Auto Hide Section---*/


        if (isset($faqftw_options['faqftw_auto_hide_tab']) && $faqftw_options['faqftw_auto_hide_tab'] == 0) {
            $baf_woo_tab_hide_status = 0;
        }

        if ($baf_woo_tab_hide_status == 1) {

            // Count no of KB for current product.
            $faqftw_faq_post_ids = (int) count(get_post_meta($product->get_id(), 'faqftw_faq_post_ids'));

            if ($faqftw_faq_post_ids == 0) {
                return $tabs;
            }
        }

        /*---End Tab Auto Hide Section---*/

        // Set custom position for FAQ tab. 
        // Tips: Higher values like 100, 200, 300 will set FAQ tab bottom of the tab container. 
        // Set lower value like 1 to set FAQ tab at the first position.

        $faqftw_tab_position = 100; // Set faq tab in last position.

        if (isset($faqftw_options['faqftw_tab_position']) && is_numeric($faqftw_options['faqftw_tab_position'])) {

            $faqftw_tab_position = trim($faqftw_options['faqftw_tab_position']);
        }


        /*---Search Box Section---*/


        $faqftw_show_search_box = 1; // Set faq tab in last position.

        if (isset($faqftw_options['faqftw_show_search_box'])) {

            $faqftw_show_search_box = trim($faqftw_options['faqftw_show_search_box']);
        }

        /*---Meta Info Section---*/

        $faqftw_show_meta_box = 1;

        if (isset($faqftw_options['faqftw_show_meta_box'])) {

            $faqftw_show_meta_box = trim($faqftw_options['faqftw_show_meta_box']);
        }

        /*---Voting Box Section---*/

        $faqftw_show_voting_box = 1;

        if (isset($faqftw_options['faqftw_show_voting_box'])) {

            $faqftw_show_voting_box = trim($faqftw_options['faqftw_show_voting_box']);
        }


        /*---Pagination Section---*/

        $faqftw_enable_pagination = 1;

        if (isset($faqftw_options['faqftw_enable_pagination'])) {

            $faqftw_enable_pagination = trim($faqftw_options['faqftw_enable_pagination']);
        }

        /*---No of Items Per Page---*/

        $faqftw_item_per_page = 5;

        if (isset($faqftw_options['faqftw_item_per_page']) && is_numeric($faqftw_options['faqftw_item_per_page'])) {

            $faqftw_item_per_page = trim($faqftw_options['faqftw_item_per_page']);
        }

        // Specefic Product FAQ Tab Hide section.

        $baf_woo_tab_hide_status = get_post_meta($product->get_id(), 'baf_woo_tab_hide_status', true);

        if (isset($baf_woo_tab_hide_status) && $baf_woo_tab_hide_status == 1) {

            return $tabs;
        }


        $faqtfw_total_faq_string = '';

        $get_faqftw_faq_post_ids = apply_filters('filter_baftfwc_content_data', get_post_meta($product->get_id(), 'faqftw_faq_post_ids'));

        $faqftw_faq_post_ids = implode(',', $get_faqftw_faq_post_ids);

        $tabs['faqtfw_tab'] = [
            'title' => esc_html($faqftw_tab_title . $faqtfw_total_faq_string),
            'priority' => $faqftw_tab_position, // Always display at the end of tab :)
            'callback' => [$this, 'faqtfwCustomTabContent'],
            'content' => '[baf_woo_tab post_ids="' . $faqftw_faq_post_ids . '" sbox="' . $faqftw_show_search_box . '"  paginate="' . $faqftw_enable_pagination . '"  pag_limit="' . $faqftw_item_per_page . '" meta_info="' . $faqftw_show_meta_box . '" voting="' . $faqftw_show_voting_box . '"/]' // custom field
        ];

        return $tabs;
    }

    /**
     * Display tab contents
     *
     * @since: 1.0.0
     */
    public function faqtfwCustomTabContent($key, $tab)
    {
        // allow shortcodes to function
        $content = apply_filters('the_content', $tab['content']);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo apply_filters('woocommerce_custom_product_tabs_lite_content', $content, $tab);
    }
}
