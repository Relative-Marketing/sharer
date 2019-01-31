<?php
/**
 * Registers a widget that when uses will display the 
 * active social network profile links
 */
namespace RelativeMarketing\Sharer;

class Profile_Widget extends \WP_Widget {
	public function __construct() {
		parent::__construct(
			'relative_sharer_profile_links_widget',
			__( 'Social Profile Links - Relative Sharer', 'relative-sharer' ),
			array(
				'decription' => __( 'Will display the links to your active social network profiles' ),
			)
		);
	}

	function widget($args, $instance) {
		echo get_page_profile_links();
	}

}

add_action( 'widgets_init', __NAMESPACE__ . '\\register_profile_widget' );

function register_profile_widget() {
	register_widget( __NAMESPACE__ . '\\Profile_Widget' );
}