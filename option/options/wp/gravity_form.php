<?php
if(is_plugin_active('gravityforms/gravityforms.php') ) {
    function get_gravity_options(){
        $myforms = RGFormsModel::get_forms();
        foreach ($myforms as $myform) {
            $options[$myform ->id] = $myform->title;
        }
        return $options;
    }
}
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'.esc_html__('Gravity Form', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
          'type'    => 'notice',
          'style'   => 'info',
          'content' =>esc_html__( 'activate this section to notify you when a gravity form is submitted.Make sure you have the plugin gravity form installed and activated.','nss'),
        ),
//        array(
//            'id'            => 'gravity',
//            'type'          => 'tabbed',
//            'tabs'          => array(
//                array(
//                    'title'     => esc_html__('Admin','nss'),
//                    'fields'    => array(
                        array(
                            'id'         => 'gravity_form_activate',
                            'type'       => 'switcher',
                            'title'  =>  esc_html__('Activate Notification','nss'),
                            'text_width' => 100
                        ),
                        array(
                            'id'         => 'pattern_gravity_form',
                            'type'       => 'text',
                            'title'  =>  esc_html__('Pattern','nss'),
                            'subtitle' => esc_html__('use variable form_id in your pattern to refer to the id of the form and use 0 if you are using melipayamak.','nss'),
                            'dependency' => array( 'gravity_form_activate', '==', 'true' ),
                        ),
                        array(
                            'id'     => 'gravity_form_rep',
                            'type'   => 'repeater',
                            'title'  => esc_html__('specify the form and phonenumber','nss'),
                            'dependency' => array( 'gravity_form_activate', '==', 'true' ),
                            'fields' => array(
                                array(
                                    'id'         => 'phone_gravity',
                                    'type'       => 'text',
                                    'title'  =>  esc_html__('Your phone Number','nss'),
                                ),
                                array(
                                    'id'         => 'gravity_form',
                                    'type'       => 'select',
                                    'title'  =>  esc_html__('select the form','nss'),
                                    'options' => 'get_gravity_options',
                                ),
                            ),
                        ),
//                    )

//                array(
//                    'title'     => esc_html__('User','nss'),
//                    'fields'    => array(
//                        array(
//                            'id'         => 'gravity_form_user_activate',
//                            'type'       => 'switcher',
//                            'title'  =>  esc_html__('Activate Notification','nss'),
//                            'text_width' => 100
//                        ),
//                        array(
//                            'id'         => 'gravity_form_user_pattern',
//                            'type'       => 'text',
//                            'title'  =>  esc_html__('Pattern','nss'),
//                            'subtitle' => esc_html__('use variable form_id in your pattern to refer to the id of the form and use 0 if you are using melipayamak.','nss'),
//                            'dependency' => array( 'gravity_form_user_activate', '==', 'true' ),
//                        ),
//                        array(
//                            'id'     => 'gravity_form_user_rep',
//                            'type'   => 'repeater',
//                            'title'  => esc_html__('specify the form and phone number field','nss'),
//                            'dependency' => array( 'gravity_form_user_activate', '==', 'true' ),
//                            'fields' => array(
//                                array(
//                                    'id'         => 'user_gravity_form',
//                                    'type'       => 'select',
//                                    'title'  =>  esc_html__('select the form','nss'),
//                                    'options' => 'get_gravity_options',
//                                ),
//                                array(
//                                    'id'         => 'user_gravity_phone_field',
//                                    'type'       => 'select',
//                                    'title'  =>  esc_html__('select the field','nss'),
////                                    'options' => 'get_gravity_options',
//                                ),
//                            ),
//                        ),
//                    )
//                ),
//            )
//        ),
    ),
));