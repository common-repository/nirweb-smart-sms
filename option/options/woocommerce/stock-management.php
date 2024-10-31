<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('Stock Management', 'nss'),
    'parent' => 'primary_woocommerce',
    'fields' => array(
        array(
            'id'    => 'low_stock',
            'type'  => 'switcher',
            'title' => esc_html__('Low Stock','nss'),
            'text_width' => 100,
            'subtitle' => esc_html__('use variable product for the product name in your pattern','nss'),
        ),
        array(
            'id'    => 'low_stock_pattern',
            'type'  => 'text',
            'title' => esc_html__('Low Stock Pattern Code','nss'),
            'dependency' => array( 'low_stock', '==', 'true' )
        ),
        array(
            'id'    => 'out_of_stock',
            'type'  => 'switcher',
            'title' => esc_html__('Out Of Stock','nss'),
            'text_width' => 100,
            'subtitle' => esc_html__('use variable product for the product name in your pattern','nss'),
        ),
        array(
            'id'    => 'out_of_stock_pattern',
            'type'  => 'text',
            'title' => esc_html__('Out Of Stock Pattern Code','nss'),
            'dependency' => array( 'out_of_stock', '==', 'true' )
        ),
        array(
            'id'    => 'in_stock',
            'type'  => 'switcher',
            'title' => esc_html__('when the product is back in stock','nss'),
            'text_width' => 100,
        ),
        array(
            'id'    => 'in_stock_pattern',
            'type'  => 'text',
            'title' => esc_html__('In Stock Pattern','nss'),
            'dependency' => array( 'in_stock', '==', 'true' )
        ),
        array(
            'id'    => 'do_not_show_when_in_stock',
            'type'  => 'switcher',
            'title' => esc_html__('Do Not Show When in Stock','nss'),
            'text_width' => 100,
            'dependency' => array( 'in_stock', '==', 'true' )
        ),
        array(
            'id'    => 'in_stock_text_field',
            'type'  => 'text',
            'title' => esc_html__('In Stock Title','nss'),
            'dependency' => array( 'in_stock', '==', 'true' )
        ),
        array(
            'id'    => 'in_stock_select',
            'type'  => 'select',
            'placeholder' => esc_html__('Select an option','nss'),
            'options'     => array(
                'under_post_thumbnail'  => esc_html__('Under Product Thumbnail','nss'),
                'product_body'  => esc_html__('In Product Summary','nss'),
                'shortcode'  => esc_html__('use shortcode','nss'),
            ),
            'title' => esc_html__('In Stock Place ','nss'),
            'text_width' => 100,
            'dependency' => array( 'in_stock', '==', 'true' ),
            'default' => 'under_post_thumbnail'
        ),
        array(
            'type' => 'submessage',
            'style' => 'danger',
            'content' => '<p style="font-size: 16px">' . esc_html__('use shortcode [in_stock_nirweb]', 'nss') . '</p>',
            'dependency' => array(['in_stock_select', '==', 'shortcode'],['in_stock', '==', true]),
        ),
        array(
            'id'     => 'in_stock_fields',
            'type'   => 'fieldset',
            'title'  => esc_html__('Activate the variables used in your pattern','nss'),
            'subtitle' => esc_html__('for mellipayamak use 0 for product_title , 1 for product_price , 2 for sale_price , 3 for onsale_from and 4 for onsale_to ,','nss'),
            'dependency' => array( 'in_stock', '==', 'true' ),
            'fields' => array(
                array(
                    'id'    => 'product_title',
                    'type'  => 'switcher',
                    'title' => esc_html__('Product Title','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable product_title for product\'s title in your pattern','nss'),
                ),
                array(
                    'id'    => 'product_price',
                    'type'  => 'switcher',
                    'title' => esc_html__('Product Price','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable product_price for product\'s price in your pattern','nss'),
                ),
                array(
                    'id'    => 'sale_price',
                    'type'  => 'switcher',
                    'title' => esc_html__('Sale Price','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable sale_price for product\'s sale in your pattern','nss'),
                ),
                array(
                    'id'    => 'onsale_from',
                    'type'  => 'switcher',
                    'title' => esc_html__('On Sale From','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable onsale_from for product\'s on sale date from in your pattern','nss'),
                ),
                array(
                    'id'    => 'onsale_to',
                    'type'  => 'switcher',
                    'title' => esc_html__('On Sale To','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable onsale_to for product\'s on sale date to in your pattern','nss'),
                ),
            ),
        ),
    )
));