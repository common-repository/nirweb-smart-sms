<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('Processing', 'nss'),
    'parent' => 'primary_woocommerce',
    'fields' => array(
        array(
            'id'            => 'processing_tab',
            'type'          => 'tabbed',
            'tabs'          => array(
                array(
                    'title'     => esc_html__('Admin','nss'),
                    'fields'    => array(
                        array(
                            'id'         => 'processing_admin_activate',
                            'type'       => 'switcher',
                            'title'  =>  esc_html__('Send SMS','nss'),
                            'default' => false,
                            'text_width' => 100
                        ),
                        array(
                            'id'         => 'processing_admin_pattern',
                            'type'       => 'text',
                            'title'  =>  esc_html__('Pattern Code','nss'),
                            'dependency' => array( 'processing_admin_activate', '==', 'true' )
                        ),
                        array(
                            'id'     => 'processing_admin_options',
                            'type'   => 'fieldset',
                            'title'  => esc_html__('Activate the variables used in your pattern','nss'),
                            'dependency' => array( 'processing_admin_activate', '==', 'true' ),
                            'subtitle' => esc_html__('for mellipayamak use 0 for status , 1 for order_id , 2 for customer , 3 for price and 4 for order_items ,','nss'),
                            'fields' => array(
                                array(
                                    'id'    => 'status',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Status','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable status for order\'s status in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_id',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable order_id for order\'s number in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'customer',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Customer Name','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable customer for the buyer\'s name in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'price',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Price','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable price for the order\'s price in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_items',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order Items','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable orderitems for the order\'s items in your pattern','nss'),
                                ),
                            ),
                        ),
                    )
                ),
                array(
                    'title'     => esc_html__('Customer','nss'),
                    'fields'    => array(
                        array(
                            'id'         => 'processing_customer_activate',
                            'type'       => 'switcher',
                            'title'  =>  esc_html__('Send SMS','nss'),
                            'default' => false,
                            'text_width' => 100
                        ),
                        array(
                            'id'         => 'processing_customer_pattern',
                            'type'       => 'text',
                            'title'  =>  esc_html__('Pattern Code','nss'),
                            'dependency' => array( 'processing_customer_activate', '==', 'true' )
                        ),
                        array(
                            'id'     => 'processing_customer_options',
                            'type'   => 'fieldset',
                            'title'  => esc_html__('Activate the variables used in your pattern','nss'),
                            'subtitle' => esc_html__('for mellipayamak use 0 for status , 1 for order_id , 2 for customer , 3 for price and 4 for order_items ,','nss'),
                            'dependency' => array( 'processing_customer_activate', '==', 'true' ),
                            'fields' => array(
                                array(
                                    'id'    => 'status',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Status','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable status for order\'s status in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_id',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable order_id for order\'s number in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'customer',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Customer Name','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable customer for the buyer\'s name in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'price',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Price','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable price for the order\'s price in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_items',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order Items','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable orderitems for the order\'s items in your pattern','nss'),
                                ),
                            ),
                        ),
                    )
                ),
                array(
                    'title'     => esc_html__('Vendor','nss'),
                    'fields'    => array(
                        array(
                            'id'         => 'processing_vendor_activate',
                            'type'       => 'switcher',
                            'title'  =>  esc_html__('Send SMS','nss'),
                            'default' => false,
                            'text_width' => 100
                        ),
                        array(
                            'id'         => 'processing_vendor_pattern',
                            'type'       => 'text',
                            'title'  =>  esc_html__('Pattern Code','nss'),
                            'dependency' => array( 'processing_vendor_activate', '==', 'true' )
                        ),
                        array(
                            'id'         => 'processing_vendor_metakey',
                            'type'       => 'select',
                            'options' => 'nirweb_get_user_meta_keys',
                            'title'  =>  esc_html__('user meta','nss'),
                            'subtitle' => esc_html__('to access vendor\'s phone number , specify the meta key','nss'),
                            'dependency' => array( 'processing_vendor_activate', '==', 'true' )
                        ),
                        array(
                            'id'     => 'processing_vendor_options',
                            'type'   => 'fieldset',
                            'title'  => esc_html__('Activate the variables used in your pattern','nss'),
                            'subtitle' => esc_html__('for mellipayamak use 0 for status , 1 for order_id , 2 for customer , 3 for price and 4 for order_items ,','nss'),
                            'dependency' => array( 'processing_vendor_activate', '==', 'true' ),
                            'fields' => array(
                                array(
                                    'id'    => 'status',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Status','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable status for order\'s status in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_id',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable order_id for order\'s number in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'customer',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Customer Name','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable customer for the buyer\'s name in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'price',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Price','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable price for the order\'s price in your pattern','nss'),
                                ),
                                array(
                                    'id'    => 'order_items',
                                    'type'  => 'switcher',
                                    'title' => esc_html__('Order Items','nss'),
                                    'text_width' => 100,
                                    'subtitle' => esc_html__('use variable orderitems for the order\'s items in your pattern','nss'),
                                ),
                            ),
                        ),
                    )
                ),
            )
        ),
    )
));