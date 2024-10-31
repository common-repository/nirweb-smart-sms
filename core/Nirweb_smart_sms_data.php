<?php

class Nirweb_smart_sms_data
{
    static function prepare_panel_product_data($data,$id,$product){
        $output = [];
        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            if($data['product_title'] == '1'){
                $output['product_title'] =  $product->get_title();
            }
            if($data['product_price'] == '1'){
                $output['product_price'] =  $product->get_regular_price();
            }
            if($data['sale_price'] == '1'){
                $output['sale_price'] =   $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price();
            }
            if($data['onsale_from'] == '1'){
                $output['onsale_from'] =   $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->date( "j/m/Y") : date('Y/m/d',time());
            }
            if($data['onsale_to'] == '1'){
                $output['onsale_to'] =  $product->get_date_on_sale_to() ? $product->get_date_on_sale_to()->date( "j/m/Y") : date('Y/m/d',time());
            }
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){

            if($data['product_title'] == '1'){
                $params['Parameter'] = 'product_title';
                $params['ParameterValue'] = $product->get_title();
                $output[] = $params;
                $params = [];
            }
            if($data['product_price'] == '1'){
                $params['Parameter'] = 'product_price';
                $params['ParameterValue'] = $product->get_regular_price();
                $output[] = $params;
                $params = [];
            }
            if($data['sale_price'] == '1'){
                $params['Parameter'] = 'sale_price';
                $params['ParameterValue'] = $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price();
                $output[] = $params;
                $params = [];
            }
            if($data['onsale_from'] == '1'){
                $params['Parameter'] = 'onsale_from';
                $params['ParameterValue'] = $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->date( "j/m/Y") : date('Y/m/d',time());
                $output[] = $params;
                $params = [];
            }
            if($data['onsale_to'] == '1'){
                $params['Parameter'] = 'onsale_to';
                $params['ParameterValue'] = $product->get_date_on_sale_from() ? $product->get_date_on_sale_to()->date( "j/m/Y") : date('Y/m/d',time());
                $output[] = $params;
                $params = [];
            }
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            if($data['product_title'] == '1'){
                $output[] =  $product->get_title();
            }
            if($data['product_price'] == '1'){
                $output[] =  $product->get_regular_price();
            }
            if($data['sale_price'] == '1'){
                $output[] =  $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price();
            }
            if($data['onsale_from'] == '1'){
                $output[] =   $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->date( "j/m/Y") : date('Y/m/d',time());
            }
            if($data['onsale_to'] == '1' ){
                $output[] =   $product->get_date_on_sale_from() ? $product->get_date_on_sale_to()->date( "j/m/Y") : date('Y/m/d',time());
            }
        }
        return $output;
    }

    static function nirweb_smart_sms_post_data($data,$post){
        $output = [];
        $user = get_user_by( 'id', $post->post_author );
        $name =  $user->data->display_name;
        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            if($data['post_title'] == '1'){
                $output['post_title'] =  $post->post_title;
            }
            if($data['post_author'] == '1'){
                $output['post_author'] =  $name;
            }
            if($data['post_excerpt'] == '1'){
                $output['post_excerpt'] =  $post->post_excerpt;
            }
            if($data['guid'] == '1'){
                $output['guid'] =   $post->guid;
            }
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            if($data['post_title'] == '1'){
                $params['Parameter'] = 'post_title';
                $params['ParameterValue'] = $post->post_title;
                $output[] = $params;
                $params = [];
            }
            if($data['post_author'] == '1'){
                $params['Parameter'] = 'post_author';
                $params['ParameterValue'] = $name;
                $output[] = $params;
                $params = [];
            }
            if($data['post_excerpt'] == '1'){
                $params['Parameter'] = 'post_excerpt';
                $params['ParameterValue'] = $post->post_excerpt;
                $output[] = $params;
                $params = [];
            }
            if($data['guid'] == '1'){
                $params['Parameter'] = 'guid';
                $params['ParameterValue'] =  $post->guid;
                $output[] = $params;
                $params = [];
            }
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            if($data['post_title'] == '1'){
                $output[] =  $post->post_title;
            }
            if($data['post_author'] == '1'){
                $output[] = $name;
            }
            if($data['post_excerpt'] == '1'){
                $output[] = $post->post_excerpt;
            }
            if($data['guid'] == '1' ){
                $output[] =   $post->guid;
            }
        }
        return $output;
    }

    static function prepare_panel_order_data($data,$order_id,$order){
        $output = [];
        if (nirweb_smart_sms_option['sms_panel'] == 'melipayamak') {
            if ($data['status'] == '1') {
                switch ($order->get_status()) {
                    case 'on-hold':
                        $output[] = esc_html__('on hold', 'nss');
                        break;
                    case 'processing':
                        $output[] = esc_html__('processing', 'nss');
                        break;
                    case 'completed':
                        $output[] = esc_html__('completed', 'nss');
                        break;
                    case 'cancelled':
                        $output[] = esc_html__('cancelled', 'nss');
                        break;
                    case 'refunded':
                        $output[] = esc_html__('refunded', 'nss');
                        break;
                    case 'failed':
                        $output[] = esc_html__('failed', 'nss');
                        break;
                }
            }
            if ($data['order_id'] == '1') {
                $output[] = $order_id;
            }
            if ($data['customer'] == '1') {
                $output[] = $order->get_formatted_billing_full_name();
            }
            if ($data['price'] == '1') {
                $output[] = $order->get_total();
            }
            if ($data['order_items'] == '1') {
                $items = '';
                foreach ($order->get_items() as $item) {
                    $items .= $item['name'];
                    $items .= '،';
                }
                $output[] = $items;
            }
        }elseif (nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            $params = [];
            if ($data['status'] == '1') {
                switch ($order->get_status()) {
                    case 'on-hold':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('on hold', 'nss');
                        $output[] = $params;
                        break;
                    case 'processing':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] =  esc_html__('processing', 'nss');
                        $output[] = $params;
                        break;
                    case 'completed':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] =  esc_html__('completed', 'nss');
                        $output[] = $params;
                        break;
                    case 'cancelled':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('cancelled', 'nss');
                        $output[] = $params;
                        break;
                    case 'refunded':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] =  esc_html__('refunded', 'nss');
                        $output[] = $params;
                        break;
                    case 'failed':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('failed', 'nss');
                        $output[] = $params;
                        break;
                }
            }
            if ($data['order_id'] == '1') {
                $params['Parameter'] = 'order_id';
                $params['ParameterValue'] = $order_id;
                $output[] = $params;
                $params = [];
            }
            if ($data['customer'] == '1') {
                $params['Parameter'] = 'customer';
                $params['ParameterValue'] = $order->get_formatted_billing_full_name();
                $output[] = $params;
                $params = [];
            }
            if ($data['price'] == '1') {
                $params['Parameter'] = 'price';
                $params['ParameterValue'] = $order->get_total();
                $output[] = $params;
                $params = [];
            }
            if ($data['order_items'] == '1') {
                $items = '';
                foreach ($order->get_items() as $item) {
                    $items .= $item['name'];
                    $items .= '،';
                }
                $params['Parameter'] = 'order_items';
                $params['ParameterValue'] = $items;
                $output[] = $params;
                $params = [];
            }
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'ip_panel'){
            if($data['status'] == '1'){
                $output['status'] = $order->get_status();
                switch($order->get_status()){
                    case 'on-hold':
                        $output['status'] = esc_html__('on hold','nss');
                        break;
                    case 'processing':
                        $output['status'] = esc_html__('processing','nss');
                        break;
                    case 'completed':
                        $output['status'] = esc_html__('completed','nss');
                        break;
                    case 'cancelled':
                        $output['status'] = esc_html__('cancelled','nss');
                        break;
                    case 'refunded':
                        $output['status'] = esc_html__('refunded','nss');
                        break;
                    case 'failed':
                        $output['status'] = esc_html__('failed','nss');
                        break;
                }
            }
            if($data['order_id'] == '1'){
                $output['order_id'] =$order_id;
            }
            if($data['customer'] == '1'){
                $output['customer'] = $order->get_formatted_billing_full_name();
            }
            if($data['price'] == '1'){
                $output['price'] = $order->get_total();
            }
            if($data['order_items'] == '1'){
                $items = '';
                foreach( $order->get_items() as $item ) {
                    $items .= $item['name'];
                    $items .= '،';
                }
                $output['orderitems'] = $items;
            }
        }
        return $output;
    }

    static function perpare_panel_vendor_data($data,$order_id,$order,$vendor_id){
        $output = [];
        $items = '';
        $price = 0;
        foreach( $order->get_items() as $item_id => $item  ) {
            $item_vendor = get_post_field( 'post_author',$item['product_id'] );
            if($item_vendor == $vendor_id){
                $items .= $item['name'];
                $items .= '،';
                $price += $item['line_total'];
            }
        }
        if (nirweb_smart_sms_option['sms_panel'] == 'melipayamak') {
            if($data['status'] == '1'){
                $output[] = $order->get_status();
                switch($order->get_status()){
                    case 'on-hold':
                        $output[] = esc_html__('on hold','nss');
                        break;
                    case 'processing':
                        $output[] = esc_html__('processing','nss');
                        break;
                    case 'completed':
                        $output[] = esc_html__('completed','nss');
                        break;
                    case 'cancelled':
                        $output[] = esc_html__('cancelled','nss');
                        break;
                    case 'refunded':
                        $output[] = esc_html__('refunded','nss');
                        break;
                    case 'failed':
                        $output[] = esc_html__('failed','nss');
                        break;
                }
            }
            if($data['order_id'] == '1'){
                $output[] =$order_id;
            }
            if($data['customer'] == '1'){
                $output[] = $order->get_formatted_billing_full_name();
            }
            if($data['price'] == '1'){
                $output[] = $price;
            }
            if($data['order_items'] == '1'){
                $output[] = $items;
            }
        }elseif (nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            if($data['status'] == '1') {
                $output[] = $order->get_status();
                switch ($order->get_status()) {
                    case 'on-hold':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('on hold', 'nss');
                        $output[] = $params;
                        break;
                    case 'processing':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('processing', 'nss');
                        $output[] = $params;
                        break;
                    case 'completed':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('completed', 'nss');
                        $output[] = $params;
                        break;
                    case 'cancelled':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('cancelled', 'nss');
                        $output[] = $params;
                        break;
                    case 'refunded':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('refunded', 'nss');
                        $output[] = $params;
                        break;
                    case 'failed':
                        $params['Parameter'] = 'status';
                        $params['ParameterValue'] = esc_html__('failed', 'nss');
                        $output[] = $params;
                        break;
                }
            }
            if ($data['order_id'] == '1') {
                $params['Parameter'] = 'order_id';
                $params['ParameterValue'] = $order_id;
                $output[] = $params;
                $params = [];
            }
            if ($data['customer'] == '1') {
                $params['Parameter'] = 'customer';
                $params['ParameterValue'] = $order->get_formatted_billing_full_name();
                $output[] = $params;
                $params = [];
            }
            if($data['price'] == '1'){
                $params['Parameter'] = 'price';
                $params['ParameterValue'] = $price;
                $output[] = $params;
                $params = [];
            }
            if($data['order_items'] == '1'){
                $params['Parameter'] = 'orderitems';
                $params['ParameterValue'] = $items;
                $output[] = $params;
                $params = [];
            }
        }else{
            if($data['status'] == '1'){
                $output['status'] = $order->get_status();
                switch($order->get_status()){
                    case 'on-hold':
                        $output['status'] = esc_html__('on hold','nss');
                        break;
                    case 'processing':
                        $output['status'] = esc_html__('processing','nss');
                        break;
                    case 'completed':
                        $output['status'] = esc_html__('completed','nss');
                        break;
                    case 'cancelled':
                        $output['status'] = esc_html__('cancelled','nss');
                        break;
                    case 'refunded':
                        $output['status'] = esc_html__('refunded','nss');
                        break;
                    case 'failed':
                        $output['status'] = esc_html__('failed','nss');
                        break;
                }
            }
            if($data['order_id'] == '1'){
                $output['order_id'] =$order_id;
            }
            if($data['customer'] == '1'){
                $output['customer'] = $order->get_formatted_billing_full_name();
            }
            if($data['price'] == '1'){
                $output['price'] = $price;
            }
            if($data['order_items'] == '1'){
                $output['orderitems'] = $items;
            }
        }
        return $output;
    }

    static function nirweb_smart_sms_contact_7_data($id){
        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            $output['form_id'] = $id;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            $params['Parameter'] = 'form_id';
            $params['ParameterValue'] = $id;
            $output[] = $params;
            $params = [];

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            $output[] = $id;
        }
        return $output;
    }

    static function nirweb_smart_sms_user_register($id){
        $user = get_user_by( 'id', $id );
        if($user){
            $name =  $user->data->display_name;
        }else{
            $name =  $id;
        }

        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            $output['display_name'] = $name;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            $params['Parameter'] = 'display_name';
            $params['ParameterValue'] = $name;
            $output[] = $params;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            $output[] = $name;
        }
        return $output;
    }

    static function nirweb_smart_sms_verification_code($code){
        include_once(ABSPATH . 'wp-includes/pluggable.php');
        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            $output['code'] = $code;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            $params['Parameter'] = 'code';
            $params['ParameterValue'] = $code;
            $output[] = $params;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            $output[] = $code;
        }
        return $output;
    }

    static function nirweb_smart_sms_learnpress($id,$course_id){
        include_once(ABSPATH . 'wp-includes/pluggable.php');
        $user = get_user_by( 'id', $id );
        if($user){
            $name =  $user->data->display_name;
        }else{
            $name =  $id;
        }
        $course_name = get_the_title($course_id);

        if (nirweb_smart_sms_option['sms_panel'] == 'ip_panel') {
            $output['display_name'] = $name;
            $output['course'] = $course_name;
        }elseif(nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            $params['Parameter'] = 'display_name';
            $params['ParameterValue'] = $name;
            $output[] = $params;
            $params = [];
            $params['Parameter'] = 'course';
            $params['ParameterValue'] = $course_name;
            $output[] = $params;

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            $output[] = $name;
            $output['course'] = $course_name;
        }
        return $output;
    }

    static function nirweb_smart_send_sms($pattern,$input_data,$to){
        if(nirweb_smart_sms_option['sms_panel'] == 'ip_panel'){
            $username = trim(nirweb_smart_sms_option['username']) ? : '';
            $password = trim(nirweb_smart_sms_option['password']) ? : '';
            $from = trim(nirweb_smart_sms_option['from']) ? : '';
            $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern";

            $args = array(
                'body'        => $input_data,
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
            );

            $response = wp_remote_post( $url, $args );

        }elseif(nirweb_smart_sms_option['sms_panel'] == 'melipayamak'){
            ini_set("soap.wsdl_cache_enabled","0");
            $sms = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl",array("encoding"=>"UTF-8"));
            $data = array(
                "username"=> trim(nirweb_smart_sms_option['username_meli']) ? : '',
                "password"=> trim(nirweb_smart_sms_option['password_meli']) ? : '',
                "text"=> $input_data,
                "to"=> $to,
                "bodyId"=> $pattern );
            $send_Result = $sms->SendByBaseNumber($data)->SendByBaseNumberResult;
        }elseif (nirweb_smart_sms_option['sms_panel'] == 'smsir'){
            require_once NIRWEB_SMART_SMS . '/core/panels/smsir.php';
            $APIKey = trim(nirweb_smart_sms_option['apikey_smsir']) ? : '';
            $APIURL = "https://ws.sms.ir/";
            $SecretKey = trim(nirweb_smart_sms_option['seckey_smsir']) ? : '';
            $data = array(
                "ParameterArray" => $input_data,
                "Mobile" => $to,
                "TemplateId" => $pattern
            );
            $SmsIR_UltraFastSend = new SmsIR_UltraFastSend($APIKey,$SecretKey,$APIURL);
            $UltraFastSend = $SmsIR_UltraFastSend->UltraFastSend($data);
        }
    }
}
new Nirweb_smart_sms_data;