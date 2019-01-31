<?php
/**
 * This file contains functions for working with all social network Data management (CRUD tasks)
 */
namespace RelativeMarketing\Sharer;

/**
 * Returns all currently registered social_networks.
 */
function get_registered_social_networks( ) {
	return get_option( RELATIVE_SHARER_SOCIAL_NETWORKS, array() );
}

/**
 * Will get the registered social networks and will just return a standard array that is not associative
 */
function get_registered_social_networks_not_assoc() {
	$assoc = get_registered_social_networks();
	$networks = array();

	foreach($assoc as $network) {
		$networks[] = $network;
	}

	return $networks;
}
/**
 * Checks if a social_network with a given id is registered.
 */
function is_social_network_registered( $id ) {
	return array_key_exists( $id, get_registered_social_networks() );
}

/**
 * Checks if a social_network is active in a given context.
 */
function is_social_network_active( $id, $context ) {
	return in_array( $id, get_active_social_network_ids( $context ) );
}

/**
 * Returns all active social_network ids for a given content.
 */
function get_active_social_network_ids( $context ) {
	return is_valid_context( $context )
		? get_option( RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS . '_' . $context, array() )
		: invalid_context_error( __FUNCTION__ );
}

/**
 * Returns all active social_networks for a given content
 */
function get_active_social_networks( $context ) {
	if (! is_valid_context( $context) )
		return invalid_context_error( __FUNCTION__ );
	
	$ids = get_active_social_network_ids($context);
	$social_networks = get_registered_social_networks();
	$active = array();

	foreach ( $ids as $id ) {
		$active[$id] = $social_networks[$id];
	}

	return $active;
}

/**
 * Registers a social_network. The social_network array may contain the following:
 * 
 * id - The id of the social_network
 * name - The nicename for the social_network
 * link_to_social_network - The link to the users dedicated page on the social network you are adding
 * icon - A custom icon for the social network
 * fa_icon - The font awesome icon name to use for this social_network e.g 'facebook'
 * share_link_format - A valid sprintf string that will be used to contruct the share url. Arguments for this string will be name, link, media, referrer in that order.
 * 
 * return boolean|WP_Error - True if the social_network was registered or WP_Error on failure
 * 
 * TODO: Make it possible to disallow activation in a particular context. e.g news items cannot be shared to instagram, so giving users an option to activate it doesn't make sense.
 */
function register_social_network( SocialNetwork $social_network ) {
	$social_networks = get_registered_social_networks();

	if ( array_key_exists( $social_network->get_id(), $social_networks ) ) {
		return false;
	}

	$social_networks[ $social_network->get_id() ] = $social_network->get_social_network_array();

	update_option( RELATIVE_SHARER_SOCIAL_NETWORKS, $social_networks );
	
	return true;
}

/**
 * Works in a similar fashion to register_social_network but expects the given $id to exists. $data should be any valid social_network data you would like to update.
 */
function update_social_network($id, $data) {
	// fetch the date
	$social_networks = get_registered_social_networks();
	
	if ( ! \array_key_exists($id, $social_networks) ) {
		// TODO: be more consistent with return values between this func and update_social_network_value
		return false;
	}
	
	// TODO: check that the data being passed in is valid
	$social_networks[$id] = array_merge($social_networks[$id], $data);

	update_option( RELATIVE_SHARER_SOCIAL_NETWORKS, $social_networks );

	return true;

}

/**
 * Will update a specific value of the social network for example this function could be used to update the nicename independantly
 */
function update_social_network_value( $id, $key, $value ) {
	if ( ! SocialNetwork::is_valid_social_network_key( $key ) ) {
		return new \WP_Error( 'invalid_social_network_key', 'The $key given when calling ' . __FUNCTION__ . ' is not a valid social_network key' );
	}
	
	$social_networks = get_registered_social_networks();
	
	if ( ! array_key_exists( $id, $social_networks ) ) {
		return new \WP_Error( 'social_network_not_registered', 'The social_network $id provided when calling ' . __FUNCTION__ . ' is not a registered social_network' );
	}
	
	// TODO: Make sure the value is sanitized before storing
	$social_networks[$id][$key] = $value;
	
	update_option( RELATIVE_SHARER_SOCIAL_NETWORKS, $social_networks );

	return true;
}

/**
 * Will activate the given social_network in the given content
 */
function activate_social_network( $id, $context ) {
	if ( ! is_valid_context( $context ) )
		return invalid_context_error( __FUNCTION__ );

	// Check that the social_network exists before activating it
	if ( ! is_social_network_registered( $id ) )
		return new \WP_Error('social_network_not_registered', 'Relative Sharer: social_network $id ($id = ' . $id . ') given to ' . __FUNCTION__ . ' is not a currently registered social_network. In file: ' . __FILE__ . ' line: ' . __LINE__ );
	
	$active = get_active_social_network_ids( $context );

	// If the network is already active in the context then return true as what
	// the caller wants is already done
	if ( array_search( $id, $active ) === true ) {
		return true;
	}

	$active[] = $id; 

	return update_option( RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS . '_' . $context, array_unique( $active ) );
}

/**
 * Will deactivate the given social_network in the given content
 */
function deactivate_social_network( $id, $context ) {
	if ( ! is_valid_context( $context ) )
		return invalid_context_error( __FUNCTION__ );

	$social_networks = get_active_social_network_ids( $context );

	$index = array_search( $id, $social_networks );

	if ( $index !== false ) {
		array_splice($social_networks, $index, 1);
	}

	update_option( RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS . '_' . $context, $social_networks );

	return true;
}

/**
 * Will set the active status of a social network in a given context
 * 
 * @param $activate bool setting to true will activate the social network and false will deactivate it in the context given
 */
function set_social_network_active_state($id, $context, $activate) {
	$result = '';

	if ( $activate ) {
		$result = activate_social_network($id, $context);
	} else {
		$result = deactivate_social_network($id, $context);
	}

	if ( is_wp_error( $result ) ) {
		return false;
	}

	return true;
}

/**
 * Will register and activate a given social_network and activate it for the given contents
 */
function register_and_activate_social_network( SocialNetwork $social_network, $contexts = array() ) {

	register_social_network( $social_network );
	
	foreach ($contexts as $context) {
		activate_social_network( $social_network->get_id(), $context );
	}

	return true;
}

/**
 * Will delete a social_network from the registered_social_networks array.
 */
function delete_social_network( $id ) {
	$social_networks = get_registered_social_networks();

	if ( array_key_exists( $id, $social_networks ) ) {
		unset( $social_networks[$id] );
	}

	// Make sure we also delete from active contexts
	foreach (RELATIVE_SHARER_AVAILABLE_CONTEXTS as $context) {
		deactivate_social_network($id, $context);
	}

	update_option( RELATIVE_SHARER_SOCIAL_NETWORKS, $social_networks );

	return true;
}

/**
 * Delete all options
 */
function delete_all_settings() {
	// Delete all active profiles
	delete_option( RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_PROFILE );
	// Delete all active share
	delete_option( RELATIVE_SHARER_ACTIVE_SOCIAL_NETWORKS_SHARE );
	// Delete all social networks
	delete_option( RELATIVE_SHARER_SOCIAL_NETWORKS );
}