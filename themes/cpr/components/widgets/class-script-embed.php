<?php
/**
 * Widget Script Embed component.
 *
 * @package CPR
 */

namespace CPR\Components\Widgets;

/**
 * Widget Content List.
 */
class Script_Embed extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'widget-script-embed';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'script_url' => '',
		];
	}
}
