<?php
/**
 * Eyebrow component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Eyebrow.
 */
class Eyebrow extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'eyebrow';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'eyebrow_label' => '',
			'eyebrow_link'  => '',
			'theme_name'    => 'small',
		];
	}
}
