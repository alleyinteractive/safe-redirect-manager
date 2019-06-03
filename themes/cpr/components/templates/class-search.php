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
			 * Search results grid.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'one-column' )
				->append_children(
					[
						/**
						 * Search results grid.
						 */
						( new \CPR\Components\Modules\Content_List() )
								->set_config(
									'heading',
									sprintf(
										// Translators: %1$s - search query string.
										__( 'Results For: %1$s', 'cpr' ),
										$this->query->get( 's' )
									)
								)
								->set_config( 'image_size', 'grid_item' )
								->parse_from_wp_query( $this->query )
								->set_theme( 'gridLarge' )
								->set_child_themes(
									[
										'content-item'  => 'grid',
										'content-title' => 'grid',
										'eyebrow'       => 'small',
									]
								),

						/**
						 * Pagination for results.
						 */
						( new \WP_Components\Pagination() )
							->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
							->set_config( 'base_url', '/search/' )
							->set_query( $this->query ),
					]
				),

		];
	}

	/**
	 * Return an array of post types that can be searched for.
	 *
	 * @return array
	 */
	public static function get_searchable_post_type() {
		return [
			'post',
			'page',
			'podcast-post',
			'show-post',
			'podcast-episode',
			'show-episode',
			'show-segment',
			'job',
			'press-release',
			'guest-author',
		];
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
			$wp_query->set( 'post_type', self::get_searchable_post_type() );
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
