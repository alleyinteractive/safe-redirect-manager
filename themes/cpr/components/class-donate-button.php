<?php
/**
 * Donate Button component.
 *
 * @package CPR
 */

namespace CPR\Component;

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
	public function get_default_label() {
		return __( 'Donate to CPR', 'cpr' );
	}

	/**
	 * Get the default url.
	 *
	 * @return string
	 */
	public function get_default_url() {
		return '/donate/';
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {

		$settings = get_option( 'cpr-settings' );

		return [
			'label' => $settings['giving']['donate']['donate_button']['label'] ?? $this->get_default_label(),
			'url'   => $settings['giving']['donate']['donate_button']['url'] ?? $this->get_default_url(),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() {
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
