<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'. esc_html__('User Registeration', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
          'id'            => 'user_register',
          'type'          => 'tabbed',
          'tabs'          => array(
            array(
              'title'     => esc_html__('Admin','nss'),
              'fields'    => array(
                array(
                  'id'    => 'user_register_admin_activate',
                  'type'  => 'switcher',
                  'title' => esc_html__('Activate to notify you when a user has registered.','nss'),
                   'text_width' => 100
                ),
                array(
                  'id'    => 'user_register_admin_pattern',
                  'type'  => 'text',
                  'title' => esc_html__('Pattern','nss'),
                  'subtitle' => esc_html__('use variable display_name to refer to user\'s name in your pattern.or use 0 if you are using melipayamak.','nss'),
                  'dependency' => array('user_register_admin_activate','==','true'),
                ),
              )
            ),
            array(
              'title'     => esc_html__('User','nss'),
              'fields'    => array(
                array(
                  'id'    => 'user_register_activate',
                  'type'  => 'switcher',
                  'title' => esc_html__('Activate to send a welcome text to the user.','nss'),
                    'text_width' => 100
                ),
                array(
                  'id'    => 'user_register_pattern',
                  'type'  => 'text',
                  'title' => esc_html__('Pattern','nss'),
                  'subtitle' => esc_html__('use variable display_name to refer to user\'s name in your pattern.or use 0 if you are using melipayamak.','nss'),
                  'dependency' => array('user_register_activate','==','true'),
                ),
              )
            ),
          )
        ),
    ),
));