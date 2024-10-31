<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'. esc_html__('New Post', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
            'id'    => 'new_post_activate',
            'type'  => 'switcher',
            'title' => esc_html__('Activate to notify users when a new post is published.','nss'),
            'text_width' => 100
        ),
        array(
            'id'    => 'new_post_pattern',
            'type'  => 'text',
            'title' => esc_html__('Pattern','nss'),
            'dependency' => array('new_post_activate','==','true'),
        ),
        array(
            'id'    => 'new_post_type',
            'type'  => 'select',
            'options' => 'post_types',
            'placeholder' => esc_html__('select an option','nss'),
            'title' => esc_html__('Post Type','nss'),
            'dependency' => array('new_post_activate','==','true'),
            'ajax' => true,
            'chosen' => true,
            'multiple' => true
        ),
        array(
            'id'     => 'new_post_options',
            'type'   => 'fieldset',
            'title'  => esc_html__('Activate the variables used in your pattern','nss'),
            'subtitle' => esc_html__('for mellipayamak use 0 for title , 1 for excerpt , 2 for author , 3 for guid,','nss'),
            'dependency' => array( 'new_post_activate', '==', 'true' ),
            'fields' => array(
                array(
                    'id'    => 'post_title',
                    'type'  => 'switcher',
                    'title' => esc_html__('Title','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable post_title for the title of the post in your pattern','nss'),
                ),
                array(
                    'id'    => 'post_excerpt',
                    'type'  => 'switcher',
                    'title' => esc_html__('Excerpt','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable post_excerpt for the excerpt of the post in your pattern','nss'),
                ),
                array(
                    'id'    => 'post_author',
                    'type'  => 'switcher',
                    'title' => esc_html__('Author','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable post_author for the author of the post in your pattern','nss'),
                ),
                array(
                    'id'    => 'guid',
                    'type'  => 'switcher',
                    'title' => esc_html__('Guid','nss'),
                    'text_width' => 100,
                    'subtitle' => esc_html__('use variable guid for the short link of the post in your pattern','nss'),
                ),
            ),
        ),
    ),
));