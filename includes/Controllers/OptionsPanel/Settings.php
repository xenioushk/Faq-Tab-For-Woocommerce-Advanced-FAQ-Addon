<?php
namespace FTFWCWP\Controllers\OptionsPanel;

use FTFWCWP\Callbacks\OptionsPanel\SettingsPageCb;
use FTFWCWP\Callbacks\OptionsPanel\Fields\DisplayFieldsCb;

/**
 * Class for settings page.
 *
 * @package FTFWCWP
 * @since: 1.0.0
 */
class Settings {

    /**
     * Plugin options id.
     *
     * @note: Must be unqiue, and we will fetch data using this id.
     * @var string
     */
    public $options_id = FTFWCWP_OPTIONS_ID;

    /**
     * Plugin page id.
     *
     * @note: Must be unique.
     * @var string
     */
    public $ftfwc_page_id = 'faqftw-settings';

	/**
     * Plugin section id.
     *
     * @note: Section id will be attached to the settings fields.
     * @var string
     */
    public $ftfwc_section_id = 'faqftw_display_section';

    /**
     * Settings fields.
     *
     * @var array
     */
    public $settings_fields = [];

    /**
     * Register the settings page and fields
     */
	public function register() {
        $this->initialize();
	}

    /**
     * Initialize the settings page and fields
     */
    public function initialize() {
        // Initialize the settings page.
        add_action( 'admin_menu',  [ $this, 'register_options_page' ] );
		add_action( 'admin_init',  [ $this, 'register_settings_fields' ] );
    }

    /**
     * Register options page to the parent menu.
     * For this we are going to add the submenu page to the BWL Advanced FAQ menu.
     */
	public function register_options_page() {

		// Initalize Callbacks.
		$settings_page_cb = new SettingsPageCb();

		add_submenu_page(
            'edit.php?post_type=bwl_advanced_faq',
            esc_html__( 'FAQ Tab For WooCommerce Settings', 'baf-faqtfw' ),
            esc_html__( 'WooCommerce TAB', 'baf-faqtfw' ),
            'manage_options',
            $this->ftfwc_page_id,
            [ $settings_page_cb, 'load_template' ]
		);
	}

    /**
     * Display the settings section
     */
	public function faqftw_display_section_cb() {
        // echo 'hello from display sections!';
	}

    /**
     * Register the options group
     */
    public function register_options_group() {

		register_setting( 'faqftw_options', $this->options_id );
    }

    /**
     * Register the settings fields
     */
	public function register_settings_fields() {

        // Register Settings.
        $this->register_options_group();

        // Register Sections.
        $sections = [
            'faqftw_display_section' => [
				'title'    => esc_html__( 'TAB Content Settings: ', 'baf-faqtfw' ),
				'callback' => [ $this, 'faqftw_display_section_cb' ],
            ],
        ];

        foreach ( $sections as $section_id => $section ) {
            add_settings_section(
                $section_id,
                $section['title'],
                $section['callback'],
                $this->ftfwc_page_id
            );
            $this->register_fields( $section_id );
        }

	}


    /**
     * Set the fields for the settings page
     */
    public function get_fields( $section_id ) {

        switch ( $section_id ) {
            case 'faqftw_display_section':
                return ( new DisplayFieldsCb() )->get_fields();
			default:
                return ( new DisplayFieldsCb() )->get_fields();
        }

    }


    /**
     * Register the fields for the settings page
     *
     * @param string $section_id The section id to register the fields to.
     */
    public function register_fields( $section_id = '' ) {

        $settings_fields = $this->get_fields( $section_id );

		foreach ( $settings_fields as $id => $field ) {
			add_settings_field(
				$id,
				$field['title'],
				$field['callback'],
				$this->ftfwc_page_id,
				$section_id,
			);
		}

    }
}
