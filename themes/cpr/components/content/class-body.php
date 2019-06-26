<?php
/**
 * Content Body component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Body class.
 */
class Body extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-body';

	/**
	 * Fires after the post object has been set on this class.
	 */
	public function post_has_set() : self {

		// Ensure this post isn't used in the backfill.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $this->get_post_id() );

		$this->set_featured_media();

		return $this->append_children(
			[
				( new \WP_Components\Gutenberg_Content() )->set_post( $this->post ),
				$this->get_sidebar_component(),
				// ( new Keep_Reading() )->set_post( $this->post ),
				// ( new Related_Tags() )->set_post( $this->post ),
				// ( new Bylines() )->set_post( $this->post ),
				new \CPR\Components\Donate\Donate_CTA(),
				// ( new Comments() )->set_post( $this->post ),
			]
		);
	}

	/**
	 * Set the featured media.
	 *
	 * @return self
	 */
	public function set_featured_media() : self {

		$type = (string) get_post_meta( $this->get_post_id(), 'featured_media_type', true );

		switch ( $type ) {
			case 'image':
			default:
				if ( has_post_thumbnail( $this->get_post_id() ) ) {
					$this->wp_post_set_featured_image( 'content_single', [ 'show_caption' => true ] );
				}
				break;
			case 'video':
				$video_url = get_post_meta( $this->get_post_id(), 'video_url', true );
				if ( empty( $video_url ) ) {
					break;
				}
	
				$markup = wp_oembed_get( $video_url );
				if ( false === $markup ) {
					break;
				}
	
				$this->append_child(
					( new \WP_Components\HTML() )
						->set_config( 'content', $markup )
						->set_name( 'featured-video' )
				);
				break;
			case 'none':
				break;
		}

		return $this;
	}

	/**
	 * Get the widget sidebar based on primary section, or fall
	 * back to a default sidebar.
	 *
	 * @return \CPR\Components\Sidebar
	 */
	public function get_sidebar_component() : \CPR\Components\Sidebar {
		return ( new \CPR\Components\Sidebar() )
				->set_config( 'position', 'right' )
				->set_config( 'has_ad', true )
				->set_sidebar( $this->get_sidebar_slug() )
				->prepend_child(
					( new \CPR\Components\Advertising\Ad_Unit() )
						->configure_ad_slot( 'CPR3-Article-300x250' )
				);
	}

	/**
	 * Return a Content List component to be used in the sidebar as
	 * `More in {$primary_category}`.
	 *
	 * @return \CPR\Components\Modules\Content_List
	 */
	public function get_more_articles_sidebar_component() {

		// Get primary category component.
		$category_component = $this->get_primary_category_component();
		if ( ! $category_component->is_valid_term() ) {
			return;
		}

		return ( new \CPR\Components\Modules\Content_List() )
			->set_config(
				'heading',
				sprintf(
					// Translators: %1$s, term name.
					esc_html__( 'More in %1$s', 'cpr' ),
					esc_html( $category_component->wp_term_get_name() )
				)
			)
			->parse_from_ids(
				[],
				3,
				[
					'tax_query' => [
						[
							'taxonomy' => $category_component->wp_term_get_taxonomy(),
							'field'    => 'term_id',
							'terms'    => $category_component->wp_term_get_id(),
						],
					],
				]
			)
			->set_theme( 'river' )
			->set_child_themes(
				[
					'content-list'  => 'river',
					'content-item'  => 'river',
					'eyebrow'       => 'small',
					'content-title' => 'grid',
				]
			);
	}

	/**
	 * Return a Content List component to be used in the sidebar as
	 * `Most Recent`.
	 *
	 * @return \CPR\Components\Modules\Content_List
	 */
	public function get_recent_articles_sidebar_component() {
		return ( new \CPR\Components\Modules\Content_List() )
			->set_config( 'heading', __( 'Most Recent', 'cpr' ) )
			->parse_from_wp_query(
				new \Alleypack\Unique_WP_Query(
					[
						'posts_per_page' => 3,
					]
				)
			)
			->set_theme( 'river' )
			->set_child_themes(
				[
					'content-list'  => 'river',
					'content-item'  => 'river',
					'eyebrow'       => 'small',
					'content-title' => 'grid',
				]
			);
	}
}
