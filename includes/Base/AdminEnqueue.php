<?php
namespace FTFWCWP\Base;

/**
 * Class for registering the plugin admin scripts and styles.
 *
 * @package FTFWCWP
 */
class AdminEnqueue {

	/**
	 * Admin script slug.
	 *
	 * @var string $admin_script_slug
	 */
	private $admin_script_slug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend script slug.
		// This is required to hook the loclization texts.
		$this->admin_script_slug = 'baf-faqtfw-admin';
	}

	/**
	 * Register the plugin scripts and styles loading actions.
	 */
	public function register() {
		// for admin.
		add_action( 'admin_enqueue_scripts', [ $this, 'get_the_scripts' ] );
	}
	/**
     * Load the plugin styles and scripts.
     */
	public function get_the_scripts() {

		wp_enqueue_style(
			$this->admin_script_slug,
			FTFWCWP_PLUGIN_STYLES_ASSETS_DIR . 'admin.css',
			[],
			FTFWCWP_PLUGIN_VERSION
		);

				wp_enqueue_script(
					$this->admin_script_slug,
					FTFWCWP_PLUGIN_SCRIPTS_ASSETS_DIR . 'admin.js',
					[ 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ],
					FTFWCWP_PLUGIN_VERSION, true
				);

				$this->get_the_localization_texts();
	}

	/**
	 * Load the localization texts.
	 */
	private function get_the_localization_texts() {

		// Localize scripts.
		// Frontend.
		// Access data: BafFtfwcAdminData.version
		wp_localize_script(
            $this->admin_script_slug,
            'BafFtfwcAdminData',
            [
				'version'      => FTFWCWP_PLUGIN_VERSION,
				'ajaxurl'      => esc_url( admin_url( 'admin-ajax.php' ) ),
				'product_id'   => FTFWCWP_PRODUCT_ID,
				'installation' => get_option( FTFWCWP_PRODUCT_INSTALLATION_TAG ),
			]
		);
	}
}
