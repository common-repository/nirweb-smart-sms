<?php

if (!defined('ABSPATH')) exit;
include_once(ABSPATH . 'wp-includes/pluggable.php');
require_once NIRWEB_SMART_SMS . 'core/Nirweb_smart_sms_data.php';
require_once NIRWEB_SMART_SMS . 'core/core.php';
//require_once NIRWEB_SMART_SMS . 'option/framework.php';
//require_once NIRWEB_SMART_SMS . 'option/options/options.php';
require_once NIRWEB_SMART_SMS . 'inc/woocommerce.php';
require_once NIRWEB_SMART_SMS . 'inc/wp.php';
require_once NIRWEB_SMART_SMS . 'option/codestar-framework.php';
require_once NIRWEB_SMART_SMS . 'option/options/options.php';
//require_once NIRWEB_SMART_SMS . 'settings/settings.php';

add_action('wp_enqueue_scripts',function (){
    wp_enqueue_style( 'style-nirweb-smart-sms', NIRWEB_SMART_SMS_URL.'assets/style.css', false,'1.0.0' );
    wp_enqueue_script('jquery');
    wp_enqueue_script('scripts-nirweb-smart-sms',NIRWEB_SMART_SMS_URL.'assets/scripts.js',['jquery'],'1.0.0',true);
    wp_localize_script(
        'scripts-nirweb-smart-sms',
        'nirweb_smart_sms_js',
        [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'security_nonce' => wp_create_nonce('nirweb_secure_ajax_calls')
        ]
    );
});

add_action('admin_enqueue_scripts',function(){
    wp_enqueue_style( 'wp_admin_nirweb_style', NIRWEB_SMART_SMS_URL.'/assets/admin.css', false, '1.0.0' );
    wp_enqueue_style( 'select_wp_admin_nirweb_style', NIRWEB_SMART_SMS_URL.'/assets/select2.min.css', false, '1.0.0' );
    wp_enqueue_script( 'select_wp_admin_js_nirweb', NIRWEB_SMART_SMS_URL.'/assets/select2.min.js', true, '1.0.0' );
    wp_enqueue_script( 'admin_js_nirweb_smart_sms', NIRWEB_SMART_SMS_URL.'/assets/admin.js', true, '1.0.0' );
     wp_localize_script(
        'admin_js_nirweb_smart_sms',
        'nirweb_smart_sms_js',
        [
           'ajax_url' => admin_url( 'admin-ajax.php' ),
            'security_nonce_admin' => wp_create_nonce('nirweb_admin_secure_ajax_calls'),
           'wait_text' => __('please wait till all texts are sent','nss'),
           'success_text' => __('sent successfully','nss')
        ]
    );
});

