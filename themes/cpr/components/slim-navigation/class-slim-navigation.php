<?php
/**
 * Slim Navigation component.
 *
 * @package CPR
 */

namespace CPR\Components\Slim_Navigation;

/**
 * Slim Navigation.
 */
class Slim_Navigation extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'slim-navigation';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		return [
			new Menu(),
			new Listen_Live(),
			( new \CPR\Components\Social_Links() )
				->parse_from_fm_data(),
			new Search(),
			new \CPR\Components\Donate\Donate_Button(),
		];
	}
}
