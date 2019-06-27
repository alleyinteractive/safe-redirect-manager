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

	use \WP_Components\WP_Query;
	use \WP_Components\Guest_Author;
	use \WP_Components\Author;
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
		// Set the author on this component so we can use it later.
		$this->set_author( $this->query->get( 'author_name' ) );

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
			 * Guest Author Header
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->append_child(
					( new \CPR\Components\Header\Author_Header() )
						->set_author( $this->query->get( 'author_name' ) )
				),

			/**
			 * Column Area
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->append_children( [

					/**
					 * River of posts by this author.
					 */
					( new \CPR\Components\Modules\Content_List() )
						->merge_config(
							[
								'theme'        => 'riverFull',
								'image_size'   => 'grid_item',
								'show_excerpt' => true,
							]
						)
						->parse_from_wp_query( $this->query )
						->set_theme( 'riverFull' )
						->set_child_themes(
							[
								'content-item'  => 'riverFull',
								'content-title' => 'featureSecondary',
								'eyebrow'       => 'small',
							]
						),

					/**
					 * Pagination
					 */
					( new \WP_Components\Pagination() )
						->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
						->set_config( 'base_url', "/author/{$this->query->get( 'author_name' )}/" )
						->set_query( $this->query ),

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
									->set_heading_from_fm_data( $data['author_archive_stories'] ?? [] )
									->parse_from_fm_data( $data['author_archive_stories'] ?? [], 4 ) // $todo Change to the source of the data.
									->set_child_themes(
										[
											'content-list' => 'river',
											'content-item' => 'river',
											'eyebrow'      => 'small',
											'title'        => 'grid',
										]
									),

								/**
								 * First Ad.
								*/
								( new \CPR\Components\Advertising\Ad_Unit() )
									->set_config( 'height', 400 ),

								/**
								 * Second Ad.
								*/
								new \CPR\Components\Advertising\Ad_Unit(),
							]
						),
				] ),
		];
	}

	/**
	 * Modify results.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {
		if ( $wp_query->is_author() && ! empty( $wp_query->get( 'irving-path' ) ) ) {
			$wp_query->set( 'posts_per_page', 16 );
		}
	}
}
