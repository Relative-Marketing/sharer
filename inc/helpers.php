<?php
/**
 * Helpers
 */

namespace RelativeMarketing\Sharer;

/**
 * Get available contexts
 */
function get_all_available_contexts() {
	return explode( '|',RELATIVE_SHARER_AVAILABLE_CONTEXTS );
}
/**
 * Check if a given context is valid
 * 
 * return bool
 */
function is_valid_context($context = '') {
	return in_array( $context, get_all_available_contexts() );
}

function invalid_context_error($fn) {
	return new \WP_Error( 'invalid_context', 'Relative Sharer Plugin: An invalid context was passed to function ' . $fn );
}

/**
 * Font awesome stack
 * 
 * Will return a stacked font awesome icon properly formatted
 * 
 * $title - The title used when hovering over the icon
 * $inner_icon - Will be placed inside the $outer_icon
 * $size - The overall size of the stack 2x - 4x
 * $inner_icon_type - fab - font awesome brands could be something like fa for standard font awesome icon
 */

 function font_awesome_stack( $title, $inner_icon, $outer_icon = 'circle', $size = '2x', $inner_icon_type = 'fab', $classes = '' ) {
	$output = '<span class="fa-stack relative-sharer-social-network-icons__fa-stack fa-' . $size . ' ' . $classes .'">';
	$output .= '<i aria-hidden class="relative-sharer-icon--outer fa fa-' . $outer_icon . ' fa-stack-2x"></i>';
	$output .= '<i aria-hidden class="relative-sharer-icon--main ' . $inner_icon_type . ' fa-' . $inner_icon . ' fa-stack-1x" title="'. $title .'"></i>';
	$output .= '</span>';

	return $output;
 }

 function get_share_link( $sprinf_string ) {
	return sprintf( $sprinf_string, get_the_title(), get_the_permalink(), get_the_post_thumbnail_url( null, 'full' ), site_url() );
 }