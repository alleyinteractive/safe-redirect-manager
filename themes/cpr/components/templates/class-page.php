<?php
/**
 * Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * Page template.
 */
class Page extends \WP_Component\Component {

	use \WP_Component\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'page-template';

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		$body = new \WP_Component\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		return [];
	}
}
