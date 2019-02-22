<?php
/**
 * Content Body component.
 *
 * @package CPR
 */

namespace CPR\Component\Content;

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
	public function post_has_set() {

		// Ensure this post isn't used in the backfill.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $this->get_post_id() );

		$this->append_children(
			[
				( new \WP_Components\Gutenberg_Content() )->set_post( $this->post ),
				( new \CPR\Component\Sidebar() )
					->set_config( 'has_ad', true )
					->set_config( 'position', 'right' )

					/**
					 * Content List of 3 articles tagged with the same primary category.
					 */
					->append_child( $this->get_more_articles_sidebar_component() )

					/**
					 * Ad
					 */
					->append_child(
						/**
						 * Advertisement.
						 */
						new \CPR\Component\Ad()
					)

					/**
					 * Content List of 3 most recent articles.
					 */
					->append_child( $this->get_recent_articles_sidebar_component() ),
			]
		);
	}

	/**
	 * Return a Content List component to be used in the sidebar as
	 * `More in {$primary_category}`.
	 *
	 * @return \CPR\Component\Modules\Content_List
	 */
	public function get_more_articles_sidebar_component() {

		// Get primary category component.
		$category_component = $this->get_primary_category_component();

		return ( new \CPR\Component\Modules\Content_List() )
			->set_config(
				'heading',
				sprintf(
					// Translators: %1$s, term name.
					esc_html__( 'More in %1$s', 'cpr' ),
					esc_html( $category_component->wp_term_get_name() )
				)
			)
			->set_config( 'theme', 'river' )
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
			);
	}

	/**
	 * Return a Content List component to be used in the sidebar as
	 * `Most Recent`.
	 *
	 * @return \CPR\Component\Modules\Content_List
	 */
	public function get_recent_articles_sidebar_component() {
		return ( new \CPR\Component\Modules\Content_List() )
			->set_config( 'heading', __( 'Most Recent', 'cpr' ) )
			->set_config( 'theme', 'river' )
			->parse_from_wp_query(
				new \Alleypack\Unique_WP_Query(
					[
						'posts_per_page' => 3,
					]
				)
			);
	}
}
