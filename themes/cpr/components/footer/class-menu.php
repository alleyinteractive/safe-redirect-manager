<?php
/**
 * Footer Menu component.
 *
 * @package CPR
 */

namespace CPR\Components\Footer;

/**
 * Footer Menu.
 */
class Menu extends \WP_Components\Component {

	use \WP_Components\WP_Menu;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer-menu';

	/**
	 * Define a default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'title'           => '',
			'type'            => 'menu',
			'menu_item_class' => '\CPR\Components\Footer\Menu_Item',
		];
	}

	/**
	 * Callback function once menu object has been set.
	 *
	 * @return self
	 */
	public function menu_has_set() : self {
		$this->wp_menu_set_title();
		$this->parse_wp_menu();
		return $this;
	}
}
