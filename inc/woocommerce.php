<?php
if(is_plugin_active('woocommerce/woocommerce.php') ) {


    /**********************************************
     # ON HOLD
     **********************************************/
    add_action('woocommerce_thankyou', function ($order_id) {
        $tab = nirweb_smart_sms_option['hold_tab'];
        $order = wc_get_order($order_id);
         $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'on_hold' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }
        if ($tab['hold_customer_activate'] == '1' && !empty($tab['hold_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['hold_customer_options'],$order_id,$order);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['hold_customer_pattern']), $data, $phone);
        }
        if ($tab['hold_admin_activate'] == '1' && !empty($tab['hold_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['hold_admin_options'],$order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['hold_admin_pattern']), $data, $phone);
        }
        if ($tab['hold_vendor_activate'] == '1' && !empty($tab['hold_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author',$item['product_id'] );
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['hold_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['hold_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['hold_vendor_pattern']),$data, $phone);
                }
            }
        }
    }, 10, 1);

    /**********************************************
    # PROCESSING
     **********************************************/
    add_action('woocommerce_order_status_processing', function ($order_id) {
        $tab = nirweb_smart_sms_option['processing_tab'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'processing' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }
        if ($tab['processing_customer_activate'] == '1' && !empty($tab['processing_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['processing_customer_options'], $order_id,$order);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['processing_customer_pattern']), $data, $phone);
        }
        if ($tab['processing_admin_activate'] == '1' && !empty($tab['processing_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['processing_admin_options'],$order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['processing_admin_pattern']), $data, $phone);
        }
        if ($tab['processing_vendor_activate'] == '1' && !empty($tab['processing_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author', $item['product_id']);
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['processing_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['processing_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['processing_vendor_pattern']), $data, $phone);
                }
            }
        }
    });

    /**********************************************
    # COMPLETED
     **********************************************/
    add_action('woocommerce_order_status_completed', function ($order_id) {
        $tab = nirweb_smart_sms_option['completed_tab'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'completed' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }   
        if ($tab['completed_customer_activate'] == '1' && !empty($tab['completed_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['completed_customer_options'], $order_id,$order);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['completed_customer_pattern']), $data, $phone);
        }
        if ($tab['completed_admin_activate'] == '1' && !empty($tab['completed_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['completed_admin_options'], $order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['completed_admin_pattern']), $data, $phone);
        }
        if ($tab['completed_vendor_activate'] == '1' && !empty($tab['completed_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author',$item['product_id']);
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['completed_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['completed_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['completed_vendor_pattern']), $data, $phone);
                }
            }
            exit;
        }
    });

    /**********************************************
    # REFUNDED
     **********************************************/
    add_action('woocommerce_order_status_refunded', function ($order_id) {
        $tab = nirweb_smart_sms_option['refunded_tab'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'refunded' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }
        if ($tab['refunded_customer_activate'] == '1' && !empty($tab['refunded_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['refunded_customer_options'], $order_id,$order);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['refunded_customer_pattern']), $data, $phone);
        }
        if ($tab['refunded_admin_activate'] == '1' && !empty($tab['refunded_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['refunded_admin_options'], $order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['refunded_admin_pattern']), $data, $phone);
        }
        if ($tab['refunded_vendor_activate'] == '1' && !empty($tab['refunded_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author',$item['product_id'] );
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['refunded_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['refunded_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['refunded_vendor_pattern']), $data, $phone);
                }
            }
        }
    });

    /**********************************************
    # FAILED
     **********************************************/
    add_action('woocommerce_order_status_failed', function ($order_id) {
        $tab = nirweb_smart_sms_option['failed_tab'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'failed' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }
        if ($tab['failed_customer_activate'] == '1' && !empty($tab['failed_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['failed_customer_options'], $order_id,$order);
            $order = wc_get_order($order_id);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['failed_customer_pattern']), $data, $phone);
        }
        if ($tab['failed_admin_activate'] == '1' && !empty($tab['failed_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['failed_admin_options'], $order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['failed_admin_pattern']), $data, $phone);
        }
        if ($tab['failed_vendor_activate'] == '1' && !empty($tab['failed_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author',$item['product_id']);
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['failed_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['failed_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['failed_vendor_pattern']), $data, $phone);
                }
            }
        }
    });

    /**********************************************
    # CANCELLED
     **********************************************/
    add_action('woocommerce_order_status_cancelled', function ($order_id) {
        $tab = nirweb_smart_sms_option['cancelled_tab'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        if(nirweb_smart_sms_option['first_purchase_activate'] == '1' && nirweb_smart_sms_option['first_purchase_order_status'] == 'cancelled' && wc_get_customer_order_count($user_id) == 1){
            $data  =  Nirweb_smart_sms_data::nirweb_smart_sms_user_register($user_id);
            $phone = get_user_meta($user_id,nirweb_smart_sms_option['user_meta'],true);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['first_purchase_pattern']),$data, $phone);
        }
        if ($tab['cancelled_customer_activate'] == '1' && !empty($tab['cancelled_customer_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['cancelled_customer_options'], $order_id,$order);
            $phone = $order->get_billing_phone();
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['cancelled_customer_pattern']), $data, $phone);
        }
        if ($tab['cancelled_admin_activate'] == '1' && !empty($tab['cancelled_admin_pattern'])) {
            $data = Nirweb_smart_sms_data::prepare_panel_order_data($tab['cancelled_admin_options'], $order_id,$order);
            $phone = trim(nirweb_smart_sms_option['to']);
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['cancelled_admin_pattern']), $data, $phone);
        }
        if ($tab['cancelled_vendor_activate'] == '1' && !empty($tab['cancelled_vendor_pattern'])) {
            $vendors = [];
            foreach( $order->get_items() as $item_id => $item ) {
                $vendor_id = get_post_field( 'post_author',$item['product_id']);
                if(!in_array($vendor_id,$vendors)){
                    $vendors[] = $vendor_id;
                    $data = Nirweb_smart_sms_data::perpare_panel_vendor_data($tab['cancelled_vendor_options'],$order_id,$order,$vendor_id);
                    $phone = get_user_meta($vendor_id,$tab['cancelled_vendor_metakey'],true);
                    Nirweb_smart_sms_data::nirweb_smart_send_sms(trim($tab['cancelled_vendor_pattern']), $data, $phone);
                }
            }
        }
    });

    /**********************************************
    # LOW STOCK NOTIFICATION
     **********************************************/
    if(nirweb_smart_sms_option['low_stock_pattern']){
        add_filter( 'woocommerce_email_recipient_low_stock',function ($rec,$product){
            $phone = trim(nirweb_smart_sms_option['to']);
            $product_name = $product->get_formatted_name();
            if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
                $output['product'] = $product_name;
            }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
                $params['Parameter'] = 'product';
                $params['ParameterValue'] = $product_name;
                $output[] = $params;
                $params = [];
            }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
                $output[] = $product_name;
            }
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['low_stock_pattern']),$output,$phone);
        }, 10, 2 );
    }
    /**********************************************
    # NO STOCK NOTIFICATION
     **********************************************/
    if(nirweb_smart_sms_option['out_of_stock_pattern']) {
        add_filter('woocommerce_email_recipient_no_stock', function ($rec, $product) {
            $phone = trim(nirweb_smart_sms_option['to']);
            $product_name = $product->get_formatted_name();
            if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
                $output['product'] = $product_name;
            }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
                $params['Parameter'] = 'product';
                $params['ParameterValue'] = $product_name;
                $output[] = $params;
                $params = [];
            }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
                $output[] = $product_name;
            }
            Nirweb_smart_sms_data::nirweb_smart_send_sms(trim(nirweb_smart_sms_option['out_of_stock_pattern']), $output, $phone);
        }, 10, 2);
    }

    /**********************************************
    # In STOCK NOTIFICATION
     **********************************************/
    if(nirweb_smart_sms_option['in_stock'] == '1' && !empty(nirweb_smart_sms_option['in_stock_pattern'])){
        if(nirweb_smart_sms_option['in_stock_select'] == 'under_post_thumbnail'){
            add_action( 'woocommerce_product_thumbnails','nirweb_smart_sms_product_back_in_stock', 9 );
        }elseif (nirweb_smart_sms_option['in_stock_select'] == 'product_body'){
            add_action( 'woocommerce_after_single_product_summary','nirweb_smart_sms_product_back_in_stock', 5 );
        }else{
            add_shortcode( 'in_stock_nirweb','nirweb_smart_sms_product_back_in_stock');
        }
    }

    /**********************************************
    # On Sale NOTIFICATION
     **********************************************/
    if(nirweb_smart_sms_option['on_sale_product'] == '1' && !empty(nirweb_smart_sms_option['sale_product_pattern'])){
        if(nirweb_smart_sms_option['on_sale_product_select'] == 'under_post_thumbnail'){
            add_action( 'woocommerce_product_thumbnails','nirweb_smart_sms_product_on_sale', 10 );
        }elseif (nirweb_smart_sms_option['on_sale_product_select'] == 'product_body'){
            add_action( 'woocommerce_after_single_product_summary','nirweb_smart_sms_product_on_sale', 6 );
        }else{
            add_shortcode( 'on_sale_nirweb','nirweb_smart_sms_product_on_sale');
        }
    }

}