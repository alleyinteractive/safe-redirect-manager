<?php
/**
 * Donate Button component.
 *
 * @package CPR
 */

namespace CPR\Components\Donate;

/**
 * Donate Button.
 */
class Donate_Button extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'donate-button';

	/**
	 * Get the default label.
	 *
	 * @return string
	 */
	public function get_default_label() : string {
		return __( 'Donate', 'cpr' );
	}

	/**
	 * Get the default url.
	 *
	 * @return string
	 */
	public function get_default_url() : string {
		return '/donate/';
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {

		$settings = get_option( 'cpr-settings' );

		return [
			'label' => $settings['giving']['button']['donate_button']['label'] ?? $this->get_default_label(),
			'url'   => $settings['giving']['button']['donate_button']['url'] ?? $this->get_default_url(),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'donate_button' => new \Fieldmanager_Group(
				[
					'label' => __( 'Donate Button', 'cpr' ),
					'children' => [
						'label' => new \Fieldmanager_Textfield(
							[
								'label'         => __( 'Button Text', 'cpr' ),
								'default_value' => $this->get_default_label(),
							]
						),
						'url' => new \Fieldmanager_Link(
							[
								'label'         => __( 'Button URL', 'cpr' ),
								'default_value' => $this->get_default_url(),
							]
						),
					],
				]
			),
		];
	}
}
