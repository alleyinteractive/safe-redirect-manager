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
			( new \CPR\Components\Column_Area() )
				->set_theme( 'one-column' )
				->append_children( [
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
			$this->get_pagination_component(),
		];
	}

	/**
	 * Get the pagination component.
	 *
	 * @return \WP_Components\Pagination
	 */
	public function get_pagination_component() : \WP_Components\Pagination {
		// Create instance.
		$pagination = new \WP_Components\Pagination();

		// Flag irving parameters to remove.
		$pagination->set_config( 'url_params_to_remove', [ 'path', 'context' ] );

		// Set the base URL for search.
		$pagination->set_config( 'base_url', "/{$this->wp_term_get_taxonomy()}/{$this->wp_term_get_slug()}/" );

		// Apply to the current query.
		$pagination->set_query( $this->query );

		// Figure out the term archive meta info.
		$posts_per_page = 16;
		$page           = absint( $this->query->get( 'paged' ) );
		if ( $page < 1 ) {
			$page = 1;
		}

		$pagination->set_config( 'range_end', $page * $posts_per_page );
		$pagination->set_config(
			'range_start',
			( $pagination->get_config( 'range_end' ) - $posts_per_page + 1 )
		);

		$pagination->set_config( 'total', absint( $this->query->found_posts ?? 0 ) );

		return $pagination;
	}
}
