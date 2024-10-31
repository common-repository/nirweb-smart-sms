<?php if ( ! defined( 'ABSPATH' )  ) { die; }

$prefix = 'nirweb_smart_sms_perfix';

CFSSMARTSMS::createOptions( $prefix, array(
    'menu_title'  => __('Nirweb SMS General Settings', 'nss'),
    'menu_slug'   => 'nirweb_smart_sms_settings',
    'menu_type'   => 'submenu',
    'menu_parent' => 'send_sms_nirweb_smart_sms',
    'framework_title'   => __('NirWeb Team', 'nss'),
    'theme' => 'light',
    'show_bar_menu'           => false,
));

require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/general.php';

CFSSMARTSMS::createSection( $prefix, array(
    'title'  => __('Woocommerce', 'nss'),
    'id' => 'primary_woocommerce',
));
/********************************** Woocommerce  *******************************/
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/on-hold.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/processing.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/completed.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/refunded.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/failed.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/cancelled.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/stock-management.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/onsale.php';
require_once NIRWEB_SMART_SMS.'/option/options/woocommerce/first-purchase.php';

/********************************** WP  *******************************/
require_once NIRWEB_SMART_SMS.'/option/options/wp/user.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/scheduled.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/post.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/birthday.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/comments.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/newsletters.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/wordpress_update.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/contact_7.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/gravity_form.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/learnpress.php';
require_once NIRWEB_SMART_SMS.'/option/options/wp/learndash.php';



