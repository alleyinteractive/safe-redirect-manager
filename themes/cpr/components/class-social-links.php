<?php
/**
 * Social Links component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Social Links.
 */
class Social_Links extends \WP_Components\Social_Links {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'social-links';

	/**
	 * Define a default config shape.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'services'      => [
				'instagram' => __( 'Instagram', 'cpr' ),
				'facebook'  => __( 'Facebook', 'cpr' ),
				'twitter'   => __( 'Twitter', 'cpr' ),
			],
			'display_icons' => true,
		];
	}

	/**
	 * Get the default Facebook link.
	 *
	 * @return string
	 */
	public function get_default_facebook() : string {
		return 'https://www.facebook.com/ColoradoPublicRadio/';
	}

	/**
	 * Get the default Twitter link.
	 *
	 * @return string
	 */
	public function get_default_twitter() : string {
		return 'https://twitter.com/copublicradio/';
	}

	/**
	 * Get the default Instagram link.
	 *
	 * @return string
	 */
	public function get_default_instagram() : string {
		return 'https://www.instagram.com/newscpr/';
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		$services = $this->config['services'];
		$fields   = [];

		foreach ( $services as $service => $label ) {
			$fields[ $service ] = new \Fieldmanager_Link(
				[
					'label'         => $label,
					'default_value' => method_exists( $this, "get_default_{$service}" ) ?
						call_user_func( [ $this, "get_default_{$service}" ] ) : $service,
				]
			);
		}

		return $fields;
	}

	/**
	 * Parse data from a saved FM field.
	 *
	 * @return self
	 */
	public function parse_from_fm_data() : self {
		$links         = get_option( 'cpr-settings', [] )['social']['social'] ?? [];
		$display_icons = $this->get_config( 'display_icons' );
		$link_configs  = [];

		if ( ! empty( $links ) ) {
			foreach ( $links as $service => $url ) {
				$link_configs[ $service ] = [
					'type'        => $service ?? '',
					'url'         => $url ?? '',
					'display_icon' => $display_icons,
				];
			}
		}

		$this->create_link_components( $link_configs );

		return $this;
	}
}
