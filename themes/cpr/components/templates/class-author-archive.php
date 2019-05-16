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
		$this->guest_author_has_set( $this->query->get( 'author_name' ) );
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
			 * Guest Author Header
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->append_child(
					( new \CPR\Components\Header\Author_Header() )
						->merge_config(
							[
								'email'       => $this->guest_author->user_email ?? '',
								'name'        => $this->guest_author->display_name ?? '',
								'link'        => get_author_posts_url( $this->guest_author->ID, $this->guest_author->user_nicename ),
								'shortBio'    => get_post_meta( $this->guest_author->ID, 'short_bio', true ),
								'title'       => get_post_meta( $this->guest_author->ID, 'title', true ),
								'twitter'     => get_post_meta( $this->guest_author->ID, 'twitter', true ),
							]
						)
						->guest_author_set_avatar( 'avatar' )
						->append_child(
							( new \WP_Components\HTML() )
								->set_config( 'content', get_post_meta( $this->guest_author->ID, 'description', true ) )
						)
				),

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
						),
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
									->set_heading_from_fm_data( $data['author_archive_stories'] ?? [] )
									->parse_from_fm_data( $data['author_archive_stories'] ?? [], 4 ) // $todo Change to the source of the data.
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
					 * Pagination
					 */
					( new \WP_Components\Pagination() )
						->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
						->set_config( 'base_url', "/author/{$this->query->get( 'author_name' )}/" )
						->set_query( $this->query ),
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
