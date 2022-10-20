<?php

/**
 * Render the settings screen
 */
function faqftw_settings() {
    ?>

    <div class="wrap faq-wrapper baf-option-panel">

        <h2><?php esc_html_e('FAQ Tab For WooCommerce Settings', 'baf-faqtfw'); ?></h2>

    <?php if (isset($_GET['settings-updated'])) { ?>
            <div id="message" class="updated">
                <p><strong><?php esc_html_e('Settings saved.', 'baf-faqtfw') ?></strong></p>
            </div>
    <?php } ?>

        <form action="options.php" method="post">
    <?php settings_fields('faqftw_options') ?>
            <?php do_settings_sections(__FILE__); ?>

            <p class="submit">
                <input name="submit" type="submit" class="button-primary" value="<?php esc_html_e('Save Settings', 'baf-faqtfw'); ?>"/>
            </p>
        </form>    

    </div> 

    <?php
}

function faqftw_register_settings_fields() {

    // First Parameter option group.
    // Second Parameter contain keyword. use in get_options() function.

    register_setting('faqftw_options', 'faqftw_options');

    // Common Settings.        
    add_settings_section('faqftw_display_section', esc_html__("TAB Content Settings: ", 'baf-faqtfw'), "faqftw_display_section_cb", __FILE__);

    add_settings_field('faqftw_tab_title', esc_html__("Tab Title: ", 'baf-faqtfw'), "faqftw_tab_title_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_tab_position', esc_html__("Tab Position: ", 'baf-faqtfw'), "faqftw_tab_position_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_faq_counter', esc_html__("Display FAQ Counter? ", 'baf-faqtfw'), "faqftw_faq_counter_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_auto_hide_tab', esc_html__("Hide Tab If Total FAQs Are Zero? ", 'baf-faqtfw'), "faqftw_auto_hide_tab_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_show_search_box', esc_html__("Show Search Box? ", 'baf-faqtfw'), "faqftw_show_search_box_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_show_meta_box', esc_html__("Show Meta Info Box? ", 'baf-faqtfw'), "faqftw_show_meta_box_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_show_voting_box', esc_html__("Show Voting Box? ", 'baf-faqtfw'), "faqftw_show_voting_box_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_enable_pagination', esc_html__("Enable FAQ Pagination? ", 'baf-faqtfw'), "faqftw_enable_pagination_settings", __FILE__, 'faqftw_display_section');
    add_settings_field('faqftw_item_per_page', esc_html__("Item Per Page: ", 'baf-faqtfw'), "faqftw_item_per_page_settings", __FILE__, 'faqftw_display_section');
}

/**
 * @Description: Tab Title Settings
 * @Created At: 04-08-2015
 * @Last Edited AT: 04-08-2015
 * @Created By: Mahbub
 * */
function faqftw_tab_title_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_tab_title = esc_html__('FAQ', 'baf-faqtfw');

    if (isset($faqftw_options['faqftw_tab_title'])) {

        $faqftw_tab_title = $faqftw_options['faqftw_tab_title'];
    }

    echo '<input type="text" name="faqftw_options[faqftw_tab_title]" id="faqftw_tab_title" class="medium-text" value="' . sanitize_textarea_field( $faqftw_tab_title ) . '" />';
}

/**
 * @Description: Tab Position Settings
 * @Created At: 04-08-2015
 * @Last Edited AT: 04-08-2015
 * @Created By: Mahbub
 * */
function faqftw_tab_position_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_tab_position = "100";

    if (isset($faqftw_options['faqftw_tab_position'])) {

        $faqftw_tab_position = strtoupper($faqftw_options['faqftw_tab_position']);
    }

    echo '<input type="text" name="faqftw_options[faqftw_tab_position]" id="faqftw_tab_position" class="small-text" value="' . absint( $faqftw_tab_position ) . '" /><em><small> ' . esc_html__('Set number like- 1,2,3. Set big number(100, 200, 300) to display FAQ tab at the last of tab contain .', 'baf-faqtfw') . '</small></em>';
}

/**
 * @Description: FAQ Counter Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_faq_counter_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_faq_counter = 1;

    if (isset($faqftw_options['faqftw_faq_counter'])) {

        $faqftw_faq_counter = $faqftw_options['faqftw_faq_counter'];
    }


    if ($faqftw_faq_counter == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_faq_counter]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Show', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('Hide', 'baf-faqtfw') . '</option>               
                 </select><em><small> ' . esc_html__('Show total number of FAQ items.', 'baf-faqtfw') . '</small></em>';
}

/**
 * @Description: Auto Hide Tab Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_auto_hide_tab_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_auto_hide_tab = 1;

    if (isset($faqftw_options['faqftw_auto_hide_tab'])) {

        $faqftw_auto_hide_tab = $faqftw_options['faqftw_auto_hide_tab'];
    }


    if ($faqftw_auto_hide_tab == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_auto_hide_tab]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Yes', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('No', 'baf-faqtfw') . '</option>               
                 </select>';
}

/**
 * @Description: Show Search Box Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_show_search_box_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_show_search_box = 1;

    if (isset($faqftw_options['faqftw_show_search_box'])) {

        $faqftw_show_search_box = $faqftw_options['faqftw_show_search_box'];
    }


    if ($faqftw_show_search_box == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_show_search_box]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Yes', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('No', 'baf-faqtfw') . '</option>               
                 </select>';
}

/**
 * @Description: Show Voting Box Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_show_voting_box_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_show_voting_box = 1;

    if (isset($faqftw_options['faqftw_show_voting_box'])) {

        $faqftw_show_voting_box = $faqftw_options['faqftw_show_voting_box'];
    }

    if ($faqftw_show_voting_box == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_show_voting_box]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Yes', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('No', 'baf-faqtfw') . '</option>               
                 </select>';
}

/**
 * @Description: FAQ Meta Box Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_show_meta_box_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_show_meta_box = 1;

    if (isset($faqftw_options['faqftw_show_meta_box'])) {

        $faqftw_show_meta_box = $faqftw_options['faqftw_show_meta_box'];
    }


    if ($faqftw_show_meta_box == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_show_meta_box]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Yes', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('No', 'baf-faqtfw') . '</option>               
                 </select>';
}

/**
 * @Description: Enable Pagination Settings.
 * @Created At: 04-07-2015
 * @Last Edited AT: 04-07-2015
 * @Created By: Mahbub
 * */
function faqftw_enable_pagination_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_enable_pagination = 1;

    if (isset($faqftw_options['faqftw_enable_pagination'])) {

        $faqftw_enable_pagination = $faqftw_options['faqftw_enable_pagination'];
    }


    if ($faqftw_enable_pagination == 1) {

        $show_status = "selected=selected";
        $hide_status = "";
    } else {

        $show_status = "";
        $hide_status = "selected=selected";
    }

    echo '<select name="faqftw_options[faqftw_enable_pagination]">
                    <option value="1" ' . $show_status . '>' . esc_html__('Yes', 'baf-faqtfw') . '</option>   
                    <option value="0" ' . $hide_status . '>' . esc_html__('No', 'baf-faqtfw') . '</option>               
                 </select>';
}

/**
 * @Description: Tab Position Settings
 * @Created At: 04-08-2015
 * @Last Edited AT: 04-08-2015
 * @Created By: Mahbub
 * */
function faqftw_item_per_page_settings() {

    $faqftw_options = get_option('faqftw_options');

    $faqftw_item_per_page = "5";

    if (isset($faqftw_options['faqftw_tab_position'])) {

        $faqftw_item_per_page = trim( $faqftw_options['faqftw_item_per_page'] );
    }

    echo '<input type="text" name="faqftw_options[faqftw_item_per_page]" id="faqftw_item_per_page" class="small-text" value="' . absint( $faqftw_item_per_page ) . '" />';
}

/*---Form Input---*/

function faqftw_display_section_cb() {
    // Add option Later.        
}

/**
 * Add the settings page to the admin menu
 */
function faqftw_settings_submenu() {

    add_submenu_page(
            'edit.php?post_type=bwl_advanced_faq', esc_html__('FAQ Tab For WooCommerce Settings', 'baf-faqtfw'), esc_html__('WooCommerce TAB', 'baf-faqtfw'), 'administrator', 'faqftw-settings', 'faqftw_settings'
    );
}

add_action('admin_menu', 'faqftw_settings_submenu');


add_action('admin_init', 'faqftw_register_settings_fields');