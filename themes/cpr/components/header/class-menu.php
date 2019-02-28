<?php
/**
 * Header Menu component.
 *
 * @package CPR
 */

namespace CPR\Components\Header;

/**
 * Header Menu.
 */
class Menu extends \WP_Components\Component {

	use \WP_Components\WP_Menu;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header-menu';

	/**
	 * Default config is the sections.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'type'            => 'menu',
			'menu_item_class' => '\CPR\Components\Header\Menu_Item',
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
