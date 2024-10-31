<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('LearnDash', 'nss'),
    'fields' => array(
        array(
            'type'    => 'notice',
            'style'   => 'info',
            'content' => esc_html__('Make sure you have LearnDash installed and activated.','nss'),
        ),
        array(
            'id'            => 'learndash',
            'type'          => 'tabbed',
            'tabs'          => array(
                array(
                    'title'     => esc_html__('Admin','nss'),
                    'fields'    => array(
                        array(
                            'id'    => 'course_complete_admin_activate',
                            'type'  => 'switcher',
                            'title' => esc_html__('Activate to notify you when a student has completed their course.','nss'),
                            'text_width' => 100
                        ),
                        array(
                            'id'    => 'course_complete_admin_pattern',
                            'type'  => 'text',
                            'title' => esc_html__('Pattern','nss'),
                            'subtitle' => esc_html__('use display_name for user\'s display name and course for course\'s title.if you are using melipayamak use 0 for user\'s display name and 1 for course title','nss'),
                            'dependency' => array('course_complete_admin_activate','==','true'),
                        ),
                    )
                ),
                array(
                    'title'     => esc_html__('User','nss'),
                    'fields'    => array(
                        array(
                            'id'    => 'course_complete_user_activate',
                            'type'  => 'switcher',
                            'title' => esc_html__('Activate to send user sms when they have completed their course.','nss'),
                            'text_width' => 100
                        ),
                        array(
                            'id'    => 'course_complete_user_pattern',
                            'type'  => 'text',
                            'title' => esc_html__('Pattern','nss'),
                            'subtitle' => esc_html__('use display_name for user\'s display name and course for course\'s title.if you are using melipayamak use 0 for user\'s display name and 1 for course title','nss'),
                            'dependency' => array('course_complete_user_activate','==','true'),
                        ),
                    )
                ),
            )
        ),
    ),
));