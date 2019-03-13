<?php
/**
 * Term Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

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
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		// Also set the term.
		$this->set_term( $this->get_queried_object() );
		return $this;
	}

	/**
	 * Hook into term being set.
	 *
	 * @return self
	 */
	public function term_has_set() : self {
		$body           = new \WP_Components\Body();
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
			 * Column Area
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'one-column' )
				->append_children( [

					/**
					 * Content List
					 */
					( new \CPR\Components\Modules\Content_List() )
						->set_config( 'heading', $this->wp_term_get_name() )
						->parse_from_wp_query( $this->query )
						->set_theme( 'gridLarge' )
							->set_child_themes(
								[
									'content-item' => 'gridPrimary',
									'title'        => 'grid',
									'eyebrow'      => 'small',
								]
							),
				] ),

			/**
			 * Pagination.
			 */
			( new \WP_Components\Pagination() )
				->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
				->set_config( 'base_url', "/{$this->wp_term_get_taxonomy()}/{$this->wp_term_get_slug()}/" )
				->set_query( $this->query ),
		];
	}
}
