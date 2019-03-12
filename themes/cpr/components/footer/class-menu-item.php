<?php
/**
 * Footer Menu Item component.
 *
 * @package CPR
 */

namespace CPR\Components\Footer;

/**
 * Footer Menu Item.
 */
class Menu_Item extends \WP_Components\Component {

	use \WP_Components\WP_Menu_item;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer-menu-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'id'    => '',
			'label' => '',
			'url'   => '',
		];
	}

	/**
	 * Callback function once menu item has been set.
	 *
	 * @return self
	 */
	public function menu_item_has_set() : self {
		$this->set_config_from_menu_item();
		return $this;
	}
}
