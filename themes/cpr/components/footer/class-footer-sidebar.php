<?php
/**
 * Footer Sidebar Component.
 *
 * @package CPR
 */

namespace CPR\Components\Footer;

/**
 * Footer Sidebar.
 */
class Footer_Sidebar extends \WP_Components\Component {

	use \WP_Components\WP_Widget_Sidebar;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer-sidebar';
}
