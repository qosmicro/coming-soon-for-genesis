<?php /*

Plugin Name: Genesis Coming Soon
Plugin URI: https://qosmicro.com/plugins/coming-soon-for-genesis
Description: Genesis Coming Soon allows you to create a simple coming soon page while keeping all features that Genesis offers.
Author: Jose Manuel Sanchez
Author URI: https://qosmicro.com/

Text Domain: coming-soon-for-genesis
Domain Path: /languages

Version: 1.0.6

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl.html

 _____     _____ _____ _             
|     |___|   __|     |_|___ ___ ___ 
|  |  | . |__   | | | | |  _|  _| . |
|__  _|___|_____|_|_|_|_|___|_| |___|
   |__|                              

================================================================== */


//* Define Constants
define( 'GCS_VERSION', '1.0.6' );
define( 'GCS_SETTINGS', 'gcs-settings' );
define( 'GCS_PLUGIN_DIR', dirname( __FILE__ ) );


//* Activation Hook
register_activation_hook( __FILE__, 'gcs_activation_check' );
function gcs_activation_check() {
	if( !defined( 'PARENT_THEME_VERSION' ) || 
		!version_compare( PARENT_THEME_VERSION, '2.3.0', '>=' ) || 
		!version_compare( get_bloginfo('version'), '4.4.2', '>=' ) )
		gcs_deactivate( '2.3.0', '4.4.2' );
	else
		flush_rewrite_rules();
}


//* Deactivation Hook
register_deactivation_hook( __FILE__, 'gcs_deactivation' );
function gcs_deactivation() {
	flush_rewrite_rules();
}


//* Deactivate Genesis Coming Soon
function gcs_deactivate( $genesis_version = '2.3.0', $wp_version = '4.4.2' ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( sprintf( __( 'Sorry, you cannot run Genesis Coming Soon without WordPress %s and <a href="%s">Genesis %s</a>, or greater.', 'coming-soon-for-genesis' ), $wp_version, 'http://www.studiopress.com/', $genesis_version ) );
}


//* Plugin Initialization
add_action( 'genesis_init', 'gcs_genesis_init', 20 );
function gcs_genesis_init() {

	if( ! is_admin() ) {

		# If user is logged, do nothing
		if( is_user_logged_in() &&
			!isset($_GET['gcs_preview']) ) return;

		# If in login page, do nothing
		if( strrchr($_SERVER["PHP_SELF"], "/") == strrchr(wp_login_url(), "/") || 
			strrchr($_SERVER["SCRIPT_NAME"], "/") == strrchr(wp_login_url(), "/") ) return;

		# Include front files
		require_once( GCS_PLUGIN_DIR . '/inc/front.php' );

	} else {

		# Include admin files
		require_once( GCS_PLUGIN_DIR . '/inc/admin.php' );

	}

}

//* Make sure theme is responsive
add_action( 'after_setup_theme', 'gcs_setup_theme' ); 
function gcs_setup_theme( $content ) {
	add_theme_support( 'genesis-responsive-viewport' );
}


//* Enqueue scripts & styles (admin)
add_action( 'admin_enqueue_scripts', 'gcs_admin_scripts' );
function gcs_admin_scripts() {
	wp_enqueue_style( 'gcs-style-admin', plugins_url( 'css/admin.css', __FILE__ ), array(), GCS_VERSION );
	if( isset( $_GET['page'] ) )
		if( 'genesis-coming-soon' != $_GET['page'] ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'gcs-script-admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), GCS_VERSION );
}


//* Enqueue scripts & styles (front)
add_action( 'wp_enqueue_scripts', 'gcs_wp_scripts' );
function gcs_wp_scripts() {
	wp_enqueue_style( 'gcs-style-front', plugins_url( 'css/front.css', __FILE__ ), array(), GCS_VERSION );
}


//* Show/Hide toolbar message when active
add_action( 'admin_bar_menu', 'gcs_admin_bar_menu', 999 );
function gcs_admin_bar_menu( $wp_admin_bar ) {
	$message = '';

	if( !is_object( $wp_admin_bar ) || 
		!function_exists( 'is_admin_bar_showing' ) ||
		!is_admin_bar_showing() ) return;

	$_comingsoon = stripslashes_deep( get_option( GCS_SETTINGS ) );
	extract( $_comingsoon, EXTR_PREFIX_ALL, 'op' );

	switch( $op_status ) {
		case 'coming_soon':
			$message .= __('Coming Soon Mode Active','coming-soon-for-genesis');
			break;
		case 'maintenance':
			$message .= __('Maintenance Mode Active','coming-soon-for-genesis');
			break;
		default:
			return;
	}

	$args = array(
		'id'     => 'gcs-bar-message',
		'title'  => '<span class="ab-icon"></span><span class="ab-label">'.$message.'</span>',
		'href'   => admin_url() . 'admin.php?page=genesis-coming-soon',
		'parent' => 'top-secondary',
		'meta'   => array( 
						'class' => 'gcs-bar-message',
						'title' => $message,
					),
	);
	$wp_admin_bar->add_node( $args );

}





























/* --- end */