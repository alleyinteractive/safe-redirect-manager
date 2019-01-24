<?php
/**
 * Term Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * Term Archive template.
 */
class Term_Archive extends \WP_Components\Component {

	use \WP_Components\WP_Term;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'term-archive-template';

	/**
	 * Hook into term being set.
	 */
	public function term_has_set() {
		$body = new \WP_Components\Body();
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
