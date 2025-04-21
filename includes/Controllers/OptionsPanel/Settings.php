<?php
namespace FTFWCWP\Controllers\OptionsPanel;

use FTFWCWP\Callbacks\OptionsPanel\SettingsPageCb;
use FTFWCWP\Callbacks\OptionsPanel\Fields\AllFieldsCb;

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

        // Register Section.
		add_settings_section(
            'faqftw_display_section',
            esc_html__( 'TAB Content Settings: ', 'baf-faqtfw' ),
            [ $this, 'faqftw_display_section_cb' ],
            $this->ftfwc_page_id
		);

        $this->set_fields()->register_fields();
	}


    /**
     * Set the fields for the settings page
     */
    public function set_fields() {

        $all_fields_cb = new AllFieldsCb();
        // Register fields here if needed.

        $this->settings_fields = [
            'faqftw_tab_title' => [
                'title'    => esc_html__( 'Tab Title:', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_tab_title_settings' ],
            ],
            'faqftw_tab_position' => [
                'title'    => esc_html__( 'Tab Position:', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_tab_position_settings' ],
            ],
            'faqftw_faq_counter' => [
                'title'    => esc_html__( 'Display FAQ Counter?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_faq_counter_settings' ],
            ],
            'faqftw_auto_hide_tab' => [
                'title'    => esc_html__( 'Hide Tab If Total FAQs Are Zero?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_auto_hide_tab_settings' ],
            ],
            'faqftw_show_search_box' => [
                'title'    => esc_html__( 'Show Search Box?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_show_search_box_settings' ],
            ],
            'faqftw_show_meta_box' => [
                'title'    => esc_html__( 'Show Meta Info Box?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_show_meta_box_settings' ],
            ],
            'faqftw_show_voting_box' => [
                'title'    => esc_html__( 'Show Voting Box?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_show_voting_box_settings' ],
            ],
            'faqftw_enable_pagination' => [
                'title'    => esc_html__( 'Enable FAQ Pagination?', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_enable_pagination_settings' ],
            ],
            'faqftw_item_per_page' => [
                'title'    => esc_html__( 'Item Per Page', 'baf-faqtfw' ),
                'callback' => [ $all_fields_cb, 'faqftw_item_per_page_settings' ],
            ],
        ];

        return $this;

    }


    /**
     * Register the fields for the settings page
     */
    public function register_fields() {

        if ( ! empty( $this->settings_fields ) ) {

			foreach ( $this->settings_fields as $id => $field ) {
				add_settings_field(
                    $id,
                    $field['title'],
                    $field['callback'],
                    $this->ftfwc_page_id,
                    $this->ftfwc_section_id,
				);
			}
		}
    }
}
