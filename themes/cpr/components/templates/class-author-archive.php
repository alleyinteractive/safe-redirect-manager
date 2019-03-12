<?php
/**
 * Author Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Author Archive template.
 */
class Author_Archive extends \WP_Components\Component {

	use \WP_Components\Guest_Author;
	use \WP_Components\Author;
	use \WP_Components\WP_Query;
	use \WP_Components\WP_User;
	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'author-archive-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		$this->set_author( $this->query->get( 'author_name' ) );
		return $this;
	}

	/**
	 * Hook into author being set.
	 *
	 * @return self
	 */
	public function author_has_set() : self {
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
				->set_theme( 'twoColumn' )
				->merge_config(
					[
						'heading' => sprintf(
							// Translators: %1$s, author archive heading name.
							esc_html__( 'Stories by %1$s', 'cpr' ),
							esc_html( $this->get_author_display_name() )
						)
					]
				)
				->append_children( [

					/**
					 * Content List
					 */
					( new \CPR\Components\Modules\Content_List() )
						->set_config( 'image_size', 'grid_item' )
						->set_config( 'show_excerpt', true )
						
						->parse_from_wp_query( $this->query )
						->set_theme( 'river_full' )
							->set_child_themes(
								[
									'content-item' => 'river',
									'title'        => 'grid',
								]
							),

					/**
					 * Content sidebar.
					 */
					( new \CPR\Components\Sidebar() )
						->set_theme( 'right' )
						->append_children(
							[
						
								/**
								 * Grid of additional items.
								 */
								( new \CPR\Components\Modules\Content_List() )
									->set_config( 'eyebrow_label', __( 'Across Colorado', 'cpr' ) ) // $todo Change to the source of the data.
									->set_heading_from_fm_data( $data['author_arhive_stories'] ?? [] )
									->parse_from_fm_data( $data['author_arhive_stories'] ?? [], 4 ) // $todo Change to the source of the data.
									->set_child_themes(
										[
											'content-list'         => 'river',
											'content-item'         => 'river',
											'eyebrow'              => 'small',
											'title'                => 'grid',
										]
									),

								/**
								 * First Ad.
								*/
								( new \CPR\Components\Ad() )
									->set_config( 'height', 400 ),

								/**
								 * Second Ad.
								*/
								new \CPR\Components\Ad(),
							]
						),

					/**
			 		* Pagination.
			 		*/
					$this->get_pagination_component(),
				] ),
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
		$pagination->set_config( 'base_url', "/author/{$this->query->get( 'author_name' )}/" );

		// Apply to the current query.
		$pagination->set_query( $this->query );

		// Figure out the term archive meta info.
		$posts_per_page = absint( $this->query->get( 'posts_per_page' ) );
		$page = absint( $this->query->get( 'paged' ) );
		if ( $page < 1 ) {
			$page = 1;
		}

		$pagination->set_config( 'range_end', $page * $posts_per_page );
		$pagination->set_config(
			'range_start',
			absint( $pagination->get_config( 'range_end' ) - $posts_per_page + 1 )
		);
		$pagination->set_config( 'total', absint( $this->query->found_posts ?? 0 ) );

		// Ensure the range isn't larger than the total.
		if ( $pagination->get_config( 'range_end' ) > $pagination->get_config( 'total' ) ) {
			$pagination->set_config( 'range_end', absint( $pagination->get_config( 'total' ) ) );
		}

		return $pagination;
	}

	/**
	 * Modify results.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {
		if ( $wp_query->is_author() && ! empty( $wp_query->get( 'irving-path' ) ) ) {
			$wp_query->set( 'posts_per_page', 7 );
		}
	}
}
