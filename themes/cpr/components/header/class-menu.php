<?php
/**
 * Header Menu component.
 *
 * @package CPR
 */

namespace CPR\Component\Header;

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
	public function default_config() {
		return [
			'type'            => 'menu',
			'menu_item_class' => '\CPR\Component\Header\Menu_Item',
		];
	}

	public function menu_has_set() {
		$this->parse_wp_menu();
	}
}
