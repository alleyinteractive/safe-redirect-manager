<?php
/**
 * Slim Navigation Menu component.
 *
 * @package CPR
 */

namespace CPR\Component\Slim_Navigation;

/**
 * Slim Navigation Menu.
 */
class Menu extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'slim-navigation-menu';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() {

		$children = [];

		$children = [
			( new Menu_Item() )
				->set_config( 'label', __( 'News', 'cpr' ) )
				->set_config( 'link', home_url( '/news/' ) ),

			( new Menu_Item() )
				->set_config( 'label', __( 'Classical', 'cpr' ) )
				->set_config( 'link', home_url( '/classical/' ) ),

			( new Menu_Item() )
				->set_config( 'label', __( 'OpenAir', 'cpr' ) )
				->set_config( 'link', home_url( '/openair/' ) ),
		];

		return $children;
	}
}
