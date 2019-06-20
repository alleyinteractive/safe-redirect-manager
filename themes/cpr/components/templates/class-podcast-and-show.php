<?php
/**
 * Podcast and Show Single Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Podcast and Show template.
 */
class Podcast_And_Show extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \WP_Components\WP_Term;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'podcast-and-show-template';

	/**
	 * Hook into the term being set, and then load the linked post. This gives
	 * us access to both the term and post easily.
	 *
	 * @return self
	 */
	public function term_has_set() : self {
		// This is a Term_Post_Link, so get the linked post (since all the meta
		// is there).
		$post_id = absint( \Alleypack\Term_Post_Link::get_post_from_term( $this->term->term_id ) );
		return $this->set_post( $post_id );
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
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
		$data = get_post_meta( $this->get_post_id(), 'settings', true );
		return [
			/**
			 * Header.
			 */
			( new \CPR\Components\Podcast_And_Show\Header() )->set_post( $this->post ),

			/**
			 * Highlighted episodes.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->parse_from_fm_data(
					(array) get_post_meta( $this->get_post_id(), 'highlighted_episodes', true ),
					4,
					[
						'post_type' => [
							'podcast-episode',
							'show-episode',
						],
						'tax_query' => [
							[
								'taxonomy' => $this->term->taxonomy,
								'field'    => 'slug',
								'terms'    => $this->term->slug,
							],
						],
					]
				)
				->set_config( 'heading', __( 'Highlighted Episodes', 'cpr' ) )
				->set_theme( 'gridCentered' )
				->set_child_themes(
					[
						'content-item'  => 'grid',
						'content-title' => 'grid',
						'eyebrow'       => 'large',
					]
				),


			/**
			 * "More Stories" river.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->set_config( 'heading', __( 'Latest Episodes', 'cpr' ) )
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'theme'                => 'riverFull',
									'image_size'           => 'grid_item',
									'show_excerpt'         => true,
								]
							)
							->parse_from_ids(
								[],
								8,
								[
									'post_type' => [
										'podcast-episode',
										'show-episode',
									],
									'tax_query' => [
										[
											'taxonomy' => $this->term->taxonomy,
											'field'    => 'slug',
											'terms'    => $this->term->slug,
										],
									],
								]
							)
							->set_theme( 'riverFull' )
							->set_child_themes(
								[
									'content-item'  => 'riverFull',
									'content-title' => 'featureSecondary',
									'eyebrow'       => 'small',
								]
							),

						/**
						 * Right Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->append_children(
								[
									/**
									 * Advertisement.
									 */
									new \CPR\Components\Advertising\Ad_Unit(),
								]
							),
					]
				),

			/**
			 * Hosts.
			 */
			( new \CPR\Components\Modules\Grid_Group() )
				->parse_from_fm_data( (array) get_post_meta( $this->get_post_id(), 'hosts', true ) )
				->children_callback(
					function( $child ) {
						return $child->set_config( 'show_name', $this->wp_post_get_title() );
					}
				),

			/**
			 * Related Podcasts.
			 */
			( new \CPR\Components\Modules\Grid_Group() )
				->parse_from_fm_data( (array) get_post_meta( $this->get_post_id(), 'related_podcasts', true ) ),
		];
	}

	/**
	 * Get an array of query args to be used on the highlighted episode
	 * zoninator to limit results to episodes for the podcast/show currently
	 * being edited.
	 *
	 * @return array
	 */
	public static function get_highlighted_episodes_query_args() : array {

		if ( ! is_admin() ) {
			return [];
		}

		$args = [
			'post_type' => [
				'podcast-episode',
				'show-episode',
			],
		];

		// Filter results by the linked term.
		$post_id = ! empty( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
		$term_id = absint( get_post_meta( $post_id, '_linked_term_id', true ) );
		$term    = get_term_by( 'id', $term_id, str_replace( '-post', '', get_post_type( $post_id ) ) );

		if ( ! $term instanceof \WP_Term ) {
			return $args;
		}

		$args['tax_query'] = [
			[
				'taxonomy' => $term->taxonomy,
				'field'    => 'slug',
				'terms'    => $term->slug,
			],
		];

		return $args;
	}
}
