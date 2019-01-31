<?php
/**
 * Responsible for loading all scripts and styles the plugin requires
 */
namespace RelativeMarketing\Sharer;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\add_font_awesome' );

function add_font_awesome() {
	// TODO: add some check here to allow the user to choose whether or not they would like us to enqueue font awesome
	wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\add_frontend_styles' );

function add_frontend_styles() {
	$mtime = date("ymd-Gis", filemtime( RELATIVE_SHARER_DIR . 'dist/style.css' ));
	wp_register_style( 'relative-sharer-css',    RELATIVE_SHARER_URL . 'dist/style.css', false,   $mtime );
    wp_enqueue_style ( 'relative-sharer-css' );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\load_option_page_scripts' );

function load_option_page_scripts( $hook ) {
	/**
	 * Make sure we only add these scripts the relative sharer options page
	 */
	if ( ! $hook === 'toplevel_page_relative_sharer' )
		return;

		
		$css_mtime = date("ymd-Gis", filemtime( RELATIVE_SHARER_DIR . 'dist/admin.css' ));
		wp_enqueue_style( 'relative-sharer-options-page-css', RELATIVE_SHARER_URL . 'dist/admin.css', array(), $css_mtime );

		$js_mtime = date("ymd-Gis", filemtime( RELATIVE_SHARER_DIR . 'dist/admin.js' ));
		wp_enqueue_script( 'relative-sharer-options-page-js', RELATIVE_SHARER_URL . 'dist/admin.js', array(), $js_mtime, true );

		/**
		 * Make the rest root and nonce available so users can make authorised requests
		 */
		wp_localize_script( 'relative-sharer-options-page-js', 'wpApiSettings', array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' )
		) );
}