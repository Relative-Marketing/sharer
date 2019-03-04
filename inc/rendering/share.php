<?php
/**
 * Handles all rendering functions to do with the SHARE context
 */
namespace RelativeMarketing\Sharer;

/**
 * Will return HTML output for all active_page_social_networks
 */
function get_page_share_icons() {
	// return $html;
	$out = '';
	$networks = get_active_social_networks( 'share' );
	
	if ( ! empty( $networks ) ) {		
		$out .= '<div class="relative-sharer-social-network-icons">';
		foreach ( $networks as $network ) {
			// Desktop
			$out .= '<div class="relative-sharer-social-network-icons__icon--share">';
			$out .= '<a href="' . get_share_link( $network['share_link_format'] ) . '" class="relative-sharer-social-network__share-link" target="_blank">';

			if ( array_key_exists( 'icon_type', $network ) && $network['icon_type'] === 'img' ) {
				$out .= '<img src="' . $network['img_icon'] . '" alt="' . $network['nice_name']  . ' icon">';
			} else {
				$out .= '<i aria-hidden class="fab fa-' . $network['fa_icon'] . ' relative-sharer-social-network-icons-desktop desktop-d-inline-block"></i>';
				$out .= font_awesome_stack( $network['nice_name'], $network['fa_icon'], 'square', '1x', 'fab', 'desktop-d-none' );
			}

			$out .= '<span class="relative-sharer-social-network__share-link-nicename desktop-d-inline-block">' . $network['nice_name'] . '</span>';
			$out .= '</a>';
			$out .= '</div>';
		}
		
		$out .= '</div>';
		
	}
	
	return $out;
}

function get_page_sharer() {
	$out = '<div class="relative-sharer__page-share-wrapper">';
	$out .= apply_filters( 'relative_sharer_before_page_sharer', '');
	$out .= get_page_share_icons();
	$out .= apply_filters( 'relative_sharer_after_page_sharer', '');
	$out .= '</div>';

	return $out;
}

function get_share_icon($network) {
	if ( ! array_key_exists( 'icon_type', $network) ) {
		return false;
	}

	
}