<?php
namespace FTFWCWP\Callbacks\OptionsPanel\Fields;

use FTFWCWP\Callbacks\OptionsPanel\Fields\AllFieldsCb;

/**
 * Class for Woo FAQ Tab shortcode callback.
 *
 * @package FTFWCWP
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class DisplayFieldsCb2 {

	/**
     * Set the fields for the settings page
     */
    public function get_fields() {

			$all_fields_cb = new AllFieldsCb();
			// Register fields here if needed.

			$settings_fields = [
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

			return $settings_fields;

	}
}
