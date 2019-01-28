<?php
/**
 * Header Menu Item component.
 *
 * @package CPR
 */

namespace CPR\Component\Header;

/**
 * Header Menu Item.
 */
class Menu_Item extends \WP_Components\Component {

	use \WP_Components\WP_Menu_item;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header-menu-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'id'    => '',
			'label' => '',
			'url'   => '',
		];
	}

	/**
	 * Callback function once menu item has been set.
	 *
	 * @return void
	 */
	public function menu_item_has_set() {
		$this->set_config_from_menu_item();
	}
}
