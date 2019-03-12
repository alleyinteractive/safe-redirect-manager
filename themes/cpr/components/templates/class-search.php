<?php
/**
 * Search Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Search template.
 */
class Search extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'search-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
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
			 * Search bar.
			 */
			( new \CPR\Components\Search\Search_Bar() )->set_config( 's', $this->query->get( 's' ) ),

			/**
			 * Search results grid.
			 */
			( new \CPR\Components\Column_Area() )
				->set_config( 'heading', __( 'Search Results for:', 'cpr' ) )
				->set_theme( 'one-column' )
				->append_children( [
					( new \CPR\Components\Modules\Content_List() )
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
			 * Pagination for results.
			 */
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
		$pagination->set_config( 'base_url', '/search/' );

		// Apply to the current query.
		$pagination->set_query( $this->query );

		// Figure out the search result meta info.
		$posts_per_page = absint( $this->query->get( 'posts_per_page' ) );
		$page = absint( $this->query->get( 'paged' ) );
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

	/**
	 * Modify results.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {
		if (
			$wp_query->is_search()
			&& ! empty( $wp_query->get( 'irving-path' ) )
		) {
			$wp_query->set( 'posts_per_page', 16 );
		}
	}

	/**
	 * Modify rewrite rules.
	 */
	public static function rewrite_rules() {
		add_rewrite_rule( '^search/?$', 'index.php?s=', 'top' );
		add_rewrite_rule( '^search/page/?([0-9]{1,})/?$', 'index.php?s=&paged=$matches[1]', 'top' );
	}
}
