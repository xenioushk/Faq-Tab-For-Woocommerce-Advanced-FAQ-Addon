<?php
namespace FTFWCWP\Helpers;

/**
 * Class for plugin constants.
 *
 * @package FTFWCWP
 */
class PluginConstants {

		/**
         * Static property to hold addon options.
         *
         * @var array
         */
	public static $addon_options = [];

	/**
	 * Initialize the plugin options.
	 */
	public static function init() {

		define( 'FTFWCWP_OPTIONS_ID', 'faqftw_options' );

		self::$addon_options = get_option( FTFWCWP_OPTIONS_ID );

		// Addon Installation Date Time.
		define( 'FTFWCWP_INSTALLATION_DATE', 'baf_ftfwc_installation_date' );
		if ( empty( get_option( FTFWCWP_INSTALLATION_DATE ) ) ) {
			update_option( FTFWCWP_INSTALLATION_DATE, date( 'Y-m-d H:i:s' ) );
		}
	}

	/**
	 * Get the relative path to the plugin root.
	 *
	 * @return string
	 * @example wp-content/plugins/<plugin-name>/
	 */
	public static function get_plugin_path(): string {
		return dirname( dirname( __DIR__ ) ) . '/';
	}

	/**
	 * Get the plugin URL.
	 *
	 * @return string
	 * @example http://appealwp.local/wp-content/plugins/<plugin-name>/
	 */
	public static function get_plugin_url(): string {
		return plugin_dir_url( self::get_plugin_path() . FTFWCWP_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::init();
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( FTFWCWP_PLUGIN_DIR . '/' . FTFWCWP_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {
		// This is super important to check if the get_plugin_data function is already loaded or not.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data = get_plugin_data( FTFWCWP_PLUGIN_DIR . FTFWCWP_PLUGIN_ROOT_FILE );

		define( 'FTFWCWP_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'FTFWCWP_PLUGIN_TITLE', $plugin_data['Name'] ?? 'FAQ Tab For WooCommerce - Advanced FAQ Addon' );
		define( 'FTFWCWP_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/lang/' );
		define( 'FTFWCWP_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'FTFWCWP_PLUGIN_FOLDER', 'faq-tab-for-woocommerce' );
		define( 'FTFWCWP_PLUGIN_CURRENT_VERSION', FTFWCWP_PLUGIN_VERSION );
	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'FTFWCWP_PLUGIN_ROOT_FILE', 'faq-tab-for-woocommerce.php' );
		define( 'FTFWCWP_PLUGIN_DIR', self::get_plugin_path() );
		define( 'FTFWCWP_PLUGIN_FILE_PATH', FTFWCWP_PLUGIN_DIR );
		define( 'FTFWCWP_PLUGIN_URL', self::get_plugin_url() );
		define( 'FTFWCWP_CONTROLLER_DIR', FTFWCWP_PLUGIN_DIR . 'includes/Controllers/' );
		define( 'FTFWCWP_VIEWS_DIR', FTFWCWP_PLUGIN_DIR . 'includes/Views/' );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'FTFWCWP_PLUGIN_STYLES_ASSETS_DIR', FTFWCWP_PLUGIN_URL . 'assets/styles/' );
		define( 'FTFWCWP_PLUGIN_SCRIPTS_ASSETS_DIR', FTFWCWP_PLUGIN_URL . 'assets/scripts/' );
		define( 'FTFWCWP_PLUGIN_LIBS_DIR', FTFWCWP_PLUGIN_URL . 'libs/' );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'baf/notifier_ftfwc.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'FTFWCWP_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'FTFWCWP_PLUGIN_UPDATER_SLUG', FTFWCWP_PLUGIN_FOLDER . '/' . FTFWCWP_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'FTFWCWP_PLUGIN_PATH', FTFWCWP_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'FTFWCWP_PRODUCT_ID', '12509686' ); // Plugin codecanyon/themeforest Id.
		define( 'FTFWCWP_PRODUCT_INSTALLATION_TAG', 'baf_ftfwc_installation_' . str_replace( '.', '_', FTFWCWP_PLUGIN_VERSION ) );

		define( 'FTFWCWP_PRODUCT_OPTIONS_PANEL', admin_url( 'edit.php?post_type=bwl_advanced_faq&page=faqftw-settings' ) );
		define( 'FTFWCWP_PRODUCT_DOC', 'https://xenioushk.github.io/docs-plugins-addon/baf-addon/ftfwc/index.html' );
		define( 'FTFWCWP_SUPPORT', 'https://codecanyon.net/item/bwl-advanced-faq-manager/5007135/support/contact' );
		define( 'FTFWCWP_PRODUCT_YOUTUBE_PLAYLIST', '#' );
	}
}
