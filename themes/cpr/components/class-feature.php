<?php
/**
 * Feature component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Feature.
 */
class Feature extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'feature';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'theme' => 'three-column',
		];
	}
}
