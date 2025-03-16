<?php

/**
 * Plugin Name:     FAQ Tab For WooCommerce - Advanced FAQ Addon
 * Plugin URI: https://bluewindlab.net/portfolio/faq-tab-for-woocommerce-advanced-faq-addon/
 * Description:      FAQ tab for woocommerce Addon allows you to convert you're existing FAQ posts in to WooCommerce product FAQ item with in a minute. You can add unlimited number of FAQ post as product FAQ items and using drag drop feature sort them according to you're choice.
 * Author: Mahbub Alam Khan
 * Version: 1.1.9
 * Author URI: https://codecanyon.net/user/xenioushk
 * WP Requires at least: 6.0+
 * Text Domain: baf-faqtfw
 * Domain Path: /lang/
 *
 * @package   FTFWC
 * @author    Mahbub Alam Khan
 * @license   GPL-2.0+
 * @link      https://codecanyon.net/user/xenioushk
 * @copyright 2024 BlueWindLab
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Version Define For Parent Plugin And Addon.

define('FAQTFW_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_advanced_faq_version'));
define('FAQTFW_ADDON_PARENT_PLUGIN_TITLE', 'BWL Advanced FAQ Manager');
define('FAQTFW_ADDON_TITLE', 'FAQ Tab For WooCommerce - Advanced FAQ Addon');
define('FAQTFW_PARENT_PLUGIN_REQUIRED_VERSION', '1.5.9'); // change plugin required version in here.
define('FAQTFW_ADDON_CURRENT_VERSION', '1.1.9');
define('FAQTFW_ADDON_UPDATER_SLUG', plugin_basename(__FILE__));
define("BAF_WC_PLUGIN_DIR", plugins_url() . '/faq-tab-for-woocommerce/');
define('FAQTFW_DIR', plugin_dir_path(__FILE__));

define("FAQTFW_PLUGIN_CC_ID", "12509686"); // Plugin codecanyon Id.
define('FAQTFW_PLUGIN_INSTALLATION_TAG', 'baf_ftfwc_installation_' . str_replace('.', '_', FAQTFW_ADDON_CURRENT_VERSION));

define("FAQTFW_PARENT_PURCHASE_VERIFIED_KEY", "baf_purchase_verified");
define("FAQTFW_PARENT_PURCHASE_STATUS", get_option(FAQTFW_PARENT_PURCHASE_VERIFIED_KEY) == 1 ? 1 : 0);

require_once FAQTFW_DIR . 'includes/public/class-faqtfw-addon.php';

register_activation_hook(__FILE__, ['BAF_faqtfw', 'activate']);
register_deactivation_hook(__FILE__, ['BAF_faqtfw', 'deactivate']);

add_action('plugins_loaded', ['BAF_faqtfw', 'get_instance']);

if (is_admin()) {
    include_once plugin_dir_path(__FILE__) . 'includes/baf-custom-meta-box.php';
    include_once plugin_dir_path(__FILE__) . 'includes/admin/class-faqtfw-addon-admin.php';
    include_once plugin_dir_path(__FILE__) . 'includes/admin/includes/settings/faqtfw-admin-settings.php'; // Load Addon option panel.
    add_action('plugins_loaded', ['BAF_faqtfw_Admin', 'get_instance']);
}
