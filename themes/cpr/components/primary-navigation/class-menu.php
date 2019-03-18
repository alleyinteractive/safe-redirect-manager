<?php
/**
 * Primary Navigation Menu component.
 *
 * @package CPR
 */

namespace CPR\Components\Primary_Navigation;

/**
 * Primary Navigation Menu.
 */
class Menu extends \WP_Components\Component {

	use \WP_Components\WP_Menu;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'primary-navigation-menu';

	/**
	 * Define a default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'type'            => 'menu',
			'menu_item_class' => '\CPR\Components\Primary_Navigation\Menu_Item',
		];
	}

	/**
	 * Callback function once menu object has been set.
	 *
	 * @return self
	 */
	public function menu_has_set() : self {
		$this->parse_wp_menu();
		return $this;
	}
}
