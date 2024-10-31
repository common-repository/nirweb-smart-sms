<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'.esc_html__('Comments', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
          'id'            => 'comment',
          'type'          => 'tabbed',
          'tabs'          => array(
            array(
              'title'     => esc_html__('Admin','nss'),
              'fields'    => array(
                array(
                  'id'    => 'comment_admin_activate',
                  'type'  => 'switcher',
                  'title' => esc_html__('Activate to notify you when you have a new comment.','nss'),
                   'text_width' => 100
                ),
                array(
                  'id'    => 'comment_admin_pattern',
                  'type'  => 'text',
                  'title' => esc_html__('Pattern','nss'),
                  'subtitle' => esc_html__('use variable display_name to refer to the ID of the comment in your pattern.or use 0 if you are using melipayamak.','nss'),
                  'dependency' => array('comment_admin_activate','==','true'),
                ),
              )
            ),
            array(
              'title'     => esc_html__('User','nss'),
              'fields'    => array(
                array(
                  'type'    => 'notice',
                  'style'   => 'info',
                  'content' => esc_html__('Make sure you have selected user meta in general settings of the plugin to send notification to logged in users.','nss'),
                ),
                array(
                  'id'    => 'comment_user_activate',
                  'type'  => 'switcher',
                  'title' => esc_html__('Activate to notify user that you have replied their comment.','nss'),
                   'text_width' => 100
                ),
                array(
                  'id'    => 'comment_user_pattern',
                  'type'  => 'text',
                  'title' => esc_html__('Pattern','nss'),
                  'subtitle' => esc_html__('use variable display_name to refer to user\'s name in your pattern.or use 0 if you are using melipayamak.','nss'),
                  'dependency' => array('comment_user_activate','==','true'),
                ),
                array(
                  'id'    => 'comment_user_checkbox',
                  'type'  => 'text',
                  'title' => esc_html__('Checkbox text','nss'),
                   'text_width' => 100
                ),
              )
            ),
          )
        ),
    ),
));