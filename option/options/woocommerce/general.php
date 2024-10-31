<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('General settings', 'nss'),
    'fields' => array(
        array(
            'id'         => 'sms_panel',
            'type'       => 'select',
            'placeholder' => esc_html__( 'Select an option','nss'),
            'options' =>  array(
                'ip_panel'  => 'IP Panel',
                'melipayamak'  => 'Melipayamak',
//                'raygansms'  => 'Raygansms',
                'smsir'  => 'Sms.ir',
            ),
            'title'  =>  esc_html__('SMS Panel','nss'),
        ),
        array(
            'id'         => 'username',
            'type'       => 'text',
            'title'  =>  esc_html__('IP PANEL username','nss'),
            'dependency' => array( 'sms_panel', '==', 'ip_panel' )
        ),
        array(
            'id'         => 'password',
            'type'       => 'text',
            'title'  =>  esc_html__('IP PANEL password','nss'),
            'dependency' => array( 'sms_panel', '==', 'ip_panel' )
        ),
        array(
            'id'         => 'from',
            'type'       => 'text',
            'title'  =>  esc_html__('IP PANEL sender number','nss'),
            'dependency' => array( 'sms_panel', '==', 'ip_panel' )
        ),
        array(
            'id'         => 'username_meli',
            'type'       => 'text',
            'title'  =>  esc_html__('Melipayamak username','nss'),
            'dependency' => array( 'sms_panel', '==', 'melipayamak' )
        ),
        array(
            'id'         => 'password_meli',
            'type'       => 'text',
            'title'  =>  esc_html__('Melipayamak password','nss'),
            'dependency' => array( 'sms_panel', '==', 'melipayamak' )
        ),
        array(
            'id'         => 'username_raygan',
            'type'       => 'text',
            'title'  =>  esc_html__('RayganSMS username','nss'),
            'dependency' => array( 'sms_panel', '==', 'raygansms' )
        ),
        array(
            'id'         => 'password_raygan',
            'type'       => 'text',
            'title'  =>  esc_html__('RayganSMS password','nss'),
            'dependency' => array( 'sms_panel', '==', 'raygansms' )
        ),
        array(
            'id'         => 'apikey_smsir',
            'type'       => 'text',
            'title'  =>  esc_html__('SMS.IR api key','nss'),
            'dependency' => array( 'sms_panel', '==', 'smsir' )
        ),
        array(
            'id'         => 'seckey_smsir',
            'type'       => 'text',
            'title'  =>  esc_html__('SMS.IR Security key','nss'),
            'dependency' => array( 'sms_panel', '==', 'smsir' )
        ),
        array(
            'id'         => 'to',
            'type'       => 'text',
            'title'  =>  esc_html__('Your Phone Number','nss'),
        ),
        array(
            'id'         => 'user_meta',
            'type'       => 'select',
            'options' => 'nirweb_get_user_meta_keys',
            'title'  =>  esc_html__('user meta','nss'),
            'subtitle' => esc_html__('to access user\'s phone number , specify the meta key','nss')
        ),
    )
));