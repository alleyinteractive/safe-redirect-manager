<?php
/**
 * Sidebar component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Sidebar.
 */
class Sidebar extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;
	use \WP_Components\WP_Widget_Sidebar;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'sidebar';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'position' => 'left',
			'has_ad'   => false,
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		return $this->set_sidebar( $this->get_sidebar_slug() );
	}
}
