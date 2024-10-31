<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'. esc_html__('LearnPress', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
            'type'    => 'notice',
            'style'   => 'info',
            'content' => esc_html__('Make sure you have LearnPress installed and activated.','nss'),
        ),
        array(
            'id'            => 'learnpress',
            'type'          => 'tabbed',
            'tabs'          => array(
                array(
                    'title'     => esc_html__('Admin','nss'),
                    'fields'    => array(
                        array(
                            'id'    => 'enroll_admin_activate',
                            'type'  => 'switcher',
                            'title' => esc_html__('Activate to notify you when a new student has enrolled.','nss'),
                            'text_width' => 100
                        ),
                        array(
                            'id'    => 'enroll_admin_pattern',
                            'type'  => 'text',
                            'title' => esc_html__('Pattern','nss'),
                            'subtitle' => esc_html__('use display_name for user\'s display name and course for course\'s title.if you are using melipayamak use 0 for user\'s display name and 1 for course title','nss'),
                            'dependency' => array('enroll_admin_activate','==','true'),
                        ),
                    )
                ),
                array(
                    'title'     => esc_html__('User','nss'),
                    'fields'    => array(
                        array(
                            'id'    => 'enroll_user_activate',
                            'type'  => 'switcher',
                            'title' => esc_html__('Activate to send user successful enrollment sms.','nss'),
                            'text_width' => 100
                        ),
                        array(
                            'id'    => 'enroll_user_pattern',
                            'type'  => 'text',
                            'title' => esc_html__('Pattern','nss'),
                            'subtitle' => esc_html__('use display_name for user\'s display name and course for course\'s title.if you are using melipayamak use 0 for user\'s display name and 1 for course title','nss'),
                            'dependency' => array('enroll_user_activate','==','true'),
                        ),
                    )
                ),
            )
        ),
    ),
));