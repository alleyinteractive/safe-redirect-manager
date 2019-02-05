<?php
/**
 * Social Links component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Social Links.
 */
class Social_Links extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'social-links';

	/**
	 * Get the default Facebook link.
	 *
	 * @return string
	 */
	public function get_default_facebook() {
		return 'https://www.facebook.com/ColoradoPublicRadio/';
	}

	/**
	 * Get the default Twitter link.
	 *
	 * @return string
	 */
	public function get_default_twitter() {
		return 'https://twitter.com/copublicradio/';
	}

	/**
	 * Get the default Instagram link.
	 *
	 * @return string
	 */
	public function get_default_instagram() {
		return 'https://www.instagram.com/newscpr/';
	}

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 *
	 */
	public function default_children() {

		$settings = get_option( 'cpr-settings' );

		return [
			( new Social_Links_Item() )
				->merge_config(
					[
						'type' => 'facebook',
						'url'  => $settings['social']['social']['facebook'] ?? $this->get_default_facebook(),
					]
				),
			( new Social_Links_Item() )
				->merge_config(
					[
						'type' => 'twitter',
						'url'  => $settings['social']['social']['twitter'] ?? $this->get_default_twitter(),
					]
				),
			( new Social_Links_Item() )
				->merge_config(
					[
						'type' => 'instagram',
						'url'  => $settings['social']['social']['instagram'] ?? $this->get_default_instagram(),
					]
				),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() {
		return [
			'facebook' => new \Fieldmanager_Link(
				[
					'label' 		=> __( 'Facebook', 'cpr' ),
					'default_value' => $this->get_default_facebook(),
				]
			),
			'twitter' => new \Fieldmanager_Link(
				[
					'label' 		=> __( 'Twitter', 'cpr' ),
					'default_value' => $this->get_default_twitter(),
				]
			),
			'instagram' => new \Fieldmanager_Link(
				[
					'label' 		=> __( 'Instagram', 'cpr' ),
					'default_value' => $this->get_default_instagram(),
				]
			),
		];
	}
}
