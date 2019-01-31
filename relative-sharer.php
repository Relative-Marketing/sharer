<?php
/**
* Relative Sharer
*
* @package     RelativeMarketing\RelativeSharer
* @author      Daniel Gregory
* @license     GPL-2.0+
*
* @wordpress-plugin
* Plugin Name: Relative Sharer
* Plugin URI:  TODO: Add plugin uri
* Description: Adds a sharing par to posts and pages and registers a widget to output links to your social networks.
* Version:     1.0.0
* Author:      Relative Marketing
* Author URI:  https://relativemarketing.co.uk
* Text Domain: relative-sharer
* License:     GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
namespace RelativeMarketing\Sharer;

if ( ! defined( 'ABSPATH' ) ) die();

/**
 * Constants for easy access to the plugin dir and url
 * throughout the plugin.
 */

if ( ! defined( 'RELATIVE_SHARER_DIR' ) )
	define( 'RELATIVE_SHARER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

if ( ! defined( 'RELATIVE_SHARER_URL' ) )
	define( 'RELATIVE_SHARER_URL', plugin_dir_url( __FILE__ ) );


/**
 * Define option names once to make it easy to update, should we ever need to.
 */
if ( ! defined( 'RELATIVE_SHARER_SOCIAL_NETWORKS' ) )
	define( 'RELATIVE_SHARER_SOCIAL_NETWORKS', 'relative_sharer_registered_social_networks' );

if ( ! defined( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS' ) )
	define( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS', 'relative_sharer_active_social_networks' );

if ( ! defined( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_PROFILE' ) )
	define( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_PROFILE', RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS . '_profile' );

if ( ! defined( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_SHARE' ) )
	define( 'RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_SHARE', RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS . '_share' );

if ( ! defined( 'RELATIVE_SHARER_AVAILABLE_CONTEXTS' ) )
	define( 'RELATIVE_SHARER_AVAILABLE_CONTEXTS', array( 'profile', 'share' ) );

/**
 * Adds all required scripts and styles to the site
 */
include RELATIVE_SHARER_DIR . 'inc/scripts-styles/loader.php';

/**
 * Ensures consistency when adding new social networks
 */
include RELATIVE_SHARER_DIR . 'inc/class-social-network.php';

/**
 * Functions to make life easier
 */
include RELATIVE_SHARER_DIR . 'inc/helpers.php';

/**
 * Everything to do with creating, reading, updating and deleting plugin data
 */
include RELATIVE_SHARER_DIR . 'inc/social-network-data-management.php';

/**
 * Responsible for rendering output in respective context
 */
include RELATIVE_SHARER_DIR . 'inc/rendering/profile.php';
include RELATIVE_SHARER_DIR . 'inc/rendering/share.php';

/**
 * Adds a widget to make it easy to add social network links to sidebars and widget areas
 */
include RELATIVE_SHARER_DIR . 'inc/widgets/class-profile-widget.php';

// TODO: Move this to it's own file

add_filter( 'the_content', __NAMESPACE__ . '\\output_share_buttons' );

function output_share_buttons( $content ) {
	if ( !is_single() ) {
		return $content;
	}

	return get_page_sharer() . $content;
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_sharer' );

function activate_sharer() {
	// If some options already exist do not do anything
	// if ( get_option( RELATIVE_SHARER_SOCIAL_NETWORKS ) ) {
	// 	return;
	// }

	/**
	 * Use some default social networks when activating the plugin for the first time.
	 */
	// TODO: set final defaults for social network registration on activation
	$default = array(
		'facebook' => array(
			'id' => 'facebook',
			'nice_name' => 'Facebook',
			'share_link_format' => 'https://www.facebook.com/sharer/sharer.php?u=%2$s',
			'social_network_link' => '#',
			'icon' => '',
			'fa_icon' => 'facebook',
		),
		'twitter' => array(
			'id' => 'twitter',
			'nice_name' => 'Twitter',
			'share_link_format' => 'https://twitter.com/intent/tweet?original_referer=%2$s&url=%2$s',
			'social_network_link' => '#',
			'icon' => '',
			'fa_icon' => 'twitter',
		),
		'linkedin' => array(
			'id' => 'linkedin',
			'nice_name' => 'LinkedIn',
			'share_link_format' => 'https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%1$s',
			'social_network_link' => '#',
			'icon' => '',
			'fa_icon' => 'linkedin-in',
		),
		'instagram' => array(
			'id' => 'instagram',
			'nice_name' => 'Instagram',
			'share_link_format' => '',
			'social_network_link' => '',
			'icon' => '',
			'fa_icon' => 'instagram',
		)
	);

	foreach ($default as $sn) {
		register_and_activate_social_network(
			new SocialNetwork( 
				$sn['id'],
				$sn['nice_name'],
				$sn['share_link_format'],
				$sn['social_network_link'],
				$sn['icon'],
				$sn['fa_icon']
			)
		);
	}
}

/**
 * Create an options page for this plugin
 */
function create_options_page() {
	add_menu_page(
		__( 'Relative Sharer', 'relative-sharer' ),
		__( 'Relative Sharer', 'relative-sharer' ),
		'manage_options',
		'relative-sharer',
		__NAMESPACE__ . '\\output_options_page',
		'dashicons-screenoptions'
	);
}

add_action('admin_menu', __NAMESPACE__ . '\\create_options_page' );

function output_options_page() {
	echo '<div id="relative-sharer-root"></div>';
}

add_action('rest_api_init', __NAMESPACE__ . '\\rest_data');

function rest_data() {
	
	register_rest_route(
		'relative-sharer/v1', 'get-sharing-data',
		array(
			'methods' => 'GET',
			'callback' => __NAMESPACE__ . '\\get_all_data',
		)
	);

	register_rest_route(
		'relative-sharer/v1', 'update-social-network',
		array(
			'methods' => 'POST',
			'callback' => __NAMESPACE__ . '\\endpoint_update_social_network',
			// TODO: validate args
			// 'args' => ['id' => array( 'validate_callback' => '__return_true' ), 'data' => array( 'validate_callback' => '__return_true' )]
		)
	);
	
	register_rest_route(
		'relative-sharer/v1', 'set-network-visibility',
		array(
			'methods' => 'POST',
			'callback' => __NAMESPACE__ . '\\endpoint_set_network_visibility',
			// TODO: validate args
			// 'args' => ['id' => array( 'validate_callback' => '__return_true' ), 'data' => array( 'validate_callback' => '__return_true' )]
		)
	);
}

function get_all_data() {
	if( ! current_user_can( 'manage_options' )) return;
	
	return array(
		'socialNetworks' => get_registered_social_networks_not_assoc(),
		'activeShareLinks' => get_active_social_networks('share'),
		'activeProfileLinks' => get_active_social_networks('profile')
	);
}

/**
 * For information about POST requests and getting arguments see:
 * 
 * https://developer.wordpress.org/reference/classes/wp_rest_request/
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 */
function endpoint_update_social_network( \WP_REST_Request $req ) {
	if (! current_user_can('manage_options') ) {
		return "";
	}

	$params = $req->get_params();
	$data = json_decode($params['data'], true);

	return update_social_network( $params['id'], $data );
}


function endpoint_set_network_visibility( \WP_REST_Request $req ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$params = $req->get_params();
	
	$id = $params['id'];
	$activeInContext = $params['activeInContext'];

	/**
	 * Update the active states of the network
	 */
	$share_action = set_social_network_active_state( $id, 'share', $activeInContext['share'] );
	$profile_action = set_social_network_active_state( $id, 'profile', $activeInContext['profile'] );
	
	// Above returns false if an error occurred
	if($share_action && $profile_action) {
		return true;
	}

	return false;
}