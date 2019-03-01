<?php
/**
 * Title component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Title.
 */
class Title extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'title';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'content'    => '',
			'link'       => '',
			'theme_name' => 'grid',
		];
	}
}
