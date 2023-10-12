<?php
class BAF_WC_Meta_Box
{

    public $custom_fields;

    function __construct($custom_fields)
    {

        $this->custom_fields = $custom_fields; //Set custom field data as global value.

        add_action('add_meta_boxes', [$this, 'baf_wc_metaboxes']);
        add_action('save_post_product', [$this, 'save_baf_wc_meta_box_data']);
    }

    //Custom Meta Box.
    function baf_wc_metaboxes()
    {

        $bwl_cmb_custom_fields = $this->custom_fields;

        // First parameter is meta box ID.
        // Second parameter is meta box title.
        // Third parameter is callback function.
        // Last paramenter must be same as post_type_name

        add_meta_box(
            $bwl_cmb_custom_fields['meta_box_id'],
            $bwl_cmb_custom_fields['meta_box_heading'],
            [&$this, 'show_meta_box'],
            $bwl_cmb_custom_fields['post_type'],
            $bwl_cmb_custom_fields['context'],
            $bwl_cmb_custom_fields['priority']
        );
    }

    function show_meta_box($post)
    {

        $bwl_cmb_custom_fields = $this->custom_fields;

        foreach ($bwl_cmb_custom_fields['fields'] as $custom_field) :

            $field_value = get_post_meta($post->ID, $custom_field['id'], true);
?>

            <?php if ($custom_field['type'] == 'text') : ?>

                <p class="bwl_cmb_row">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?> </label>
                    <input type="<?php echo $custom_field['type'] ?>" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['name'] ?>" class="<?php echo $custom_field['class'] ?>" value="<?php echo esc_attr($field_value); ?>" />
                </p>

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'select') : ?>

                <?php
                $values = get_post_custom($post->ID);


                $selected = isset($values[$custom_field['name']]) ? esc_attr($values[$custom_field['name']][0]) : $custom_field['default_value'];
                ?>

                <p class="bwl_cmb_row">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?> </label>
                    <select name="<?php echo $custom_field['name'] ?>" id="<?php echo $custom_field['id'] ?>">

                        <option value="" selected="selected">- Select -</option>

                        <?php foreach ($custom_field['value'] as $key => $value) : ?>
                            <option value="<?php echo $key ?>" <?php selected($selected, $key); ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>
                        <i><?php echo $custom_field['desc']; ?></i>
                    <?php } ?>
                </p>

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'repeatable_select') : ?>

                <p class="bwl_cmb_row bwl_cmb_db">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?>: </label>

                    <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") : ?>
                        <small class="small-text"><?php echo $custom_field['desc']; ?></small>
                    <?php endif; ?>
                </p>
                <textarea id="bwl_cmb_data_set" style="display: none;"><?php echo json_encode($custom_field['default_value']); ?></textarea>

                <ul class="bwl_cmb_repeat_field_container">

                    <?php
                    $i = 0;
                    $field_value = apply_filters('filter_baftfwc_content_data', get_post_meta($post->ID, $custom_field['id']));

                    if (!empty($field_value) && is_array($field_value)) {

                        foreach ($field_value as $db_save_key => $db_save_value) {


                            // Find Current Selected Field.
                    ?>

                            <li class="bwl_cmb_repeat_row" data-row_count="<?php echo $i; ?>">

                                <?php
                                if (!empty($custom_field['label_text'])) {
                                    echo "<span class='label'>" . $custom_field['label_text'] . "</span>";
                                }
                                ?>

                                <select id="<?php echo $custom_field['id'] . '_' . $i; ?>" name="<?php echo $custom_field['name'] . '[' . $i . ']' ?>">

                                    <option value="" selected="selected"><?php esc_html_e("- Select -", "baf-faqtfw") ?></option>

                                    <?php foreach ($custom_field['default_value'] as $default_key => $default_value) : ?>
                                        <option value="<?php echo $default_key ?>" <?php echo ($db_save_value == $default_key) ? 'selected=selected' : ''; ?>><?php echo $default_value; ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>

                                <a class="delete_row" title="<?php esc_html_e("Delete", "baf-faqtfw") ?>"><?php esc_html_e("Delete", "baf-faqtfw") ?></a>
                            </li>

                    <?php
                            $i++;
                        }
                    }
                    ?>
                </ul>

                <input id="add_new_row" type="button" class="button" value="<?php echo $custom_field['btn_text']; ?>" data-delete_text="<?php esc_html_e("Delete", "baf-faqtfw") ?>" data-upload_text="<?php esc_html_e("Upload", "baf-faqtfw") ?>" data-field_type="<?php echo $custom_field['type'] ?>" data-field_name="<?php echo $custom_field['name'] ?>" data-label_text="<?php echo $custom_field['label_text']; ?>">


            <?php endif; ?>

<?php
        endforeach;
    }

    function save_baf_wc_meta_box_data($id)
    {

        global $post;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

            return $id;
        } else {

            $baf_wc_meta_custom_fields = $this->custom_fields;

            $baf_wc_excluded_fileds = ['faqftw_faq_post_ids'];

            foreach ($baf_wc_meta_custom_fields['fields'] as $custom_field) {

                if (isset($_POST[$custom_field['name']])) {

                    // Updated in version 1.1.2

                    if (!in_array($custom_field['name'], $baf_wc_excluded_fileds)) {

                        update_post_meta($id, $custom_field['name'], strip_tags($_POST[$custom_field['name']]));
                    }
                }
            }

            // Repeatable KB Select Fields Data Saving In Here.
            // Introduced in version 1.1.2

            if (isset($_POST['faqftw_faq_post_ids'])) {

                $faqftw_count_prev_post_meta = get_post_meta($id, 'faqftw_faq_post_ids');

                if (count($faqftw_count_prev_post_meta) > 1) {
                    //remove old meta fields and then update data.
                    delete_post_meta($id, 'faqftw_faq_post_ids');
                }

                update_post_meta($id, 'faqftw_faq_post_ids', $_POST['faqftw_faq_post_ids']);
            } else if (isset($_POST['action']) &&  $_POST['action'] != 'inline-save') {

                delete_post_meta($id, 'faqftw_faq_post_ids');
            }
        }
    }
}

// Register Custom Meta Box For BWL Pro Related Post Manager

function baf_wc_custom_meta_init()
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

            $bkb_title = ucfirst(get_the_title());

            $bkb_post_id = get_the_ID();

            $faqftw_faq_post_ids[$bkb_post_id] = $bkb_title;

        endwhile;

    endif;

    wp_reset_query();

    $cmb_bkb_woo_item_fields = [
        'meta_box_id' => 'cmb_bkb_woo_item_settings', // Unique id of meta box.
        'meta_box_heading' => 'FAQ Item Settings', // That text will be show in meta box head section.
        'post_type' => 'product', // define post type. go to register_post_type method to view post_type name.        
        'context' => 'normal',
        'priority' => 'high',
        'fields' => [
            'baf_woo_tab_hide_status' => [
                'title' => esc_html__("Hide FAQ Tab?", "baf-faqtfw"),
                'id' => 'baf_woo_tab_hide_status',
                'name' => 'baf_woo_tab_hide_status',
                'type' => 'select',
                'value' => [
                    '1' => esc_html__("Yes", "baf-faqtfw"),
                    '2' => esc_html__("No", "baf-faqtfw")
                ],
                'default_value' => 2,
                'class' => 'widefat'
            ],
            'faqftw_faq_post_ids' => [
                'title' => esc_html__("Add FAQ Items", "baf-faqtfw"),
                'id' => 'faqftw_faq_post_ids',
                'name' => 'faqftw_faq_post_ids',
                'type' => 'repeatable_select',
                'value' => '',
                'default_value' => $faqftw_faq_post_ids,
                'class' => 'widefat',
                'btn_text' => 'Add New FAQ',
                'label_text' => '',
                'field_type' => 'select'
            ]
        ]
    ];


    new BAF_WC_Meta_Box($cmb_bkb_woo_item_fields);
}

// META BOX START EXECUTION FROM HERE.

add_action('admin_init', 'baf_wc_custom_meta_init');
