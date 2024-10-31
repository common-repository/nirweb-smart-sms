<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'. esc_html__('Newsletter', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
            'id'    => 'newsletter_activate',
            'type'  => 'switcher',
            'title' => esc_html__('Activate newsletter.','nss'),
            'text_width' => 100
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => esc_html__('use this shortcode to create form to get users\'s phone numbers : [nirweb_newsletter]  ','nss'),
            'dependency' => array('newsletter_activate','==',true)
        ),
        array(
            'id'    => 'newsletter_verification_activate',
            'type'  => 'switcher',
            'title' => esc_html__('Activate to confrim user\'s phone number.','nss'),
            'text_width' => 100,
            'dependency' => array('newsletter_activate','==',true)
        ),
        array(
            'id'    => 'newsletter_verification_pattern',
            'type'  => 'text',
            'title' => esc_html__('Pattern','nss'),
            'subtitle' => esc_html__('use variable code in your pattern to send verification code.','nss'),
            'dependency' => array(['newsletter_activate','==',true],['newsletter_verification_activate','==',true])
        ),
    ),
));