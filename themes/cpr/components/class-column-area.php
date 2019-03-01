<?php
/**
 * Column_Area component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Column_Area.
 */
class Column_Area extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'column-area';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'theme_name' => 'three-column',
		];
	}
}
