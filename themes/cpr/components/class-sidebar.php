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
		$sections = wp_get_post_terms( $this->get_post_id(), 'section' );
		if ( ! empty( $sections[0] ) && $sections[0] instanceof \WP_Term ) {
			$sidebar_slug = "{$sections[0]->slug}-sidebar";
			$this->set_sidebar( $sidebar_slug );
		}
		return $this;
	}
}
