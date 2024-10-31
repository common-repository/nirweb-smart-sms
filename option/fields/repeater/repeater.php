<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_repeater' ) ) {
  class CSF_Field_repeater extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="fas fa-plus-circle"></i>',
      ) );

      if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

        echo '<div class="CFSSMARTSMS-notice CFSSMARTSMS-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'CFSSMARTSMS' ) .'</div>';

      } else {

        echo wp_kses_post($this->field_before());

        echo '<div class="CFSSMARTSMS-repeater-item CFSSMARTSMS-repeater-hidden" data-depend-id="'. wp_kses_post( $this->field['id'] ) .'">';
        echo '<div class="CFSSMARTSMS-repeater-content">';
        foreach ( $this->field['fields'] as $field ) {

          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

          CFSSMARTSMS::field( $field, $field_default, '___'. $field_unique, 'field/repeater' );

        }
        echo '</div>';
        echo '<div class="CFSSMARTSMS-repeater-helper">';
        echo '<div class="CFSSMARTSMS-repeater-helper-inner">';
        echo '<i class="CFSSMARTSMS-repeater-sort fas fa-arrows-alt"></i>';
        echo '<i class="CFSSMARTSMS-repeater-clone far fa-clone"></i>';
        echo '<i class="CFSSMARTSMS-repeater-remove CFSSMARTSMS-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'CFSSMARTSMS' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="CFSSMARTSMS-repeater-wrapper CFSSMARTSMS-data-wrapper" data-field-id="['. wp_kses_post( $this->field['id'] ) .']" data-max="'. wp_kses_post( $args['max'] ) .'" data-min="'. wp_kses_post( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;
            array_walk_recursive(
                $this->value,
                function (&$value) {
                    if (!is_array($value)) {
                        $value = wp_kses_post($value);
                    }
                }
            );
          foreach ( $this->value as $key => $value ) {

            echo '<div class="CFSSMARTSMS-repeater-item">';
            echo '<div class="CFSSMARTSMS-repeater-content">';
            foreach ( $this->field['fields'] as $field ) {

              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              CFSSMARTSMS::field( $field, $field_value, $field_unique, 'field/repeater' );

            }
            echo '</div>';
            echo '<div class="CFSSMARTSMS-repeater-helper">';
            echo '<div class="CFSSMARTSMS-repeater-helper-inner">';
            echo '<i class="CFSSMARTSMS-repeater-sort fas fa-arrows-alt"></i>';
            echo '<i class="CFSSMARTSMS-repeater-clone far fa-clone"></i>';
            echo '<i class="CFSSMARTSMS-repeater-remove CFSSMARTSMS-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'CFSSMARTSMS' ) .'"></i>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="CFSSMARTSMS-repeater-alert CFSSMARTSMS-repeater-max">'. esc_html__( 'You cannot add more.', 'CFSSMARTSMS' ) .'</div>';
        echo '<div class="CFSSMARTSMS-repeater-alert CFSSMARTSMS-repeater-min">'. esc_html__( 'You cannot remove more.', 'CFSSMARTSMS' ) .'</div>';
        echo '<a href="#" class="button button-primary CFSSMARTSMS-repeater-add">'. wp_kses_post($args['button_title']) .'</a>';

        echo wp_kses_post($this->field_after());

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
