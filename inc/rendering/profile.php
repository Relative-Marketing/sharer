<?php
/**
 * Handles all rendering functions to do with the PROFILE context
 */
namespace RelativeMarketing\Sharer;

if ( ! function_exists( __NAMESPACE__ . '\get_page_profile_links' ) ) {

	function get_page_profile_links() {
		$networks = get_active_social_networks( 'profile' );

		if( empty( $networks ) ) {
			return '';
		}

		$wrapper_classes = apply_filters( 'relative_sharer_get_profile_icons-wrapper_classes', array( 'relative-sharer-social-network-icons', 'relative-sharer-social-network-icons--profile' ) );
		$icon_classes = apply_filters( 'relative_sharer_get_profile_icons-icon_classes', array( 'relative-sharer-social-network-icons__icon', 'relative-sharer-social-network-icons__icon--profile' ) );
		
		$output = '<div class="' . esc_attr( join( ' ', $wrapper_classes ) ) . '">';

		foreach( $networks as $network ) {
			$output .= '<div class="' . esc_attr( join( ' ', $icon_classes ) ) . '">';
				// TODO: give the option to choose target attr value
				$output .= '<a href="' . $network['social_network_link'] . '" target="_BLANK">';
				$output .= font_awesome_stack( $network['nice_name'], $network['fa_icon'] );
				// TODO: make sure to add the style for .screen-reader-text incase it isn't added by the theme
				$output .= '<span class="screen-reader-text">' . $network['nice_name'] . '</span>';
				$output .= '</a>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

}