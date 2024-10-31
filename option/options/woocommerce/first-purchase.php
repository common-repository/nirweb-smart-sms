<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('First Purchase', 'nss'),
     'parent' => 'primary_woocommerce',
    'fields' => array(
        array(
          'id'    => 'first_purchase_activate',
          'type'  => 'switcher',
          'title' => esc_html__('Activate to send a text when a user has done their purchase.','nss'),
           'text_width' => 100
        ),
        array(
          'id'    => 'first_purchase_pattern',
          'type'  => 'text',
          'title' => esc_html__('Pattern','nss'),
          'subtitle' => esc_html__('use variable display_name to refer to user\'s name in your pattern.or use 0 if you are using melipayamak.','nss'),
          'dependency' => array('first_purchase_activate','==','true'),
        ),
        array(
          'id'    => 'first_purchase_order_status',
          'dependency' => array('first_purchase_activate','==','true'),
          'type'  => 'select',
          'placeholder' => esc_html__('select an option','nss'),
          'title' => esc_html__('select the order status','nss'),
           'options' => array(
               'on_hold' => esc_html__('On Hold','nss'),
               'processing' => esc_html__('Processing','nss'),
               'completed' => esc_html__('Completed','nss'),
               'refunded' => esc_html__('Refunded','nss'),
               'failed' => esc_html__('Failed','nss'),
               'cancelled' => esc_html__('Cancelled','nss'),
           )
        ),
    ),
));