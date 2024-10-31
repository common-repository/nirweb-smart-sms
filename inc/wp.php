<?php
/***************************************
#----------Contact 7
 ******************************************************/
if(is_plugin_active('contact-form-7/wp-contact-form-7.php') && nirweb_smart_sms_option['contact_7_activate'] == '1' && !empty(nirweb_smart_sms_option['contact_7_rep'])){

    add_action("wpcf7_before_send_mail",function ($cf7) {
        $cf7 = WPCF7_ContactForm::get_current();
        foreach(nirweb_smart_sms_option['contact_7_rep'] as $item){
            if($item['contact_7_form'] == $cf7->id){
                $output  =  Nirweb_smart_sms_data::nirweb_smart_sms_contact_7_data($cf7->id);
                Nirweb_smart_sms_data::nirweb_smart_send_sms(nirweb_smart_sms_option['pattern_contact_7'],$output,trim($item['phone_contact_7']));
                break;
            }
        }
        return $cf7;
    });
}
/***************************************
#----------LearnDash
 ******************************************************/
if(is_plugin_active('sfwd-lms/sfwd_lms.php')) {
    add_action('learndash_course_completed', function ($data) {
        $course_id = $data['course']->ID;
        $user_id = $data['user']->ID;
        if (nirweb_smart_sms_option['learndash']['course_complete_admin_activate'] == '1' && !empty(nirweb_smart_sms_option['learndash']['course_complete_admin_pattern'])) {
            $output = Nirweb_smart_sms_data::nirweb_smart_sms_learnpress($user_id, $course_id);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(nirweb_smart_sms_option['learndash']['course_complete_admin_pattern'], $output, trim(nirweb_smart_sms_option['to']));
        }
        if (nirweb_smart_sms_option['learndash']['course_complete_user_activate'] == '1' && !empty(nirweb_smart_sms_option['learndash']['course_complete_user_pattern'])) {
            $output = Nirweb_smart_sms_data::nirweb_smart_sms_learnpress($user_id, $course_id);
            $phone = get_user_meta($user_id, nirweb_smart_sms_option['user_meta'], true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(nirweb_smart_sms_option['learndash']['course_complete_user_pattern'], $output, $phone);
        }

    }, 10, 1);
}
