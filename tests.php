<?php 
namespace RelativeMarketing\Sharer;
/**
 * ⚠️ WARNING ⚠️
 * 
 * Uncommenting the following line will delete any data you have
 * stored when the website is next loaded.
 */
// add_action('plugins_loaded', __NAMESPACE__ . '\\test');

function test() {

	$facebook = new SocialNetwork(
		'facebook',
		'Facebook',
		'https://www.facebook.com/sharer/sharer.php?u=%2$s',
		'https://facebook.com/your-page-link',
		'https://relativemarketing.co.uk/some-icon-url.svg',
		'facebook'
	);

	$twitter = new SocialNetwork(
		'twitter',
		'Twitter',
		'https://www.twitter.com/sharer/sharer.php?u=%2$s',
		'https://twitter.com/your-page-link',
		'https://relativemarketing.co.uk/some-icon-url.svg',
		'twitter'
	);

	
	echo "<pre>";
	echo "<p><strong>Reset all settings </strong></p>";
	delete_all_settings();
	echo "<p>Settings have been reset</p>";

	echo "<p><strong>get_registered_social_networks()</strong> should start empty</p>";
	var_dump( get_registered_social_networks() );
	echo "<p><strong>get_active_social_networks( 'profile' )</strong> should start empty</p>";
	var_dump( get_active_social_networks( 'profile' ) );
	echo "<p><strong>get_active_social_networks( 'share' )</strong> should start empty</p>";
	var_dump( get_active_social_networks( 'share' ) );
	
	register_social_network($facebook);
	deactivate_social_network('facebook', 'page');
	echo "<p><strong>Should be an error, social_network doesn't exist:</strong></p>";
	var_dump( activate_social_network('bana', 'profile') );
	echo "<p><strong>Should be true, because we've registered social_network:</strong></p>";
	var_dump( activate_social_network('facebook', 'profile') );
	echo "<p><strong>Should be an array containing one string 'facebook':</strong></p>";
	var_dump( get_active_social_network_ids('profile') );
	echo "<p><strong>Should be an array containing facebook complete social_network:</strong></p>";
	var_dump( get_active_social_networks('profile') );

	echo "<p><strong>Should be false because twitter hasn't been registered: </strong></p>";
	var_dump( is_social_network_registered('twitter') );
	echo "<p><strong>Now register twitter and activate is in both contexts: </strong></p>";
	register_and_activate_social_network($twitter, get_all_available_contexts() );
	echo "<p><strong>Should have twitter in social_networks array now: </strong></p>";
	var_dump( get_registered_social_networks() );
	echo "<p><strong>delete it again: </strong></p>";
	var_dump( delete_social_network( $twitter->get_id() ) );
	var_dump( get_registered_social_networks() );
	echo "<p><strong>Update social_network value: </strong></p>";
	var_dump( update_social_network_value( 'facebook', 'nice_name', 'Test update nicename' ) );
	var_dump( get_registered_social_networks() );
	echo "<p><strong>Should be an error key not valid: </strong></p>";
	var_dump( update_social_network_value( 'facebook', 'weird_name', 'Test update' ) );
	echo "<p><strong>Register twitter again for html output: </strong></p>";
	register_and_activate_social_network( $twitter, get_all_available_contexts() );
	register_and_activate_social_network( $facebook, get_all_available_contexts() );
	var_dump( update_social_network_value( 'facebook', 'nice_name', 'Facebook' ) );

	
	
	var_dump(get_registered_social_networks());

	//delete everything 
	delete_all_settings();
	//re-register default social networks
	register_default_social_networks();
	die();
}