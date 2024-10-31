<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'.esc_html__('Birthday congrats', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
            'type'    => 'notice',
            'style'   => 'danger',
            'content' => esc_html__('to access user\'s birthday specify birthday meta key and to send sms specify phone user meta.Make sure it\'s in Gregorian date.','nss'),
        ),
        array(
            'id'    => 'birthday_activate',
            'type'  => 'switcher',
            'title' => esc_html__('Activate to send happy birthday sms.','nss'),
            'text_width' => 100
        ),
        array(
            'id'         => 'user_meta_birthday',
            'type'       => 'select',
            'options' => 'nirweb_get_user_meta_keys',
            'title'  =>  esc_html__('birthday meta key','nss'),
            'dependency' => array('birthday_activate','==','true'),
        ),
        array(
            'id'    => 'birthday_pattern',
            'type'  => 'text',
            'title' => esc_html__('Pattern','nss'),
            'subtitle' => esc_html__('use variable display_name to refer to user\'s name in your pattern.or use 0 if you are using melipayamak.','nss'),
            'dependency' => array('birthday_activate','==','true'),
        ),
    ),
));