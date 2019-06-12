<?php
/**
 * Event Body component.
 *
 * @package CPR
 */

namespace CPR\Components\Events;

/**
 * Event Body class.
 */
class Body extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \CPR\Event;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'event-body';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'address'   => '',
			'date'      => '',
			'date_time' => '',
			'time'      => '',
			'title'     => '',
			'url'       => '',
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 */
	public function post_has_set() : self {

		// Ensure this post isn't used in the backfill.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $this->get_post_id() );

		$this->wp_post_set_title();
		$this->set_event_meta();

		return $this->append_children(
			[
				( new \WP_Components\HTML() )
					// phpcs:ignore
					->set_config( 'content', apply_filters( 'the_content', $this->post->post_content ?? '' ) ),

				( new \WP_Components\HTML() )
					->set_name( 'map' )
					->set_config( 'content', tribe_get_embedded_map( $this->get_post_id(), null, null, true ) ),

				( new \CPR\Components\Sidebar() )
					->set_config( 'position', 'right' )
					->set_post( $this->post )
					->prepend_child(
						/**
						 * Social sharing.
						 */
						( new \WP_Components\Social_Sharing() )
							->merge_config(
								[
									'services' => [
										'facebook' => true,
										'twitter'  => true,
										'email'    => true,
									],
									'text'     => __( 'Share: ', 'cpr' ),
								]
							)
							->set_post( $this->post )
					),
			]
		);
	}

	/**
	 * Return a Content List component to be used in the sidebar as
	 * `More Events`.
	 *
	 * @todo This is more or less a placeholder. Fix up later.
	 *
	 * @return \CPR\Components\Modules\Content_List
	 */
	public function get_more_events_component() {
		return ( new \CPR\Components\Modules\Content_List() )
			->set_config( 'heading', __( 'More Events', 'cpr' ) )
			->parse_from_wp_query(
				new \Alleypack\Unique_WP_Query(
					[
						'posts_per_page' => 4,
						'post_type'      => 'tribe_events',
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
