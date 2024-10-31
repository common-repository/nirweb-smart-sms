<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

  $demo    = get_option( 'csf_demo_mode', false );
  $text    = ( ! empty( $demo ) ) ? 'غیر فعال سازی' : 'فعال سازی';
  $status  = ( ! empty( $demo ) ) ? 'deactivate' : 'activate';
  $class   = ( ! empty( $demo ) ) ? ' CFSSMARTSMS-warning-primary' : '';
  $section = ( ! empty( $_GET[ 'section' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'section' ] ) ) : 'about';
  $links   = array(
    'about'           => 'درباره',
    'quickstart'      => 'شروع سریع',
    'documentation'   => 'مستندات افزونه',
    'free-vs-premium' => 'مقایسه نسخه رایگان و پرمیوم',
    'support'         => 'پشتیبانی',
    'relnotes'        => 'یادداشت های انتشار',
  );

?>
<div class="CFSSMARTSMS-welcome CFSSMARTSMS-welcome-wrap">

  <h1>به فریمورک قدرتمند کُداستار خوش آمدید <?php echo wp_kses_post( CFSSMARTSMS::$version ); ?></h1>

  <p class="CFSSMARTSMS-about-text">یک چارچوب قدرتمند و گزینه ساده و سبک وردپرس برای ایجاد پنل تنظیمات حرفه ای برای قالب ها و افزونه ها</p>

  <p class="CFSSMARTSMS-demo-button"><a href="<?php echo esc_url( add_query_arg( array( 'CFSSMARTSMS-demo' => $status ) ) ); ?>" class="button button-primary<?php echo wp_kses_post( $class ); ?>"><?php echo wp_kses_post( $text ); ?> Demo</a></p>

  <div class="CFSSMARTSMS-logo">
    <div class="CFSSMARTSMS--effects"><i></i><i></i><i></i><i></i></div>
    <div class="CFSSMARTSMS--wp-logos">
      <div class="CFSSMARTSMS--wp-logo"></div>
      <div class="CFSSMARTSMS--wp-plugin-logo"></div>
    </div>
    <div class="CFSSMARTSMS--text">فریمورک کُداستار</div>
    <div class="CFSSMARTSMS--text CFSSMARTSMS--version">v<?php echo wp_kses_post( CFSSMARTSMS::$version ); ?></div>
  </div>

  <h2 class="nav-tab-wrapper wp-clearfix">
    <?php

      foreach ( $links as $key => $link ) {

        if ( CFSSMARTSMS::$premium && $key === 'free-vs-premium' ) { continue; }

        $activate = ( $section === $key ) ? ' nav-tab-active' : '';

        echo '<a href="'. esc_url( add_query_arg( array( 'page' => 'CFSSMARTSMS-welcome', 'section' => $key ), admin_url( 'tools.php' ) ) ) .'" class="nav-tab'. wp_kses_post( $activate ) .'">'. wp_kses_post( $link ) .'</a>';

      }

    ?>
  </h2>
