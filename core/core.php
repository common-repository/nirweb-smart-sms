<?php
/***************************************************************************
#  phones database
 ***************************************************************************/
if(nirweb_smart_sms_option['in_stock'] == '1') {
    add_action('admin_init', function () {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'contact_phones_smart_sms';
        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        if ($wpdb->get_var($query) != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
             id  int(255) NOT NULL AUTO_INCREMENT,
            phone  varchar(15) NOT NULL,
            product_id  varchar(15) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    });
}
if(nirweb_smart_sms_option['on_sale_product'] == '1') {
    add_action('admin_init', function () {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'sale_contact_phones_smart_sms';
        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        if ($wpdb->get_var($query) != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
             id  int(255) NOT NULL AUTO_INCREMENT,
            phone  varchar(15) NOT NULL,
            product_id  varchar(15) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    });
}
/***************************************************************************
#   User Metas
 ***************************************************************************/
function nirweb_get_user_meta_keys()
{
    global $wpdb;
    $metakey = [];
    $keys = $wpdb->get_col("SELECT DISTINCT meta_key FROM {$wpdb->usermeta}");
    foreach ($keys as $key) {

        $metakey[$key] = $key;
    }

    return $metakey;

}

if(is_plugin_active('woocommerce/woocommerce.php') ) {
/***************************************************************************
#   back in stock fields product single page
 ***************************************************************************/
function nirweb_smart_sms_product_back_in_stock(){
    $product = wc_get_product(get_the_ID());
    if(nirweb_smart_sms_option['do_not_show_when_in_stock'] == '1' && $product->get_stock_quantity() > 1){
        return;
    }
    ?>
   <div class="nirweb_smart_sms_in_stock">
       <label>
           <?php  nirweb_smart_sms_option['in_stock_text_field'] ? esc_html_e('Tell me when it\'s back in stock','nss') : '';  ?>
           <input type="checkbox" id="notity_me_when_in_stock">
       </label>
       <div class="nirweb_smart_sms_phone_fields">
           <input type="text" name="in_stock_phone" placeholder="<?php esc_html_e('Your Phonenumber','nss') ?>">
           <button data-product="<?= esc_html(get_the_ID()) ?>" ><?php esc_html_e('Submit','nss') ?></button>
           <div class="nirweb_smart_sms_response"></div>
       </div>
   </div>
    <?php
}
/***************************************************************************
#  On sale fields product single page
 ***************************************************************************/
function nirweb_smart_sms_product_on_sale(){
    $product = wc_get_product(get_the_ID());
    if(nirweb_smart_sms_option['do_not_show_when_is_on_sale'] == '1' && $product->get_sale_price()){
        return;
    }
    ?>
    <div class="nirweb_smart_sms_sale">
        <label>
            <?php nirweb_smart_sms_option['on_sale_text_field'] ?  esc_html_e('Tell me when it\'s on sale','nss') : ''?>
            <input type="checkbox" id="notity_me_when_on_sale">
        </label>
        <div class="nirweb_smart_sms_phone_fields">
            <input type="text" name="in_stock_phone" placeholder="<?php esc_html_e('Your Phonenumber','nss') ?>">
            <button data-product="<?= esc_html(get_the_ID()) ?>" ><?php esc_html_e('Submit','nss') ?></button>
            <div class="nirweb_smart_sms_response"></div>
        </div>
    </div>
<?php
    }

/***************************************************************************
#   Send phone
 ***************************************************************************/
add_action( 'wp_ajax_nirweb_smart_sms_save_phone', 'nirweb_smart_sms_save_phone' );
add_action( 'wp_ajax_nopriv_nirweb_smart_sms_save_phone', 'nirweb_smart_sms_save_phone' );
function nirweb_smart_sms_save_phone(){

    $data = [];
    $phone  = sanitize_text_field($_POST['phone']);
    $product_id  = sanitize_text_field($_POST['product_id']);

    if (!isset($_POST['sec_token']) || !wp_verify_nonce($_POST['sec_token'],'nirweb_secure_ajax_calls')){
        $data[0] = -1;
        $data[1] = esc_html__('Invalid Request','nss');
        echo json_encode($data);
        exit;
    }

    if(empty($phone)){
        $data[0] = -1;
        $data[1] = esc_html__('Fill out all fields','nss');
        echo json_encode($data);
        exit;
    }
    if(!is_numeric($phone)){
        $data[0] = -1;
        $data[1] = esc_html__('enter a valid phoneumber','nss');
        echo json_encode($data);
        exit;
    }
    global $wpdb;
    $tbl = $wpdb->prefix.'contact_phones_smart_sms';

    $result = $wpdb->get_results( $wpdb->prepare ( "select * from $tbl WHERE phone = %s", $phone ) );

    if($result){
        $data[0] = -1;
        $data[1] = esc_html__('we have your number.we\'ll notify you when the product is back in stock','nss');
        echo json_encode($data);
        exit;
    }

    $insert_id  = $wpdb->insert(
        $tbl,
        array(
            'phone' =>  $phone,
            'product_id' =>  $product_id,
        ),
        array(
            '%s',
            '%d',
        )
    );

    if(!$insert_id){
        $data[0] = -1;
        $data[1] = esc_html__('something went wrong','nss');
        echo json_encode($data);
        exit;
    }
    $data[0] = 1;
    $data[1] = esc_html__('You phoneumber is successfully saved.we\'ll notify you when the product is back in stock','nss');
    echo json_encode($data);
    exit();
}
/***************************************************************************
#   Send phone
 ***************************************************************************/
add_action( 'wp_ajax_nirweb_smart_sms_save_phone_sale', 'nirweb_smart_sms_save_phone_sale' );
add_action( 'wp_ajax_nopriv_nirweb_smart_sms_save_phone_sale', 'nirweb_smart_sms_save_phone_sale' );
function nirweb_smart_sms_save_phone_sale(){
    $data = [];
    $phone  = sanitize_text_field($_POST['phone']);
    $product_id  = sanitize_text_field($_POST['product_id']);

    if (!isset($_POST['sec_token']) || !wp_verify_nonce($_POST['sec_token'],'nirweb_secure_ajax_calls')){
        $data[0] = -1;
        $data[1] = esc_html__('Invalid Request','nss');
        echo json_encode($data);
        exit;
    }

    if(empty($phone)){
        $data[0] = -1;
        $data[1] = esc_html__('Fill out all fields','nss');
        echo json_encode($data);
        exit;
    }
    if(!is_numeric($phone)){
        $data[0] = -1;
        $data[1] = esc_html__('enter a valid phoneumber','nss');
        echo json_encode($data);
        exit;
    }
    global $wpdb;
    $tbl = $wpdb->prefix.'sale_contact_phones_smart_sms';
    $result = $wpdb->get_row( $wpdb->prepare ( "select * from $tbl WHERE phone = %s", $phone ) );

    if($result){
        $data[0] = -1;
        $data[1] = esc_html__('we have your number.we\'ll notify you when the product is on sale','nss');
        echo json_encode($data);
        exit;
    }

    $insert_id  = $wpdb->insert(
        $tbl,
        array(
            'phone' =>  $phone,
            'product_id' =>  $product_id,
        ),
        array(
            '%s',
            '%d',
        )
    );

    if(!$insert_id){
        $data[0] = -1;
        $data[1] = esc_html__('something went wrong','nss');
        echo json_encode($data);
        exit;
    }
    $data[0] = 1;
    $data[1] = esc_html__('You phoneumber is successfully saved.we\'ll notify you when the product is on sale','nss');
    echo json_encode($data);
    exit();
}
/***************************************************************************
#   notification
 ***************************************************************************/
add_action('add_meta_boxes', function (){
    add_meta_box(
        'nirweb_smart_sms_notif',
        esc_html__( 'notification', 'nss' ),
        'product_notifiction_metabox_nirweb_smart_sms',
        'product',
        'side',
        'default'
    );
});

function product_notifiction_metabox_nirweb_smart_sms( $post ) { ?>

    <?php wp_nonce_field( 'product_notifiction_metabox_nirweb_smart_sms', 'product_notifiction_metabox_nirweb_smart_sms_val' ); ?>
        <?php if(nirweb_smart_sms_option['in_stock'] == '1'): ?>
    <p>
        <label>
            <?= esc_html__('notify users that product is back in stock','nss') ?>
            <input type="checkbox" name="product_notifiction_metabox_nirweb_smart_sms_input">
        </label>
    </p>
    <?php endif;if(nirweb_smart_sms_option['on_sale_product'] == '1'):?>
    <p>
        <label>
            <?= esc_html__('notify users that product is on sale','nss') ?>
            <input type="checkbox" name="product_sale_metabox_nirweb_smart_sms_input">
        </label>
    </p>
    <?php endif; ?>
<?php }
add_action( 'save_post',function (){
    if(isset($_POST['post_ID'])){
        $id = sanitize_text_field($_POST['post_ID']);
        $product = wc_get_product($id);
    }
    if ( isset( $_POST['product_notifiction_metabox_nirweb_smart_sms_val'] ) && wp_verify_nonce( $_POST['product_notifiction_metabox_nirweb_smart_sms_val'], 'product_notifiction_metabox_nirweb_smart_sms' )
    ) {

        if(isset($_POST['product_notifiction_metabox_nirweb_smart_sms_input']) && $_POST['product_notifiction_metabox_nirweb_smart_sms_input'] == 'on'){
            global $wpdb;
            $table_name = $wpdb->prefix . 'contact_phones_smart_sms';
            $result = $wpdb->get_results( $wpdb->prepare ( "select * from $table_name WHERE product_id = %d ", $id ) );
            $data = nirweb_smart_sms_option['in_stock_fields'];
            $output = Nirweb_smart_sms_data::prepare_panel_product_data($data,$id,$product);
            foreach ($result  as $row){
                Nirweb_smart_sms_data::nirweb_smart_send_sms(nirweb_smart_sms_option['in_stock_pattern'],$output,$row->phone);
            }
            $wpdb->query("TRUNCATE TABLE `$table_name`");
        }
        if(isset($_POST['product_sale_metabox_nirweb_smart_sms_input']) && $_POST['product_sale_metabox_nirweb_smart_sms_input'] == 'on'){
            global $wpdb;
            $table_name = $wpdb->prefix . 'sale_contact_phones_smart_sms';
            $result = $wpdb->get_results( $wpdb->prepare ( "select * from $table_name WHERE product_id = %d ", $id ) );
            $data = nirweb_smart_sms_option['on_sale_product_fields'];
            $output = Nirweb_smart_sms_data::prepare_panel_product_data($data,$id,$product);
            foreach ($result  as $row){
                Nirweb_smart_sms_data::nirweb_smart_send_sms(nirweb_smart_sms_option['sale_product_pattern'],$output,$row->phone);
            }
            $wpdb->query("TRUNCATE TABLE `$table_name`");
        }

    }

}, 10, 2 );
}

/***************************************************************************
#   Send SMS
 ***************************************************************************/
add_action('admin_menu',function() {

    add_submenu_page(
        'nirweb_ticket_manage_tickets',
        esc_html__( 'Settings', 'nss' ),
        esc_html__( 'Settings', 'nss' ),
        'manage_options',
        'nirweb_ticket_settings',
        'nirweb_ticket_settings_func'
    );

    add_menu_page(
        esc_html__('Nirweb Smart SMS','nss'),
        esc_html__('Nirweb Smart SMS','nss'),
        'manage_options',
        'send_sms_nirweb_smart_sms',
        false,
        'dashicons-bell',
        30);


    add_submenu_page('send_sms_nirweb_smart_sms',esc_html__('Send SMS','nss'), '<div class="nirweb_codestar_icon bg_solid">' .esc_html__('Send SMS','nss').'<span><i class="fas fa-lock"></i>PRO</span></span></div>', 'manage_options', 'send_sms_nirweb_smart_sms', function() { ?>
        <?php
        $current_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'all_users';
        $tabs = array(
            'all_users' =>  [esc_html__('Send SMS To ALL Users','nss') , esc_html__('send sms to all users.','nss'),'<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
	 viewBox="0 0 60 60" >
<path d="M55.517,46.55l-9.773-4.233c-0.23-0.115-0.485-0.396-0.704-0.771l6.525-0.005c0.114,0.011,2.804,0.257,4.961-0.67
	c0.817-0.352,1.425-1.047,1.669-1.907c0.246-0.868,0.09-1.787-0.426-2.523c-1.865-2.654-6.218-9.589-6.354-16.623
	c-0.003-0.121-0.397-12.083-12.21-12.18c-1.739,0.014-3.347,0.309-4.81,0.853c-0.319-0.813-0.789-1.661-1.488-2.459
	C30.854,3.688,27.521,2.5,23,2.5s-7.854,1.188-9.908,3.53c-2.368,2.701-2.148,5.976-2.092,6.525v5.319c-0.64,0.729-1,1.662-1,2.625
	v4c0,1.217,0.553,2.352,1.497,3.109c0.916,3.627,2.833,6.36,3.503,7.237v3.309c0,0.968-0.528,1.856-1.377,2.32l-8.921,4.866
	C1.801,46.924,0,49.958,0,53.262V57.5h44h2h14v-3.697C60,50.711,58.282,47.933,55.517,46.55z M44,55.5H2v-2.238
	c0-2.571,1.402-4.934,3.659-6.164l8.921-4.866C16.073,41.417,17,39.854,17,38.155v-4.019l-0.233-0.278
	c-0.024-0.029-2.475-2.994-3.41-7.065l-0.091-0.396l-0.341-0.22C12.346,25.803,12,25.176,12,24.5v-4c0-0.561,0.238-1.084,0.67-1.475
	L13,18.728V12.5l-0.009-0.131c-0.003-0.027-0.343-2.799,1.605-5.021C16.253,5.458,19.081,4.5,23,4.5
	c3.905,0,6.727,0.951,8.386,2.828c0.825,0.932,1.24,1.973,1.447,2.867c0.016,0.07,0.031,0.139,0.045,0.208
	c0.014,0.071,0.029,0.142,0.04,0.21c0.013,0.078,0.024,0.152,0.035,0.226c0.008,0.053,0.016,0.107,0.022,0.158
	c0.015,0.124,0.027,0.244,0.035,0.355c0.001,0.009,0.001,0.017,0.001,0.026c0.007,0.108,0.012,0.21,0.015,0.303
	c0,0.018,0,0.033,0.001,0.051c0.002,0.083,0.002,0.162,0.001,0.231c0,0.01,0,0.02,0,0.03c-0.004,0.235-0.02,0.375-0.02,0.378
	L33,18.728l0.33,0.298C33.762,19.416,34,19.939,34,20.5v4c0,0.873-0.572,1.637-1.422,1.899l-0.498,0.153l-0.16,0.495
	c-0.669,2.081-1.622,4.003-2.834,5.713c-0.297,0.421-0.586,0.794-0.837,1.079L28,34.123v4.125c0,0.253,0.025,0.501,0.064,0.745
	c0.008,0.052,0.022,0.102,0.032,0.154c0.039,0.201,0.091,0.398,0.155,0.59c0.015,0.045,0.031,0.088,0.048,0.133
	c0.078,0.209,0.169,0.411,0.275,0.605c0.012,0.022,0.023,0.045,0.035,0.067c0.145,0.256,0.312,0.499,0.504,0.723l0.228,0.281h0.039
	c0.343,0.338,0.737,0.632,1.185,0.856l9.553,4.776C42.513,48.374,44,50.78,44,53.457V55.5z M58,55.5H46v-2.043
	c0-3.439-1.911-6.53-4.986-8.068l-6.858-3.43c0.169-0.386,0.191-0.828,0.043-1.254c-0.245-0.705-0.885-1.16-1.63-1.16h-2.217
	c-0.046-0.081-0.076-0.17-0.113-0.256c-0.05-0.115-0.109-0.228-0.142-0.349C30.036,38.718,30,38.486,30,38.248v-3.381
	c0.229-0.28,0.47-0.599,0.719-0.951c1.239-1.75,2.232-3.698,2.954-5.799C35.084,27.47,36,26.075,36,24.5v-4
	c0-0.963-0.36-1.896-1-2.625v-5.319c0.026-0.25,0.082-1.069-0.084-2.139c1.288-0.506,2.731-0.767,4.29-0.78
	c9.841,0.081,10.2,9.811,10.21,10.221c0.147,7.583,4.746,14.927,6.717,17.732c0.169,0.24,0.22,0.542,0.139,0.827
	c-0.046,0.164-0.178,0.462-0.535,0.615c-1.68,0.723-3.959,0.518-4.076,0.513h-6.883c-0.643,0-1.229,0.327-1.568,0.874
	c-0.338,0.545-0.37,1.211-0.086,1.783c0.313,0.631,0.866,1.474,1.775,1.927l9.747,4.222C56.715,49.396,58,51.482,58,53.803V55.5z"/>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>'],
            'one_user' => [esc_html__('Send SMS To One User','nss'),esc_html__('Send SMS To the user you want.','nss'),'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"	 viewBox="0 0 489.7 489.7" style="enable-background:new 0 0 489.7 489.7;" xml:space="preserve">
<g>
	<g>
		<path id="XMLID_1801_" d="M456.8,185.75h-82l-44,42.2v-42.2h-18.7c-13.1,0-23.8-10.7-23.8-23.8v-88.3
			c0-13.1,10.7-23.8,23.8-23.8h144.7c13.1,0,23.8,10.7,23.8,23.8v88.3C480.6,175.05,469.9,185.75,456.8,185.75z"/>
		<path d="M237.4,161.45v-63.8c0-36.7-29.8-66.5-66.5-66.5h-13.7c-36.7,0-66.5,29.8-66.5,66.5v63.8
			c0,11,4.7,21.2,12.8,28.4v53.2c-13.5,6.8-52.7,27.7-89,57.6c-9.2,7.6-14.5,18.9-14.5,30.9v43.7c0,5,4.1,9.1,9.1,9.1
			s9.1-4.1,9.1-9.1v-43.7c0-6.6,2.9-12.7,7.9-16.9c38.8-31.9,80.9-53.1,89-57.1c4.1-2,6.7-6.1,6.7-10.7v-61.5c0-3-1.5-5.9-4-7.5
			c-5.5-3.7-8.8-9.8-8.8-16.4v-63.8c0-26.7,21.7-48.4,48.4-48.4h13.7c26.7,0,48.4,21.7,48.4,48.4v63.8c0,6.6-3.3,12.7-8.8,16.4
			c-2.5,1.7-4,4.5-4,7.5v61.5c0,4.6,2.6,8.7,6.7,10.7c8.1,3.9,50.2,25.2,89,57.1c5,4.1,7.9,10.3,7.9,16.9v43.7c0,5,4.1,9.1,9.1,9.1
			s9.1-4.1,9.1-9.1v-43.7c0-12-5.3-23.3-14.5-30.9c-36.4-29.9-75.5-50.8-89-57.6v-53.2C232.7,182.65,237.4,172.35,237.4,161.45z"/>
		<path  d="M185.2,255.45c-5,0-9.1,4.1-9.1,9.1c0,3.5-1.5,6.6-3.9,8.8c-0.5,0.3-1,0.7-1.5,1.2
			c-1.9,1.3-4.2,2.1-6.7,2.1c-6.6,0-12.1-5.4-12.1-12.1c0-5-4.1-9.1-9.1-9.1s-9.1,4.1-9.1,9.1c0,7.1,2.5,13.6,6.6,18.8l-6.4,147.5
			c-0.1,2.7,1,5.3,3,7.1l20.3,18.3c1.7,1.6,3.9,2.3,6.1,2.3c2.2,0,4.4-0.8,6.1-2.3l20.6-18.6c2-1.8,3.1-4.4,3-7.1l-6.6-145.7
			c4.8-5.4,7.8-12.4,7.8-20.2C194.3,259.55,190.2,255.45,185.2,255.45z M163.4,437.25l-11-9.9l5.7-133.2c1.9,0.4,3.9,0.6,5.9,0.6
			c1.6,0,3.1-0.2,4.6-0.4l6,132.7L163.4,437.25z"/>
		<path  d="M435.1,91.35H333.7c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h101.4c5,0,9.1-4.1,9.1-9.1
			S440.1,91.35,435.1,91.35z"/>
		<path  d="M435.1,127.55H333.7c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h101.4c5,0,9.1-4.1,9.1-9.1
			S440.1,127.55,435.1,127.55z"/>
		<path  d="M456.8,40.85H312.1c-18.1,0-32.9,14.7-32.9,32.9v88.3c0,18.1,14.7,32.9,32.9,32.9h9.6v33.1
			c0,3.6,2.2,6.9,5.5,8.3c1.1,0.5,2.4,0.7,3.6,0.7c2.3,0,4.6-0.9,6.3-2.5l41.4-39.6h78.3c18.1,0,32.9-14.7,32.9-32.9v-88.4
			C489.6,55.55,474.9,40.85,456.8,40.85z M471.5,161.95c0,8.1-6.6,14.7-14.7,14.7h-82c-2.3,0-4.6,0.9-6.3,2.5l-28.7,27.5v-20.9
			c0-5-4.1-9.1-9.1-9.1H312c-8.1,0-14.7-6.6-14.7-14.7v-88.3c0-8.1,6.6-14.7,14.7-14.7h144.8c8.1,0,14.7,6.6,14.7,14.7V161.95z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
'],
            'product_customers' => [esc_html__('Send SMS To Product Customers','nss'),esc_html__('Send SMS to users who have bought a product','nss'),'
<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 496 496" style="enable-background:new 0 0 496 496;" xml:space="preserve">
<g>
	<g>
		<g>
			<path d="M128,193.472V128h48V0H64v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C92.528,196.328,82.776,192,72,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272H72H40c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C144,205.584,137.288,196.784,128,193.472z M80,112V16h80v96H80z M72,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S85.232,256,72,256z M32,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-40h16V376z M32,320H16v-8
				c0-13.232,10.768-24,24-24h24v64H48v-40H32V320z M64,480H48v-16h16V480z M96,480H80v-16h16V480z M96,448H80v-64H64v64H48v-80h48
				V448z M128,288c0,13.232-10.768,24-24,24h-8v40H80v-64h32v-8h16V288z M128,264h-16v-48c0-4.416,3.584-8,8-8s8,3.584,8,8V264z"/>
			<polygon points="128,32 96,32 96,48 112,48 112,80 96,80 96,96 144,96 144,80 128,80 			"/>
			<path d="M288,193.472V128h48V0H224v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C252.528,196.328,242.776,192,232,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C304,205.584,297.288,196.784,288,193.472z M240,112V16h80v96H240z M232,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S245.232,256,232,256z M240,288l-8,10.664L224,288H240z M192,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-8h16V376z
				 M224,480h-16v-16h16V480z M256,480h-16v-16h16V480z M256,448h-16v-64h-16v64h-16v-80h48V448z M288,288c0,13.232-10.768,24-24,24
				h-8v40h-48v-40h-16v40h-16v-40c0-13.232,10.768-24,24-24h4l28,37.336L260,288h12v-48h16V288z M288,224h-16v-8
				c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V224z"/>
			<path d="M496,128V0H384v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C412.528,196.328,402.776,192,392,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				c0-10.416-6.712-19.216-16-22.528V128H496z M392,256c-13.232,0-24-10.768-24-24s10.768-24,24-24s24,10.768,24,24
				S405.232,256,392,256z M352,376c0,4.416-3.584,8-8,8c-4.416,0-8-3.584-8-8v-32h16V376z M384,480h-16v-16h16V480z M416,480h-16
				v-16h16V480z M416,448h-16v-64h-16v64h-16v-80h48V448z M448,288c0,13.232-10.768,24-24,24h-8v40h-48v-40h-16v16h-16v-16
				c0-13.232,10.768-24,24-24h72v-16h16V288z M448,256h-16v-40c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V256z M400,112V16h80v96H400
				z"/>
			<path d="M444,32h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c11.032,0,20-8.968,20-20
				c0-4.504-1.496-8.656-4.008-12c2.512-3.344,4.008-7.496,4.008-12C464,40.968,455.032,32,444,32z"/>
			<path d="M304,54.832C304,42.24,293.76,32,281.168,32h-2.328C266.24,32,256,42.24,256,54.832V56h16v-1.168
				c0-3.76,3.072-6.832,6.832-6.832h2.328c3.768,0,6.84,3.072,6.84,6.832c0,2.6-1.448,4.944-3.784,6.112L256,75.056V96h48V80
				h-22.112l9.488-4.744C299.168,71.36,304,63.536,304,54.832z"/>
		</g>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
'],
        );
        ?>
              <div class="wrap">
        <hr class="wp-header-end">
                  <div class="nirshop_main">
                  <div class="nirshop_tabs_container">
            <?php
        foreach ($tabs as $tab => $tab_title) {
            $class = ($tab == $current_tab) ? 'nav-tab-active' : null;
            echo "<a class='nav-tab pi_nav_tb ".esc_html($class)."' href='" . esc_url_raw(add_query_arg(array('tab' => $tab , 'action' => ''))) . "'>".
                "<p class='nirweb_tab_title'>".esc_html($tab_title[0]).$tab_title[2]."</p>".
                "<p>".esc_html($tab_title[1])."</p>".
              "</a>";
        }
        ?>
                  </div>
                  <div class="nirshop_content_container">
        <?php
        switch ($current_tab):
            case 'all_users':
                ?>
                <div class="nirweb_smart_sms_admin_container">
                    <h2>
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                             viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
<path d="M55.517,46.55l-9.773-4.233c-0.23-0.115-0.485-0.396-0.704-0.771l6.525-0.005c0.114,0.011,2.804,0.257,4.961-0.67
	c0.817-0.352,1.425-1.047,1.669-1.907c0.246-0.868,0.09-1.787-0.426-2.523c-1.865-2.654-6.218-9.589-6.354-16.623
	c-0.003-0.121-0.397-12.083-12.21-12.18c-1.739,0.014-3.347,0.309-4.81,0.853c-0.319-0.813-0.789-1.661-1.488-2.459
	C30.854,3.688,27.521,2.5,23,2.5s-7.854,1.188-9.908,3.53c-2.368,2.701-2.148,5.976-2.092,6.525v5.319c-0.64,0.729-1,1.662-1,2.625
	v4c0,1.217,0.553,2.352,1.497,3.109c0.916,3.627,2.833,6.36,3.503,7.237v3.309c0,0.968-0.528,1.856-1.377,2.32l-8.921,4.866
	C1.801,46.924,0,49.958,0,53.262V57.5h44h2h14v-3.697C60,50.711,58.282,47.933,55.517,46.55z M44,55.5H2v-2.238
	c0-2.571,1.402-4.934,3.659-6.164l8.921-4.866C16.073,41.417,17,39.854,17,38.155v-4.019l-0.233-0.278
	c-0.024-0.029-2.475-2.994-3.41-7.065l-0.091-0.396l-0.341-0.22C12.346,25.803,12,25.176,12,24.5v-4c0-0.561,0.238-1.084,0.67-1.475
	L13,18.728V12.5l-0.009-0.131c-0.003-0.027-0.343-2.799,1.605-5.021C16.253,5.458,19.081,4.5,23,4.5
	c3.905,0,6.727,0.951,8.386,2.828c0.825,0.932,1.24,1.973,1.447,2.867c0.016,0.07,0.031,0.139,0.045,0.208
	c0.014,0.071,0.029,0.142,0.04,0.21c0.013,0.078,0.024,0.152,0.035,0.226c0.008,0.053,0.016,0.107,0.022,0.158
	c0.015,0.124,0.027,0.244,0.035,0.355c0.001,0.009,0.001,0.017,0.001,0.026c0.007,0.108,0.012,0.21,0.015,0.303
	c0,0.018,0,0.033,0.001,0.051c0.002,0.083,0.002,0.162,0.001,0.231c0,0.01,0,0.02,0,0.03c-0.004,0.235-0.02,0.375-0.02,0.378
	L33,18.728l0.33,0.298C33.762,19.416,34,19.939,34,20.5v4c0,0.873-0.572,1.637-1.422,1.899l-0.498,0.153l-0.16,0.495
	c-0.669,2.081-1.622,4.003-2.834,5.713c-0.297,0.421-0.586,0.794-0.837,1.079L28,34.123v4.125c0,0.253,0.025,0.501,0.064,0.745
	c0.008,0.052,0.022,0.102,0.032,0.154c0.039,0.201,0.091,0.398,0.155,0.59c0.015,0.045,0.031,0.088,0.048,0.133
	c0.078,0.209,0.169,0.411,0.275,0.605c0.012,0.022,0.023,0.045,0.035,0.067c0.145,0.256,0.312,0.499,0.504,0.723l0.228,0.281h0.039
	c0.343,0.338,0.737,0.632,1.185,0.856l9.553,4.776C42.513,48.374,44,50.78,44,53.457V55.5z M58,55.5H46v-2.043
	c0-3.439-1.911-6.53-4.986-8.068l-6.858-3.43c0.169-0.386,0.191-0.828,0.043-1.254c-0.245-0.705-0.885-1.16-1.63-1.16h-2.217
	c-0.046-0.081-0.076-0.17-0.113-0.256c-0.05-0.115-0.109-0.228-0.142-0.349C30.036,38.718,30,38.486,30,38.248v-3.381
	c0.229-0.28,0.47-0.599,0.719-0.951c1.239-1.75,2.232-3.698,2.954-5.799C35.084,27.47,36,26.075,36,24.5v-4
	c0-0.963-0.36-1.896-1-2.625v-5.319c0.026-0.25,0.082-1.069-0.084-2.139c1.288-0.506,2.731-0.767,4.29-0.78
	c9.841,0.081,10.2,9.811,10.21,10.221c0.147,7.583,4.746,14.927,6.717,17.732c0.169,0.24,0.22,0.542,0.139,0.827
	c-0.046,0.164-0.178,0.462-0.535,0.615c-1.68,0.723-3.959,0.518-4.076,0.513h-6.883c-0.643,0-1.229,0.327-1.568,0.874
	c-0.338,0.545-0.37,1.211-0.086,1.783c0.313,0.631,0.866,1.474,1.775,1.927l9.747,4.222C56.715,49.396,58,51.482,58,53.803V55.5z"/>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
</svg>
                        <?= esc_html__('Send SMS To ALL Users','nss') ?>
                    </h2>
                    <div class="nirweb_massage">
                        <h5><?= esc_html__( 'professional version and tutorial','nss') ?></h5>
                        <p><?= esc_html__('to use options below , you can easily purchase and download the pro version of the plugin form the link below','nss') ?></p>
                        <a href="<?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?>"><?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?></a>
                        <svg width="200px" height="200px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#caf3e5" stroke-width="2" d="M10,13 L10,16 L13,16 L13,19 L16,19 L16,21 L18,23 L23,23 L23,19 L12.74,8.74 C12.91,8.19 13,7.6 13,7 C13,3.69 10.31,1 7,1 C3.69,1 1,3.69 1,7 C1,10.31 3.69,13 7,13 C7.88,13 8.72,12.81 9.47,12.47 L10,13 Z M6,7 C5.4475,7 5,6.5525 5,6 C5,5.4475 5.4475,5 6,5 C6.5525,5 7,5.4475 7,6 C7,6.5525 6.5525,7 6,7 Z"/>
                        </svg>
                    </div>
                    <form action="" method="post">
                        <label for="">
                            <?= esc_html__('pattern code','nss') ?>
                            <input type="text" name="all_users_pattern">
                        </label>
                        <p>
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6.5C12.4142 6.5 12.75 6.83579 12.75 7.25V13.5C12.75 13.9142 12.4142 14.25 12 14.25C11.5858 14.25 11.25 13.9142 11.25 13.5V7.25C11.25 6.83579 11.5858 6.5 12 6.5Z" fill="#212121"/>
                                <path d="M12 17.4978C12.5523 17.4978 13 17.0501 13 16.4978C13 15.9455 12.5523 15.4978 12 15.4978C11.4477 15.4978 11 15.9455 11 16.4978C11 17.0501 11.4477 17.4978 12 17.4978Z" fill="#212121"/>
                                <path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C10.3817 22 8.81782 21.6146 7.41286 20.888L3.58704 21.9553C2.92212 22.141 2.23258 21.7525 2.04691 21.0876C1.98546 20.8676 1.98549 20.6349 2.04695 20.4151L3.11461 16.5922C2.38637 15.186 2 13.6203 2 12C2 6.47715 6.47715 2 12 2ZM12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 13.4696 3.87277 14.8834 4.57303 16.1375L4.72368 16.4072L3.61096 20.3914L7.59755 19.2792L7.86709 19.4295C9.12006 20.1281 10.5322 20.5 12 20.5C16.6944 20.5 20.5 16.6944 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5Z" fill="#212121"/>
                            </svg>
                            <?= esc_html__('use variable display_name for user\'s display name in your pattern.if you are using melipayamak use 0.','nss') ?>
                        </p>
                        <?php wp_nonce_field( 'all_users_sms', 'all_users_sms_action' ); ?>
                        <button  class="nirweb_submit disabled"><?= esc_html__('Send','nss') ?></button>
                    </form>
                </div>
                <?php
                break;

            case 'one_user':
                ?>
                <div class="nirweb_smart_sms_admin_container">
                    <h2>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"	 viewBox="0 0 489.7 489.7" style="enable-background:new 0 0 489.7 489.7;" xml:space="preserve">
<g>
    <g>
        <path id="XMLID_1801_" d="M456.8,185.75h-82l-44,42.2v-42.2h-18.7c-13.1,0-23.8-10.7-23.8-23.8v-88.3
			c0-13.1,10.7-23.8,23.8-23.8h144.7c13.1,0,23.8,10.7,23.8,23.8v88.3C480.6,175.05,469.9,185.75,456.8,185.75z"/>
        <path d="M237.4,161.45v-63.8c0-36.7-29.8-66.5-66.5-66.5h-13.7c-36.7,0-66.5,29.8-66.5,66.5v63.8
			c0,11,4.7,21.2,12.8,28.4v53.2c-13.5,6.8-52.7,27.7-89,57.6c-9.2,7.6-14.5,18.9-14.5,30.9v43.7c0,5,4.1,9.1,9.1,9.1
			s9.1-4.1,9.1-9.1v-43.7c0-6.6,2.9-12.7,7.9-16.9c38.8-31.9,80.9-53.1,89-57.1c4.1-2,6.7-6.1,6.7-10.7v-61.5c0-3-1.5-5.9-4-7.5
			c-5.5-3.7-8.8-9.8-8.8-16.4v-63.8c0-26.7,21.7-48.4,48.4-48.4h13.7c26.7,0,48.4,21.7,48.4,48.4v63.8c0,6.6-3.3,12.7-8.8,16.4
			c-2.5,1.7-4,4.5-4,7.5v61.5c0,4.6,2.6,8.7,6.7,10.7c8.1,3.9,50.2,25.2,89,57.1c5,4.1,7.9,10.3,7.9,16.9v43.7c0,5,4.1,9.1,9.1,9.1
			s9.1-4.1,9.1-9.1v-43.7c0-12-5.3-23.3-14.5-30.9c-36.4-29.9-75.5-50.8-89-57.6v-53.2C232.7,182.65,237.4,172.35,237.4,161.45z"/>
        <path  d="M185.2,255.45c-5,0-9.1,4.1-9.1,9.1c0,3.5-1.5,6.6-3.9,8.8c-0.5,0.3-1,0.7-1.5,1.2
			c-1.9,1.3-4.2,2.1-6.7,2.1c-6.6,0-12.1-5.4-12.1-12.1c0-5-4.1-9.1-9.1-9.1s-9.1,4.1-9.1,9.1c0,7.1,2.5,13.6,6.6,18.8l-6.4,147.5
			c-0.1,2.7,1,5.3,3,7.1l20.3,18.3c1.7,1.6,3.9,2.3,6.1,2.3c2.2,0,4.4-0.8,6.1-2.3l20.6-18.6c2-1.8,3.1-4.4,3-7.1l-6.6-145.7
			c4.8-5.4,7.8-12.4,7.8-20.2C194.3,259.55,190.2,255.45,185.2,255.45z M163.4,437.25l-11-9.9l5.7-133.2c1.9,0.4,3.9,0.6,5.9,0.6
			c1.6,0,3.1-0.2,4.6-0.4l6,132.7L163.4,437.25z"/>
        <path  d="M435.1,91.35H333.7c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h101.4c5,0,9.1-4.1,9.1-9.1
			S440.1,91.35,435.1,91.35z"/>
        <path  d="M435.1,127.55H333.7c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h101.4c5,0,9.1-4.1,9.1-9.1
			S440.1,127.55,435.1,127.55z"/>
        <path  d="M456.8,40.85H312.1c-18.1,0-32.9,14.7-32.9,32.9v88.3c0,18.1,14.7,32.9,32.9,32.9h9.6v33.1
			c0,3.6,2.2,6.9,5.5,8.3c1.1,0.5,2.4,0.7,3.6,0.7c2.3,0,4.6-0.9,6.3-2.5l41.4-39.6h78.3c18.1,0,32.9-14.7,32.9-32.9v-88.4
			C489.6,55.55,474.9,40.85,456.8,40.85z M471.5,161.95c0,8.1-6.6,14.7-14.7,14.7h-82c-2.3,0-4.6,0.9-6.3,2.5l-28.7,27.5v-20.9
			c0-5-4.1-9.1-9.1-9.1H312c-8.1,0-14.7-6.6-14.7-14.7v-88.3c0-8.1,6.6-14.7,14.7-14.7h144.8c8.1,0,14.7,6.6,14.7,14.7V161.95z"/>
    </g>
</g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
</svg>
                        <?= esc_html__('Send SMS To One User','nss') ?>
                    </h2>
                    <div class="nirweb_massage">
                        <h5><?= esc_html__( 'professional version and tutorial','nss') ?></h5>
                        <p><?= esc_html__('to use options below , you can easily purchase and download the pro version of the plugin form the link below','nss') ?></p>
                        <a href="<?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?>"><?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?></a>
                        <svg width="200px" height="200px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#caf3e5" stroke-width="2" d="M10,13 L10,16 L13,16 L13,19 L16,19 L16,21 L18,23 L23,23 L23,19 L12.74,8.74 C12.91,8.19 13,7.6 13,7 C13,3.69 10.31,1 7,1 C3.69,1 1,3.69 1,7 C1,10.31 3.69,13 7,13 C7.88,13 8.72,12.81 9.47,12.47 L10,13 Z M6,7 C5.4475,7 5,6.5525 5,6 C5,5.4475 5.4475,5 6,5 C6.5525,5 7,5.4475 7,6 C7,6.5525 6.5525,7 6,7 Z"/>
                        </svg>
                    </div>
                      <form action="" method="post">
                        <label for="" name="user_id">
                            <?= esc_html__('select the user','nss') ?>
                            <select id="select_user" name="user_id">
                                <option value="-1"><?= esc_html__('select an option','nss') ?></option>
                                <?php foreach(get_users() as $user){ ?>
                                    <option value="<?= esc_html($user->ID) ?>"><?= esc_html($user->display_name) ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label for="">
                            <?= esc_html__('pattern code','nss') ?>
                            <input type="text" name="send_user_sms_pattern">
                        </label>
                          <p>
                              <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12 6.5C12.4142 6.5 12.75 6.83579 12.75 7.25V13.5C12.75 13.9142 12.4142 14.25 12 14.25C11.5858 14.25 11.25 13.9142 11.25 13.5V7.25C11.25 6.83579 11.5858 6.5 12 6.5Z" fill="#212121"/>
                                  <path d="M12 17.4978C12.5523 17.4978 13 17.0501 13 16.4978C13 15.9455 12.5523 15.4978 12 15.4978C11.4477 15.4978 11 15.9455 11 16.4978C11 17.0501 11.4477 17.4978 12 17.4978Z" fill="#212121"/>
                                  <path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C10.3817 22 8.81782 21.6146 7.41286 20.888L3.58704 21.9553C2.92212 22.141 2.23258 21.7525 2.04691 21.0876C1.98546 20.8676 1.98549 20.6349 2.04695 20.4151L3.11461 16.5922C2.38637 15.186 2 13.6203 2 12C2 6.47715 6.47715 2 12 2ZM12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 13.4696 3.87277 14.8834 4.57303 16.1375L4.72368 16.4072L3.61096 20.3914L7.59755 19.2792L7.86709 19.4295C9.12006 20.1281 10.5322 20.5 12 20.5C16.6944 20.5 20.5 16.6944 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5Z" fill="#212121"/>
                              </svg>
                              <?= esc_html__('use variable display_name for user\'s display name in your pattern.if you are using melipayamak use 0.','nss') ?>
                          </p>
                        <?php wp_nonce_field( 'one_user_sms', 'one_user_sms_action' ); ?>
                        <button name="send_to_a_user" class="nirweb_submit disabled"><?= esc_html__('Send','nss') ?></button>
                    </form>
                </div>
                <?php
                break;
            case 'product_customers':
                if(is_plugin_active('woocommerce/woocommerce.php') ) {
                ?>
                <div class="nirweb_smart_sms_admin_container">
                    <h2>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 496 496" style="enable-background:new 0 0 496 496;" xml:space="preserve">
<g>
    <g>
        <g>
            <path d="M128,193.472V128h48V0H64v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C92.528,196.328,82.776,192,72,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272H72H40c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C144,205.584,137.288,196.784,128,193.472z M80,112V16h80v96H80z M72,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S85.232,256,72,256z M32,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-40h16V376z M32,320H16v-8
				c0-13.232,10.768-24,24-24h24v64H48v-40H32V320z M64,480H48v-16h16V480z M96,480H80v-16h16V480z M96,448H80v-64H64v64H48v-80h48
				V448z M128,288c0,13.232-10.768,24-24,24h-8v40H80v-64h32v-8h16V288z M128,264h-16v-48c0-4.416,3.584-8,8-8s8,3.584,8,8V264z"/>
            <polygon points="128,32 96,32 96,48 112,48 112,80 96,80 96,96 144,96 144,80 128,80 			"/>
            <path d="M288,193.472V128h48V0H224v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C252.528,196.328,242.776,192,232,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C304,205.584,297.288,196.784,288,193.472z M240,112V16h80v96H240z M232,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S245.232,256,232,256z M240,288l-8,10.664L224,288H240z M192,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-8h16V376z
				 M224,480h-16v-16h16V480z M256,480h-16v-16h16V480z M256,448h-16v-64h-16v64h-16v-80h48V448z M288,288c0,13.232-10.768,24-24,24
				h-8v40h-48v-40h-16v40h-16v-40c0-13.232,10.768-24,24-24h4l28,37.336L260,288h12v-48h16V288z M288,224h-16v-8
				c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V224z"/>
            <path d="M496,128V0H384v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C412.528,196.328,402.776,192,392,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				c0-10.416-6.712-19.216-16-22.528V128H496z M392,256c-13.232,0-24-10.768-24-24s10.768-24,24-24s24,10.768,24,24
				S405.232,256,392,256z M352,376c0,4.416-3.584,8-8,8c-4.416,0-8-3.584-8-8v-32h16V376z M384,480h-16v-16h16V480z M416,480h-16
				v-16h16V480z M416,448h-16v-64h-16v64h-16v-80h48V448z M448,288c0,13.232-10.768,24-24,24h-8v40h-48v-40h-16v16h-16v-16
				c0-13.232,10.768-24,24-24h72v-16h16V288z M448,256h-16v-40c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V256z M400,112V16h80v96H400
				z"/>
            <path d="M444,32h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c11.032,0,20-8.968,20-20
				c0-4.504-1.496-8.656-4.008-12c2.512-3.344,4.008-7.496,4.008-12C464,40.968,455.032,32,444,32z"/>
            <path d="M304,54.832C304,42.24,293.76,32,281.168,32h-2.328C266.24,32,256,42.24,256,54.832V56h16v-1.168
				c0-3.76,3.072-6.832,6.832-6.832h2.328c3.768,0,6.84,3.072,6.84,6.832c0,2.6-1.448,4.944-3.784,6.112L256,75.056V96h48V80
				h-22.112l9.488-4.744C299.168,71.36,304,63.536,304,54.832z"/>
        </g>
    </g>
</g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
                            <g>
                            </g>
</svg>
                        <?= esc_html__('Send SMS To Product Customers','nss') ?>
                    </h2>
                    <div class="nirweb_massage">
                        <h5><?= esc_html__( 'professional version and tutorial','nss') ?></h5>
                        <p><?= esc_html__('to use options below , you can easily purchase and download the pro version of the plugin form the link below','nss') ?></p>
                        <a href="<?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?>"><?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?></a>
                        <svg width="200px" height="200px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#caf3e5" stroke-width="2" d="M10,13 L10,16 L13,16 L13,19 L16,19 L16,21 L18,23 L23,23 L23,19 L12.74,8.74 C12.91,8.19 13,7.6 13,7 C13,3.69 10.31,1 7,1 C3.69,1 1,3.69 1,7 C1,10.31 3.69,13 7,13 C7.88,13 8.72,12.81 9.47,12.47 L10,13 Z M6,7 C5.4475,7 5,6.5525 5,6 C5,5.4475 5.4475,5 6,5 C6.5525,5 7,5.4475 7,6 C7,6.5525 6.5525,7 6,7 Z"/>
                        </svg>
                    </div>
                     <form action="" method="post">
                        <label for="" name="product_id">
                            <?= esc_html__('select the product','nss') ?>
                            <select  id="select_product" name="product_id">
                                <option value="-1"><?= esc_html__('select an option','nss') ?></option>
                                <?php    $args = array(
                                    'post_type' => 'product',
                                    'posts_per_page' => -1
                                );
                                $loop = new WP_Query( $args );
                                if ( $loop->have_posts() ): while ( $loop->have_posts() ): $loop->the_post();?>
                                    <option value="<?= esc_html(get_the_ID()) ?>"><?= esc_html(get_the_title()) ?></option>
                                <?php endwhile;wp_reset_query();endif;?>
                            </select>
                        </label>
                        <label for="">
                            <?= esc_html__('pattern code','nss') ?>
                            <input type="text" name="send_product_sms_pattern">
                        </label>
                         <p>
                             <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M12 6.5C12.4142 6.5 12.75 6.83579 12.75 7.25V13.5C12.75 13.9142 12.4142 14.25 12 14.25C11.5858 14.25 11.25 13.9142 11.25 13.5V7.25C11.25 6.83579 11.5858 6.5 12 6.5Z" fill="#212121"/>
                                 <path d="M12 17.4978C12.5523 17.4978 13 17.0501 13 16.4978C13 15.9455 12.5523 15.4978 12 15.4978C11.4477 15.4978 11 15.9455 11 16.4978C11 17.0501 11.4477 17.4978 12 17.4978Z" fill="#212121"/>
                                 <path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C10.3817 22 8.81782 21.6146 7.41286 20.888L3.58704 21.9553C2.92212 22.141 2.23258 21.7525 2.04691 21.0876C1.98546 20.8676 1.98549 20.6349 2.04695 20.4151L3.11461 16.5922C2.38637 15.186 2 13.6203 2 12C2 6.47715 6.47715 2 12 2ZM12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 13.4696 3.87277 14.8834 4.57303 16.1375L4.72368 16.4072L3.61096 20.3914L7.59755 19.2792L7.86709 19.4295C9.12006 20.1281 10.5322 20.5 12 20.5C16.6944 20.5 20.5 16.6944 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5Z" fill="#212121"/>
                             </svg>
                             <?= esc_html__('use product_title for product\'s title and display_name for users\'s displayname in your pattern.if you are using melipayamak use 0 for displayname and 1 for product title.','nss') ?></p>
                         <?php wp_nonce_field( 'product_customers_sms', 'product_customers_sms_action' ); ?>
                        <button name="send_to_product_customers"  class="nirweb_submit disabled" ><?= esc_html__('Send','nss') ?></button>
                    </form>
                </div>
                <?php
                }
                break;

        endswitch;
        ?>
                  </div>
              </div>
              </div>
    <?php });
});
/***************************************************************************
#   Admin Export
 ***************************************************************************/
add_action('admin_menu',function() {
    add_submenu_page( 'send_sms_nirweb_smart_sms',esc_html__('Subscribers','nss'),'<div class="nirweb_codestar_icon bg_solid">' .esc_html__('Subscribers','nss').'<span><i class="fas fa-lock"></i>PRO</span></span></div>', 'manage_options', 'export_billing_phone', function() { ?>
        <?php
        $current_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'export_all';
        $tabs = array(
            'export_all' =>  [esc_html__('all customers','nss'),__('export all woocommerce billing phones','nss'),'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
	 viewBox="0 0 496 496" >
<g>
	<g>
		<path d="M413.76,319.488l-62.56-13.904v-19.768c23.344-16.848,38.8-43.88,39.832-74.56c20.712-21.864,32.168-50.28,32.168-80.512
			V96c0-20.256-15.144-37.056-34.704-39.648C371.712,21.96,336.832,0,298.272,0h-13.464c-26.016,0-51.664,8.8-72.208,24.776
			c-28.856,22.44-45.4,56.28-45.4,92.832V128h-52L93.6,99.2l-12.8,9.6L95.2,128h-20L53.6,99.2l-12.8,9.6L55.2,128h-20L13.6,99.2
			l-12.8,9.6L21.2,136L0.8,163.2l12.8,9.6L35.2,144h20l-14.4,19.2l12.8,9.6L75.2,144h20l-14.4,19.2l12.8,9.592l21.6-28.8h52.808
			c2.824,25.232,13.712,48.624,31.36,67.256c1.04,30.68,16.488,57.712,39.832,74.56v19.768l-62.56,13.896
			C128.688,330.144,95.2,371.88,95.2,421.008V496h167.304h65.392H495.2v-74.992C495.2,371.88,461.712,330.144,413.76,319.488z
			 M358.216,323.528l-12.208,54.928l-37.728-26.944l37.024-30.856L358.216,323.528z M295.2,341.584l-40-33.336V295.16
			c12.192,5.616,25.712,8.84,40,8.84c14.288,0,27.808-3.224,40-8.84v13.088L295.2,341.584z M199.2,185.304
			C191.192,172.84,186.088,158.8,184.16,144h15.04V185.304z M183.2,128v-10.392c0-31.584,14.296-60.824,39.224-80.208
			C240.176,23.6,262.336,16,284.808,16h13.464c33.808,0,64.256,20.08,77.576,51.152l2.08,4.848h5.272c13.232,0,24,10.768,24,24
			v34.744c0,19.648-5.624,38.416-16,54.56V136c0-52.936-43.064-96-96-96c-50.24,0-91.504,38.8-95.592,88H183.2z M281.424,128
			c2.776-4.76,7.88-8,13.776-8c8.824,0,16,7.176,16,16c0,8.824-7.176,16-16,16c-5.896,0-11-3.24-13.776-8H295.2v-16H281.424z
			 M295.2,168c17.648,0,32-14.352,32-32s-14.352-32-32-32c-14.872,0-27.288,10.232-30.864,24H247.92
			c3.824-22.664,23.536-40,47.28-40c26.472,0,48,21.528,48,48s-21.528,48-48,48c-23.736,0-43.448-17.336-47.28-40h16.416
			C267.912,157.768,280.328,168,295.2,168z M295.2,200c35.288,0,64-28.712,64-64c0-35.288-28.712-64-64-64
			c-32.576,0-59.488,24.48-63.448,56H215.6c4.032-40.368,38.184-72,79.592-72c44.112,0,80,35.888,80,80s-35.888,80-80,80
			c-41.408,0-75.568-31.632-79.592-72h16.152C235.712,175.52,262.624,200,295.2,200z M215.2,208v-19.048
			c17.208,25.912,46.624,43.048,80,43.048c33.376,0,62.792-17.136,80-43.048V208c0,44.112-35.888,80-80,80S215.2,252.112,215.2,208z
			 M245.088,320.664l37.024,30.856l-37.728,26.944l-12.208-54.928L245.088,320.664z M263.84,480H191.2v-32h-16v32h-64v-58.992
			c0-41.56,28.336-76.888,68.904-85.896l36.456-8.104l17.456,78.536l27.656-19.76l9.248,9.248L263.84,480z M310.504,480h-30.608
			l7.584-91.032l-12.616-12.616l20.336-14.52l20.336,14.528l-12.616,12.616L310.504,480z M479.2,480h-64v-32h-16v32h-72.64
			l-7.08-84.968l9.248-9.248l27.656,19.76l17.456-78.536l36.456,8.104c40.568,9.008,68.904,44.336,68.904,85.896V480z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>'],
            'export_product_phones' => [esc_html__('product customers','nss'),esc_html__('export product customer phonenumbers','nss'),'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"  x="0px" y="0px"	 viewBox="0 0 496 496"  xml:space="preserve">
<g>
	<g>
		<g>
			<path d="M128,193.472V128h48V0H64v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C92.528,196.328,82.776,192,72,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272H72H40c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C144,205.584,137.288,196.784,128,193.472z M80,112V16h80v96H80z M72,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S85.232,256,72,256z M32,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-40h16V376z M32,320H16v-8
				c0-13.232,10.768-24,24-24h24v64H48v-40H32V320z M64,480H48v-16h16V480z M96,480H80v-16h16V480z M96,448H80v-64H64v64H48v-80h48
				V448z M128,288c0,13.232-10.768,24-24,24h-8v40H80v-64h32v-8h16V288z M128,264h-16v-48c0-4.416,3.584-8,8-8s8,3.584,8,8V264z"/>
			<polygon points="128,32 96,32 96,48 112,48 112,80 96,80 96,96 144,96 144,80 128,80 			"/>
			<path d="M288,193.472V128h48V0H224v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C252.528,196.328,242.776,192,232,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C304,205.584,297.288,196.784,288,193.472z M240,112V16h80v96H240z M232,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S245.232,256,232,256z M240,288l-8,10.664L224,288H240z M192,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-8h16V376z
				 M224,480h-16v-16h16V480z M256,480h-16v-16h16V480z M256,448h-16v-64h-16v64h-16v-80h48V448z M288,288c0,13.232-10.768,24-24,24
				h-8v40h-48v-40h-16v40h-16v-40c0-13.232,10.768-24,24-24h4l28,37.336L260,288h12v-48h16V288z M288,224h-16v-8
				c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V224z"/>
			<path d="M496,128V0H384v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C412.528,196.328,402.776,192,392,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				c0-10.416-6.712-19.216-16-22.528V128H496z M392,256c-13.232,0-24-10.768-24-24s10.768-24,24-24s24,10.768,24,24
				S405.232,256,392,256z M352,376c0,4.416-3.584,8-8,8c-4.416,0-8-3.584-8-8v-32h16V376z M384,480h-16v-16h16V480z M416,480h-16
				v-16h16V480z M416,448h-16v-64h-16v64h-16v-80h48V448z M448,288c0,13.232-10.768,24-24,24h-8v40h-48v-40h-16v16h-16v-16
				c0-13.232,10.768-24,24-24h72v-16h16V288z M448,256h-16v-40c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V256z M400,112V16h80v96H400
				z"/>
			<path d="M444,32h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c11.032,0,20-8.968,20-20
				c0-4.504-1.496-8.656-4.008-12c2.512-3.344,4.008-7.496,4.008-12C464,40.968,455.032,32,444,32z"/>
			<path d="M304,54.832C304,42.24,293.76,32,281.168,32h-2.328C266.24,32,256,42.24,256,54.832V56h16v-1.168
				c0-3.76,3.072-6.832,6.832-6.832h2.328c3.768,0,6.84,3.072,6.84,6.832c0,2.6-1.448,4.944-3.784,6.112L256,75.056V96h48V80
				h-22.112l9.488-4.744C299.168,71.36,304,63.536,304,54.832z"/>
		</g>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>'],
            'export_newsletter_phones' =>[esc_html__('newsletter','nss'),esc_html__('export newsletter phonenumbers','nss'),'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 166.781 166.781" style="enable-background:new 0 0 166.781 166.781;" xml:space="preserve">
<g>
	<g>
		<g>
			<path d="M163.451,70.046l-32.35-20.847c-0.253-0.161-0.532-0.222-0.804-0.312v-7.19c0-1.92-1.554-3.475-3.475-3.475H113.92
				L86.97,21.378c-1.126-0.706-2.558-0.706-3.685,0l-26.95,16.844H39.958c-1.92,0-3.475,1.554-3.475,3.475v7.188
				c-0.272,0.09-0.552,0.152-0.804,0.314L3.329,70.046c-0.991,0.641-1.592,1.741-1.592,2.921v90.339c0,1.92,1.554,3.475,3.475,3.475
				h156.356c1.92,0,3.475-1.554,3.475-3.475V72.968C165.043,71.787,164.442,70.688,163.451,70.046z M85.128,28.423l15.681,9.799
				H69.447L85.128,28.423z M43.433,45.171h79.915v78.178c0,0.01,0.006,0.018,0.006,0.029l-11.754,7.137l-28.284-15.427
				c-1.055-0.57-2.338-0.567-3.386,0.034l-25.81,14.749l-10.692-6.492c0-0.01,0.006-0.018,0.006-0.028L43.433,45.171z M8.687,74.861
				l27.796-17.91v62.212L8.687,102.285V74.861z M8.687,110.412l38.537,23.397L8.687,155.831V110.412z M15.689,159.833l66.005-37.715
				l69.145,37.715H15.689z M158.094,155.874L118.65,134.36l39.444-23.949V155.874z M158.094,102.285l-27.797,16.877V56.951
				l27.797,17.911V102.285z"/>
			<path d="M57.331,79.917h41.695c1.92,0,3.475-1.554,3.475-3.475V55.595c0-1.92-1.554-3.475-3.475-3.475H57.331
				c-1.92,0-3.475,1.554-3.475,3.475v20.847C53.856,78.363,55.411,79.917,57.331,79.917z M60.805,59.069h34.746v13.898H60.805
				V59.069z"/>
			<rect x="53.856" y="86.866" width="55.593" height="6.949"/>
			<rect x="53.856" y="100.765" width="55.593" height="6.949"/>
			<path d="M147.67,41.697c0.889,0,1.778-0.339,2.457-1.018l12.283-12.283c1.357-1.357,1.357-3.556,0-4.913
				c-1.357-1.358-3.556-1.357-4.913,0l-12.283,12.283c-1.357,1.357-1.357,3.556,0,4.913
				C145.892,41.358,146.781,41.697,147.67,41.697z"/>
			<path d="M16.654,40.679c0.679,0.679,1.568,1.018,2.457,1.018c0.889,0,1.778-0.339,2.457-1.018c1.357-1.357,1.357-3.556,0-4.913
				L9.284,23.483c-1.357-1.357-3.556-1.357-4.913,0c-1.357,1.357-1.357,3.556,0,4.913L16.654,40.679z"/>
			<path d="M118.584,24.076c0.421,0.17,0.859,0.247,1.289,0.247c1.378,0,2.684-0.825,3.227-2.185l6.949-17.373
				c0.713-1.781-0.156-3.804-1.937-4.516c-1.764-0.709-3.804,0.149-4.516,1.937l-6.949,17.373
				C115.934,21.341,116.802,23.364,118.584,24.076z"/>
			<path d="M47.155,22.139c0.543,1.361,1.849,2.185,3.227,2.185c0.431,0,0.869-0.078,1.289-0.248
				c1.781-0.713,2.65-2.735,1.937-4.516L46.659,2.187c-0.713-1.788-2.748-2.647-4.516-1.937c-1.781,0.713-2.65,2.735-1.937,4.516
				L47.155,22.139z"/>
		</g>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>'],
        );
        ?>
        <div class="wrap">
        <hr class="wp-header-end">
        <div class="nirshop_main">
        <div class="nirshop_tabs_container">
        <?php
        foreach ($tabs as $tab => $tab_title) {
            $class = ($tab == $current_tab) ? 'nav-tab-active' : null;
            echo "<a class='nav-tab pi_nav_tb ".esc_html($class)."' href='" . esc_url_raw(add_query_arg(array('tab' => $tab , 'action' => ''))) . "'>".
                "<p class='nirweb_tab_title'>".esc_html($tab_title[0]).$tab_title[2]."</p>".
                "<p>".esc_html($tab_title[1])."</p>".
                "</a>";
        }
        ?>
            </div>
            <div class="nirshop_content_container">
                <div class="nirweb_smart_sms_admin_container">
                <?php
                switch ($current_tab):
                    case 'export_all':
                       ?>
                        <h2>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                 viewBox="0 0 496 496" style="enable-background:new 0 0 496 496;" xml:space="preserve">
<g>
    <g>
        <path d="M413.76,319.488l-62.56-13.904v-19.768c23.344-16.848,38.8-43.88,39.832-74.56c20.712-21.864,32.168-50.28,32.168-80.512
			V96c0-20.256-15.144-37.056-34.704-39.648C371.712,21.96,336.832,0,298.272,0h-13.464c-26.016,0-51.664,8.8-72.208,24.776
			c-28.856,22.44-45.4,56.28-45.4,92.832V128h-52L93.6,99.2l-12.8,9.6L95.2,128h-20L53.6,99.2l-12.8,9.6L55.2,128h-20L13.6,99.2
			l-12.8,9.6L21.2,136L0.8,163.2l12.8,9.6L35.2,144h20l-14.4,19.2l12.8,9.6L75.2,144h20l-14.4,19.2l12.8,9.592l21.6-28.8h52.808
			c2.824,25.232,13.712,48.624,31.36,67.256c1.04,30.68,16.488,57.712,39.832,74.56v19.768l-62.56,13.896
			C128.688,330.144,95.2,371.88,95.2,421.008V496h167.304h65.392H495.2v-74.992C495.2,371.88,461.712,330.144,413.76,319.488z
			 M358.216,323.528l-12.208,54.928l-37.728-26.944l37.024-30.856L358.216,323.528z M295.2,341.584l-40-33.336V295.16
			c12.192,5.616,25.712,8.84,40,8.84c14.288,0,27.808-3.224,40-8.84v13.088L295.2,341.584z M199.2,185.304
			C191.192,172.84,186.088,158.8,184.16,144h15.04V185.304z M183.2,128v-10.392c0-31.584,14.296-60.824,39.224-80.208
			C240.176,23.6,262.336,16,284.808,16h13.464c33.808,0,64.256,20.08,77.576,51.152l2.08,4.848h5.272c13.232,0,24,10.768,24,24
			v34.744c0,19.648-5.624,38.416-16,54.56V136c0-52.936-43.064-96-96-96c-50.24,0-91.504,38.8-95.592,88H183.2z M281.424,128
			c2.776-4.76,7.88-8,13.776-8c8.824,0,16,7.176,16,16c0,8.824-7.176,16-16,16c-5.896,0-11-3.24-13.776-8H295.2v-16H281.424z
			 M295.2,168c17.648,0,32-14.352,32-32s-14.352-32-32-32c-14.872,0-27.288,10.232-30.864,24H247.92
			c3.824-22.664,23.536-40,47.28-40c26.472,0,48,21.528,48,48s-21.528,48-48,48c-23.736,0-43.448-17.336-47.28-40h16.416
			C267.912,157.768,280.328,168,295.2,168z M295.2,200c35.288,0,64-28.712,64-64c0-35.288-28.712-64-64-64
			c-32.576,0-59.488,24.48-63.448,56H215.6c4.032-40.368,38.184-72,79.592-72c44.112,0,80,35.888,80,80s-35.888,80-80,80
			c-41.408,0-75.568-31.632-79.592-72h16.152C235.712,175.52,262.624,200,295.2,200z M215.2,208v-19.048
			c17.208,25.912,46.624,43.048,80,43.048c33.376,0,62.792-17.136,80-43.048V208c0,44.112-35.888,80-80,80S215.2,252.112,215.2,208z
			 M245.088,320.664l37.024,30.856l-37.728,26.944l-12.208-54.928L245.088,320.664z M263.84,480H191.2v-32h-16v32h-64v-58.992
			c0-41.56,28.336-76.888,68.904-85.896l36.456-8.104l17.456,78.536l27.656-19.76l9.248,9.248L263.84,480z M310.504,480h-30.608
			l7.584-91.032l-12.616-12.616l20.336-14.52l20.336,14.528l-12.616,12.616L310.504,480z M479.2,480h-64v-32h-16v32h-72.64
			l-7.08-84.968l9.248-9.248l27.656,19.76l17.456-78.536l36.456,8.104c40.568,9.008,68.904,44.336,68.904,85.896V480z"/>
    </g>
</g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
</svg>
                            <?= esc_html__('all customers','nss') ?>
                        </h2>
                       <?php
                        break;
                    case 'export_product_phones':
                        ?>
                        <h2>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"  x="0px" y="0px"	 viewBox="0 0 496 496"  xml:space="preserve">
<g>
    <g>
        <g>
            <path d="M128,193.472V128h48V0H64v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C92.528,196.328,82.776,192,72,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272H72H40c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C144,205.584,137.288,196.784,128,193.472z M80,112V16h80v96H80z M72,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S85.232,256,72,256z M32,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-40h16V376z M32,320H16v-8
				c0-13.232,10.768-24,24-24h24v64H48v-40H32V320z M64,480H48v-16h16V480z M96,480H80v-16h16V480z M96,448H80v-64H64v64H48v-80h48
				V448z M128,288c0,13.232-10.768,24-24,24h-8v40H80v-64h32v-8h16V288z M128,264h-16v-48c0-4.416,3.584-8,8-8s8,3.584,8,8V264z"/>
            <polygon points="128,32 96,32 96,48 112,48 112,80 96,80 96,96 144,96 144,80 128,80 			"/>
            <path d="M288,193.472V128h48V0H224v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C252.528,196.328,242.776,192,232,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				C304,205.584,297.288,196.784,288,193.472z M240,112V16h80v96H240z M232,256c-13.232,0-24-10.768-24-24s10.768-24,24-24
				s24,10.768,24,24S245.232,256,232,256z M240,288l-8,10.664L224,288H240z M192,376c0,4.416-3.584,8-8,8s-8-3.584-8-8v-8h16V376z
				 M224,480h-16v-16h16V480z M256,480h-16v-16h16V480z M256,448h-16v-64h-16v64h-16v-80h48V448z M288,288c0,13.232-10.768,24-24,24
				h-8v40h-48v-40h-16v40h-16v-40c0-13.232,10.768-24,24-24h4l28,37.336L260,288h12v-48h16V288z M288,224h-16v-8
				c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V224z"/>
            <path d="M496,128V0H384v128h48v65.472c-5.112,1.824-9.424,5.28-12.272,9.808C412.528,196.328,402.776,192,392,192
				c-22.056,0-40,17.944-40,40c0,22.056,17.944,40,40,40c9.04,0,17.296-3.128,24-8.208V272h-24h-32c-22.056,0-40,17.944-40,40v64
				c0,13.232,10.768,24,24,24c2.808,0,5.504-0.488,8-1.368V496h80V327.192c18.232-3.72,32-19.872,32-39.192v-72
				c0-10.416-6.712-19.216-16-22.528V128H496z M392,256c-13.232,0-24-10.768-24-24s10.768-24,24-24s24,10.768,24,24
				S405.232,256,392,256z M352,376c0,4.416-3.584,8-8,8c-4.416,0-8-3.584-8-8v-32h16V376z M384,480h-16v-16h16V480z M416,480h-16
				v-16h16V480z M416,448h-16v-64h-16v64h-16v-80h48V448z M448,288c0,13.232-10.768,24-24,24h-8v40h-48v-40h-16v16h-16v-16
				c0-13.232,10.768-24,24-24h72v-16h16V288z M448,256h-16v-40c0-4.416,3.584-8,8-8c4.416,0,8,3.584,8,8V256z M400,112V16h80v96H400
				z"/>
            <path d="M444,32h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c2.2,0,4,1.8,4,4s-1.8,4-4,4h-28v16h28c11.032,0,20-8.968,20-20
				c0-4.504-1.496-8.656-4.008-12c2.512-3.344,4.008-7.496,4.008-12C464,40.968,455.032,32,444,32z"/>
            <path d="M304,54.832C304,42.24,293.76,32,281.168,32h-2.328C266.24,32,256,42.24,256,54.832V56h16v-1.168
				c0-3.76,3.072-6.832,6.832-6.832h2.328c3.768,0,6.84,3.072,6.84,6.832c0,2.6-1.448,4.944-3.784,6.112L256,75.056V96h48V80
				h-22.112l9.488-4.744C299.168,71.36,304,63.536,304,54.832z"/>
        </g>
    </g>
</g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
</svg>
                            <?= esc_html__('all customers','nss') ?>
                        </h2>
                        <?php
                        break;
                    case 'export_newsletter_phones':
                        ?>
                        <h2>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 166.781 166.781" style="enable-background:new 0 0 166.781 166.781;" xml:space="preserve">
<g>
    <g>
        <g>
            <path d="M163.451,70.046l-32.35-20.847c-0.253-0.161-0.532-0.222-0.804-0.312v-7.19c0-1.92-1.554-3.475-3.475-3.475H113.92
				L86.97,21.378c-1.126-0.706-2.558-0.706-3.685,0l-26.95,16.844H39.958c-1.92,0-3.475,1.554-3.475,3.475v7.188
				c-0.272,0.09-0.552,0.152-0.804,0.314L3.329,70.046c-0.991,0.641-1.592,1.741-1.592,2.921v90.339c0,1.92,1.554,3.475,3.475,3.475
				h156.356c1.92,0,3.475-1.554,3.475-3.475V72.968C165.043,71.787,164.442,70.688,163.451,70.046z M85.128,28.423l15.681,9.799
				H69.447L85.128,28.423z M43.433,45.171h79.915v78.178c0,0.01,0.006,0.018,0.006,0.029l-11.754,7.137l-28.284-15.427
				c-1.055-0.57-2.338-0.567-3.386,0.034l-25.81,14.749l-10.692-6.492c0-0.01,0.006-0.018,0.006-0.028L43.433,45.171z M8.687,74.861
				l27.796-17.91v62.212L8.687,102.285V74.861z M8.687,110.412l38.537,23.397L8.687,155.831V110.412z M15.689,159.833l66.005-37.715
				l69.145,37.715H15.689z M158.094,155.874L118.65,134.36l39.444-23.949V155.874z M158.094,102.285l-27.797,16.877V56.951
				l27.797,17.911V102.285z"/>
            <path d="M57.331,79.917h41.695c1.92,0,3.475-1.554,3.475-3.475V55.595c0-1.92-1.554-3.475-3.475-3.475H57.331
				c-1.92,0-3.475,1.554-3.475,3.475v20.847C53.856,78.363,55.411,79.917,57.331,79.917z M60.805,59.069h34.746v13.898H60.805
				V59.069z"/>
            <rect x="53.856" y="86.866" width="55.593" height="6.949"/>
            <rect x="53.856" y="100.765" width="55.593" height="6.949"/>
            <path d="M147.67,41.697c0.889,0,1.778-0.339,2.457-1.018l12.283-12.283c1.357-1.357,1.357-3.556,0-4.913
				c-1.357-1.358-3.556-1.357-4.913,0l-12.283,12.283c-1.357,1.357-1.357,3.556,0,4.913
				C145.892,41.358,146.781,41.697,147.67,41.697z"/>
            <path d="M16.654,40.679c0.679,0.679,1.568,1.018,2.457,1.018c0.889,0,1.778-0.339,2.457-1.018c1.357-1.357,1.357-3.556,0-4.913
				L9.284,23.483c-1.357-1.357-3.556-1.357-4.913,0c-1.357,1.357-1.357,3.556,0,4.913L16.654,40.679z"/>
            <path d="M118.584,24.076c0.421,0.17,0.859,0.247,1.289,0.247c1.378,0,2.684-0.825,3.227-2.185l6.949-17.373
				c0.713-1.781-0.156-3.804-1.937-4.516c-1.764-0.709-3.804,0.149-4.516,1.937l-6.949,17.373
				C115.934,21.341,116.802,23.364,118.584,24.076z"/>
            <path d="M47.155,22.139c0.543,1.361,1.849,2.185,3.227,2.185c0.431,0,0.869-0.078,1.289-0.248
				c1.781-0.713,2.65-2.735,1.937-4.516L46.659,2.187c-0.713-1.788-2.748-2.647-4.516-1.937c-1.781,0.713-2.65,2.735-1.937,4.516
				L47.155,22.139z"/>
        </g>
    </g>
</g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
</svg>
                            <?= esc_html__('newsletter','nss') ?>
                        </h2>
                        <?php
                        break;
                endswitch;
                ?>
                    <div class="nirweb_massage">
                        <h5><?= esc_html__( 'professional version and tutorial','nss') ?></h5>
                        <p><?= esc_html__('to use options below , you can easily purchase and download the pro version of the plugin form the link below','nss') ?></p>
                        <a href="<?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?>"><?= esc_url('https://www.zhaket.com/web/nirsam-sms-plugin') ?></a>
                        <svg width="200px" height="200px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#caf3e5" stroke-width="2" d="M10,13 L10,16 L13,16 L13,19 L16,19 L16,21 L18,23 L23,23 L23,19 L12.74,8.74 C12.91,8.19 13,7.6 13,7 C13,3.69 10.31,1 7,1 C3.69,1 1,3.69 1,7 C1,10.31 3.69,13 7,13 C7.88,13 8.72,12.81 9.47,12.47 L10,13 Z M6,7 C5.4475,7 5,6.5525 5,6 C5,5.4475 5.4475,5 6,5 C6.5525,5 7,5.4475 7,6 C7,6.5525 6.5525,7 6,7 Z"/>
                        </svg>
                    </div>
            </div>
            </div>
        </div>
            <?php
    });
});


