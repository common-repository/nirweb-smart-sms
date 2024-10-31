<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: date
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_date' ) ) {
  class CSF_Field_date extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $default_settings = array(
        'dateFormat' => 'mm/dd/yy',
      );

      $settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
      $settings = wp_parse_args( $settings, $default_settings );

      echo wp_kses_post($this->field_before());

      if ( ! empty( $this->field['from_to'] ) ) {

        $args = wp_parse_args( $this->field, array(
          'text_from' => esc_html__( 'From', 'CFSSMARTSMS' ),
          'text_to'   => esc_html__( 'To', 'CFSSMARTSMS' ),
        ) );

        $value = wp_parse_args( $this->value, array(
          'from' => '',
          'to'   => '',
        ) );

        echo '<label class="CFSSMARTSMS--from">'. wp_kses_post( $args['text_from'] ) .' <input type="text" name="'. wp_kses_post( $this->field_name( '[from]' ) ) .'" value="'. wp_kses_post( $value['from'] ) .'"'. wp_kses_post($this->field_attributes()) .'/></label>';
        echo '<label class="CFSSMARTSMS--to">'. wp_kses_post( $args['text_to'] ) .' <input type="text" name="'. wp_kses_post( $this->field_name( '[to]' ) ) .'" value="'. wp_kses_post( $value['to'] ) .'"'. wp_kses_post($this->field_attributes()) .'/></label>';

      } else {

        echo '<input type="text" name="'. wp_kses_post( $this->field_name() ) .'" value="'. wp_kses_post( $this->value ) .'"'. wp_kses_post($this->field_attributes()) .'/>';

      }

      echo '<div class="CFSSMARTSMS-date-settings" data-settings="'. wp_kses_post( json_encode( $settings ) ) .'"></div>';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
        wp_enqueue_script( 'jquery-ui-datepicker' );
      }

    }

  }
}
