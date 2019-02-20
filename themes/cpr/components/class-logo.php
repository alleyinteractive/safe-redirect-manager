<?php
/**
 * Logo component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Logo.
 */
class Logo extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'logo';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'type' => 'main',
		];
	}
}
