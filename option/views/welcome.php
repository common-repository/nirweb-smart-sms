<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Welcome' ) ) {
  class CSF_Welcome{

    private static $instance = null;

    public function __construct() {

      if ( CFSSMARTSMS::$premium && ( ! CFSSMARTSMS::is_active_plugin( 'codestar-framework/codestar-framework.php' ) || apply_filters( 'csf_welcome_page', true ) === false ) ) { return; }

      add_action( 'admin_menu', array( $this, 'add_about_menu' ), 0 );
      add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 5 );
      add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

      $this->set_demo_mode();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function add_about_menu() {
      add_management_page( 'Codestar Framework', 'فریمورک کُداستار', 'manage_options', 'CFSSMARTSMS-welcome', array( $this, 'add_page_welcome' ) );
    }

    public function add_page_welcome() {

      $section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';

      CFSSMARTSMS::include_plugin_file( 'views/header.php' );

      // safely include pages
      switch ( $section ) {

        case 'quickstart':
          CFSSMARTSMS::include_plugin_file( 'views/quickstart.php' );
        break;

        case 'documentation':
          CFSSMARTSMS::include_plugin_file( 'views/documentation.php' );
        break;

        case 'relnotes':
          CFSSMARTSMS::include_plugin_file( 'views/relnotes.php' );
        break;

        case 'support':
          CFSSMARTSMS::include_plugin_file( 'views/support.php' );
        break;

        case 'free-vs-premium':
          CFSSMARTSMS::include_plugin_file( 'views/free-vs-premium.php' );
        break;

        default:
          CFSSMARTSMS::include_plugin_file( 'views/about.php' );
        break;

      }

      CFSSMARTSMS::include_plugin_file( 'views/footer.php' );

    }

    public static function add_plugin_action_links( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['CFSSMARTSMS--welcome'] = '<a href="'. esc_url( admin_url( 'tools.php?page=CFSSMARTSMS-welcome' ) ) .'">تنظیمات</a>';
        if ( ! CFSSMARTSMS::$premium ) {
          $links['CFSSMARTSMS--upgrade'] = '<a href="'.esc_url('http://codestarframework.com/').'">Upgrade</a>';
        }
      }

      return $links;

    }

    public static function add_plugin_row_meta( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['CFSSMARTSMS--docs'] = '<a href="'.esc_url('http://codestarframework.com/documentation/').'" target="_blank">مستندات افزونه</a>';
      }

      return $links;

    }

    public function set_demo_mode() {

      $demo_mode = get_option( 'csf_demo_mode', false );

      $demo_activate = ( ! empty( $_GET[ 'CFSSMARTSMS-demo' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'CFSSMARTSMS-demo' ] ) ) : '';

      if ( ! empty( $demo_activate ) ) {

        $demo_mode = ( $demo_activate === 'activate' ) ? true : false;

        update_option( 'csf_demo_mode', $demo_mode );

      }

      if ( ! empty( $demo_mode ) ) {

        CFSSMARTSMS::include_plugin_file( 'samples/admin-options.php' );

        if ( CFSSMARTSMS::$premium ) {

          CFSSMARTSMS::include_plugin_file( 'samples/customize-options.php' );
          CFSSMARTSMS::include_plugin_file( 'samples/metabox-options.php'   );
          CFSSMARTSMS::include_plugin_file( 'samples/nav-menu-options.php'  );
          CFSSMARTSMS::include_plugin_file( 'samples/profile-options.php'   );
          CFSSMARTSMS::include_plugin_file( 'samples/shortcode-options.php' );
          CFSSMARTSMS::include_plugin_file( 'samples/taxonomy-options.php'  );
          CFSSMARTSMS::include_plugin_file( 'samples/widget-options.php'    );
          CFSSMARTSMS::include_plugin_file( 'samples/comment-options.php'   );

        }

      }

    }

  }

  CSF_Welcome::instance();
}
