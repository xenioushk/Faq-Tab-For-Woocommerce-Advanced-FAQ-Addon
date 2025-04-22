<?php
namespace FTFWCWP\Traits;

trait OptionsFieldsTraits {

	/**
	 * Text field
	 *
	 * @param string $name  The name of the field.
	 * @param string $id    The id of the field.
	 * @param bool   $value The value of the field.
	 * @param string $placeholder Placeholder text.
	 * @param string $class_name The class of the field.
	 *
	 * @return string
	 */
	public function get_text_field( $name, $id, $value = '', $placeholder = '', $class_name = 'medium-text', $hints = '' ) {
		return sprintf(
			"<input type='text' name='%s' id='%s' class='%s' value='%s' placeholder='%s' />%s",
			esc_attr( $name ),
			esc_attr( $id ),
			esc_attr( $class_name ),
			esc_attr( $value ),
			esc_attr( $placeholder ),
			( ! empty( $hints ) ) ? '<em><small>' . esc_html( $hints ) . '</small></em>' : ''
		);
	}
}
