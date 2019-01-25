<?php
/**
 * Newsletter component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Newsletter.
 */
class Newsletter extends \WP_Components\Component {

	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'newsletter';

	/**
	 * Get the default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'News That Matters, Delivered To Your Inbox', 'cpr' );
	}

	/**
	 * Get the default tagline.
	 *
	 * @return string
	 */
	public function get_default_tagline() {
		return __( 'Sign up for a smart, compelling, and sometimes funny take on your daily news briefing.', 'cpr' );
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		$settings = get_option( 'cpr-settings' );
		return [
			'heading' => $settings['engagement']['newsletter']['heading'] ?? $this->get_default_heading(),
			'tagline' => $settings['engagement']['newsletter']['tagline'] ?? $this->get_default_tagline(),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_heading(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Heading', 'cpr' ),
				]
			),
			'tagline' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_tagline(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Tagline', 'cpr' ),
				]
			),
		];
	}
}
