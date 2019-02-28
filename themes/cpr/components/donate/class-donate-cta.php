<?php
/**
 * Donate CTA component.
 *
 * @package CPR
 */

namespace CPR\Components\Donate;

/**
 * Donate CTA.
 */
class Donate_CTA extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'donate-cta';

	/**
	 * Get the default label.
	 *
	 * @return string
	 */
	public function get_default_heading() : string {
		return __( 'Donate to CPR', 'cpr' );
	}

	/**
	 * Get the default description.
	 *
	 * @return string
	 */
	public function get_default_description() : string {
		return __( 'Support impartial journalism, music exploration, and discovery with your monthly gift today.', 'cpr' );
	}

	/**
	 * Get the default label.
	 *
	 * @return string
	 */
	public function get_default_button_label() : string {
		return __( 'Donate Now', 'cpr' );
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		$settings = get_option( 'cpr-settings' );

		return [
			'heading' => $settings['giving']['cta']['donate_cta']['heading'] ?? $this->get_default_heading(),
		];
	}

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		$settings = get_option( 'cpr-settings' );

		return [
			( new Donate_Button() )
				->set_config(
					'label',
					$settings['giving']['cta']['donate_cta']['button_label'] ?? $this->get_default_button_label()
				),
			( new \WP_Components\HTML() )
				->set_config(
					'content',
					$settings['giving']['cta']['donate_cta']['description'] ?? $this->get_default_description()
				),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'donate_cta' => new \Fieldmanager_Group(
				[
					'label' => __( 'Donate CTA', 'cpr' ),
					'children' => [
						'heading' => new \Fieldmanager_Textfield(
							[
								'label'         => __( 'Heading', 'cpr' ),
								'default_value' => $this->get_default_heading(),
							]
						),
						'description' => new \Fieldmanager_RichTextArea(
							[
								'label'         => __( 'Description', 'cpr' ),
								'default_value' => $this->get_default_description(),
							]
						),
						'button_label' => new \Fieldmanager_Textfield(
							[
								'label'         => __( 'Button Text', 'cpr' ),
								'default_value' => $this->get_default_button_label(),
							]
						),
					],
				]
			),
		];
	}
}
