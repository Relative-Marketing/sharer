<?php
/**
 * Class: Social social_network
 */
namespace RelativeMarketing\Sharer;

/**
 * Responsible for ensuring consistancy when registering
 * a social social_network.
 */

class SocialNetwork {
	/**
	 * The unique id for the social_network
	 * 
	 * e.g 'facebook'
	 */
	private $id;

	/**
	 * The nicename for the social_network
	 * 
	 * e.g 'Facebook'
	 */
	private $nice_name;

	/**
	 * Link to the users page on the network
	 * 
	 * e.g 'https://facebook.com/my-page
	 */
	private $social_network_link;

	/**
	 * A link to an (optional) icon image that should be useds when graphically
	 * representing the social_network
	 * 
	 * If icon is not provided then a font awesome icon must be.
	 */
	private $img_icon;

	/**
	 * The (optional) font awesome icon to use
	 * 
	 * If a fontawesome icon is not provided then an image icon must be.
	 */
	private $fa_icon;

	/**
	 * Icon type to use when rendering the social network
	 * 
	 * should be either fa or img
	 */
	private $icon_type;

	/**
	 * A valid sprintf string that will be used to contruct the share url.
	 * 
	 * Arguments for this string will be name, link, media, referrer in that order
	 */
	private $share_link_format;

	/**
	 * A list of keys that a social_network could have, id has not been added as it should be considered immutable
	 */
	private static $valid_social_network_keys = array( 'nice_name', 'social_network_link', 'share_link_format', 'img_icon', 'fa_icon', 'icon_type' );

	public function __construct( $id, $nice_name, $share_link_format, $social_network_link = '', $img_icon = '', $fa_icon = '', $icon_type = 'fa' ) {
		$this->id = $id;
		$this->nice_name = $nice_name;
		$this->social_network_link = $social_network_link;
		$this->share_link_format = $share_link_format;
		$this->img_icon = $img_icon;
		$this->fa_icon = $fa_icon;
		$this->icon_type = $icon_type;
	}

	public function get_social_network_array() {
		return array(
			'id'                => $this->id,
			'nice_name'         => $this->nice_name,
			'social_network_link'      => $this->social_network_link,
			'share_link_format' => $this->share_link_format,
			'img_icon'              => $this->img_icon,
			'fa_icon'           => $this->fa_icon,
			'icon_type'           => $this->icon_type,
		);
	}

	public function get_id() {
		return $this->id;
	}

	public static function is_valid_social_network_key( $key ) {
		return in_array($key, self::$valid_social_network_keys );
	}
}