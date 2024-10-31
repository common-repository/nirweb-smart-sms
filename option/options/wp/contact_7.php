<?php
if(is_plugin_active('contact-form-7/wp-contact-form-7.php') ){
    function get_contact_7_options(){
        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
    	$cf7Forms = get_posts( $args );
        foreach($cf7Forms as $form):
            $options[$form->ID] = $form->post_title;
        endforeach;
    	return $options;
    }
}
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => esc_html__('Contact 7', 'nss'),
    'fields' => array(
        array(
          'type'    => 'notice',
          'style'   => 'info',
          'content' => esc_html__('activate this section to notify you when a contact form is submitted.Make sure you have the plugin contact form 7 installed and activated.','nss'),
        ),
        
        array(
            'id'         => 'contact_7_activate',
            'type'       => 'switcher',
            'title'  =>  esc_html__('Activate Notification','nss'),
              'text_width' => 100
        ),
        array(
            'id'         => 'pattern_contact_7',
            'type'       => 'text',
            'title'  =>  esc_html__('Pattern','nss'),
            'subtitle' => esc_html__('use variable form_id in your pattern to refer to the id of the form and use 0 if you are using melipayamak.','nss'),
             'dependency' => array( 'contact_7_activate', '==', 'true' ),
        ),
        array(
              'id'     => 'contact_7_rep',
              'type'   => 'repeater',
              'title'  => esc_html__('specify the form and phonenumber','nss'),
               'dependency' => array( 'contact_7_activate', '==', 'true' ),
              'fields' => array(
                    array(
                        'id'         => 'phone_contact_7',
                        'type'       => 'text',
                        'title'  =>  esc_html__('Your phone Number','nss'),
                    ),
                    array(
                        'id'         => 'contact_7_form',
                        'type'       => 'select',
                        'title'  =>  esc_html__('select the form','nss'),
                        'options' => 'get_contact_7_options',
                        
                    ),
              ),
        ),
    ),
));
