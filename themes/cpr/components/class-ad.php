<?php
/**
 * Advertisement component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Advertisement.
 */
class Ad extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'ad-unit';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'background_color'   => '',
			'background_padding' => false,
			'height'             => 250,
			'width'              => 300,
		];
	}
}
