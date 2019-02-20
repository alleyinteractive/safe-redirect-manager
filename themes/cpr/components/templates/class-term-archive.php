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

	use \WP_Components\WP_Query;
	use \WP_Components\WP_Term;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'term-archive-template';

	/**
	 * Hook into query being set.
	 */
	public function query_has_set() {
		// Also set the term.
		$this->set_term( $this->get_queried_object() );
	}

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
		return [
			/**
			 * "More Stories" river.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'layout', 'river' )
				->set_config( 'image_size', 'grid_item' )
				->set_config( 'show_excerpt', true )
				->set_config( 'heading', $this->term->name )
				->set_config( 'heading_border', true )
				->parse_from_wp_query( $this->query ),
				// ->append_child(
					/**
					 * Pagination.
					 *
					 * @todo Implement.
					 */
				// ),
		];
	}
}
