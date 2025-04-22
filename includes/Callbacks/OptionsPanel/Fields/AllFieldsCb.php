<?php
namespace FTFWCWP\Callbacks\OptionsPanel\Fields;

use FTFWCWP\Helpers\PluginConstants;
use FTFWCWP\Traits\OptionsFieldsTraits;

/**
 * Class for Woo FAQ Tab shortcode callback.
 *
 * @package FTFWCWP
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class AllFieldsCb {

	use OptionsFieldsTraits;

	/**
	 * Options array.
     *
	 * @var array
	 */
	public $options;

	/**
	 * Options ID.
	 *
	 * @var string
	 */
	public $options_id;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->options    = PluginConstants::$addon_options;
		$this->options_id = FTFWCWP_OPTIONS_ID; // change here.
	}

	/**
	 * Tab title field.
	 */
	public function faqftw_tab_title_settings() {

		$field_id   = 'faqftw_tab_title'; // change the id.
		$field_name = $this->options_id . "[{$field_id}]";
		$value      = $this->options[ $field_id ] ?? esc_html__( 'FAQ', 'baf-faqtfw' ); // change default value.

		echo $this->get_text_field( $field_name, $field_id, $value ); //phpcs:ignore
	}

	/**
	 * Tab position field.
	 */
	public function faqftw_tab_position_settings() {

		$field_id   = 'faqftw_tab_position'; // change the id.
		$field_name = $this->options_id . "[{$field_id}]";
		$value      = $this->options[ $field_id ] ?? 100; // change default value.
		$hints      = esc_html__( 'Set number like- 1,2,3. Set big number(100, 200, 300) to display FAQ tab at the last of tab contain.', 'baf-faqtfw' );

		echo $this->get_text_field( $field_name, $field_id, intval($value), '', 'small-text', $hints ); //phpcs:ignore

	}


	public function faqftw_faq_counter_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_faq_counter = 1;

		if ( isset( $faqftw_options['faqftw_faq_counter'] ) ) {

			$faqftw_faq_counter = $faqftw_options['faqftw_faq_counter'];
		}

		if ( $faqftw_faq_counter == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_faq_counter]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Show', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'Hide', 'baf-faqtfw' ) . '</option>               
							 </select><em><small> ' . esc_html__( 'Show total number of FAQ items.', 'baf-faqtfw' ) . '</small></em>';
	}


	public function faqftw_auto_hide_tab_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_auto_hide_tab = 1;

		if ( isset( $faqftw_options['faqftw_auto_hide_tab'] ) ) {

			$faqftw_auto_hide_tab = $faqftw_options['faqftw_auto_hide_tab'];
		}

		if ( $faqftw_auto_hide_tab == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_auto_hide_tab]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Yes', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'No', 'baf-faqtfw' ) . '</option>               
							 </select>';
	}

	public function faqftw_show_search_box_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_show_search_box = 1;

		if ( isset( $faqftw_options['faqftw_show_search_box'] ) ) {

			$faqftw_show_search_box = $faqftw_options['faqftw_show_search_box'];
		}

		if ( $faqftw_show_search_box == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_show_search_box]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Yes', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'No', 'baf-faqtfw' ) . '</option>               
							 </select>';
	}


	public function faqftw_show_voting_box_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_show_voting_box = 1;

		if ( isset( $faqftw_options['faqftw_show_voting_box'] ) ) {

			$faqftw_show_voting_box = $faqftw_options['faqftw_show_voting_box'];
		}

		if ( $faqftw_show_voting_box == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_show_voting_box]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Yes', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'No', 'baf-faqtfw' ) . '</option>               
							 </select>';
	}


	public function faqftw_show_meta_box_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_show_meta_box = 1;

		if ( isset( $faqftw_options['faqftw_show_meta_box'] ) ) {

			$faqftw_show_meta_box = $faqftw_options['faqftw_show_meta_box'];
		}

		if ( $faqftw_show_meta_box == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_show_meta_box]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Yes', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'No', 'baf-faqtfw' ) . '</option>               
							 </select>';
	}


	public function faqftw_enable_pagination_settings() {

		$faqftw_options = get_option( 'faqftw_options' );

		$faqftw_enable_pagination = 1;

		if ( isset( $faqftw_options['faqftw_enable_pagination'] ) ) {

			$faqftw_enable_pagination = $faqftw_options['faqftw_enable_pagination'];
		}

		if ( $faqftw_enable_pagination == 1 ) {

			$show_status = 'selected=selected';
			$hide_status = '';
		} else {

			$show_status = '';
			$hide_status = 'selected=selected';
		}

		echo '<select name="faqftw_options[faqftw_enable_pagination]">
									<option value="1" ' . $show_status . '>' . esc_html__( 'Yes', 'baf-faqtfw' ) . '</option>   
									<option value="0" ' . $hide_status . '>' . esc_html__( 'No', 'baf-faqtfw' ) . '</option>               
							 </select>';
	}


	/**
	 * FAQ item per page field.
	 */
	public function faqftw_item_per_page_settings() {

		$field_id   = 'faqftw_item_per_page'; // change the id.
		$field_name = $this->options_id . "[{$field_id}]";
		$value      = $this->options[ $field_id ] ?? 5; // change default value.

		echo $this->get_text_field( $field_name, $field_id, intval($value), '', 'small-text' ); //phpcs:ignore

	}
}
