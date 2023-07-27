<?php

/**
 * Plugin Name:     FAQ Tab For WooCommerce - Advanced FAQ Addon
 * Plugin URI: https://bluewindlab.net
 * Description:      FAQ tab for woocommerce Addon allows you to convert you're existing FAQ posts in to WooCommerce product FAQ item with in a minute. You can add unlimited number of FAQ post as product FAQ items and using drag drop feature sort them according to you're choice.
 * Author: Mahbub Alam Khan
 * Version: 1.1.1
 * Author URI: https://bluewindlab.net
 * WP Requires at least: 5.6+
 * Text Domain: baf-faqtfw
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Version Define For Parent Plugin And Addon.

define('FAQTFW_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_advanced_faq_version'));
define('FAQTFW_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Advanced FAQ Manager</b> ');
define('FAQTFW_ADDON_TITLE', '<b>FAQ Tab For WooCommerce - Advanced FAQ Addon</b>');
define('FAQTFW_PARENT_PLUGIN_REQUIRED_VERSION', '1.5.9'); // change plugin required version in here.
define('FAQTFW_ADDON_CURRENT_VERSION', '1.1.1');
define('FAQTFW_ADDON_UPDATER_SLUG', plugin_basename(__FILE__));
define("BAF_WC_PLUGIN_DIR", plugins_url() . '/faq-tab-for-woocommerce/');
define('FAQTFW_DIR', plugin_dir_path(__FILE__));

define("FAQTFW_PLUGIN_CC_ID", "12509686"); // Plugin codecanyon Id.

require_once(FAQTFW_DIR . 'public/class-faqtfw-addon.php');

register_activation_hook(__FILE__, ['BAF_faqtfw', 'activate']);
register_deactivation_hook(__FILE__, ['BAF_faqtfw', 'deactivate']);

add_action('plugins_loaded', ['BAF_faqtfw', 'get_instance']);

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . '/includes/baf-custom-meta-box.php');
    require_once(plugin_dir_path(__FILE__) . 'admin/class-faqtfw-addon-admin.php');
    require_once(plugin_dir_path(__FILE__) . 'admin/includes/settings/faqtfw-admin-settings.php'); // Load Addon option panel.

    add_action('plugins_loaded', ['BAF_faqtfw_Admin', 'get_instance']);
}
