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
					->append_children(
						[
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
								->set_post( $this->post ),

							/**
							 * Content List of 4 upcoming events.
							 */
							$this->get_more_events_component(),
						]
					),
			]
		);
	}

	/**
	 * Set meta for the event.
	 */
	public function set_event_meta() : self {
		$this->set_config( 'url', (string) get_post_meta( $this->get_post_id(), '_EventURL', true ) );
		$this->set_config(
			'address',
			trim(
				implode(
					', ',
					array_filter(
						[
							tribe_get_venue( $this->get_post_id() ),
							tribe_get_address( $this->get_post_id() ),
							tribe_get_city( $this->get_post_id() ),
							tribe_get_stateprovince( $this->get_post_id() ) . ' ' . tribe_get_zip( $this->get_post_id() ),
						]
					)
				)
			)
		);

		$start_date = tribe_get_start_date( $this->get_post_id(), false, 'l, F j, Y' );
		$end_date   = tribe_get_end_date( $this->get_post_id(), false, 'l, F j, Y' );

		if ( $start_date === $end_date ) {
			$time = sprintf(
				/* translators: 1. start time of event 2. end time of event */
				esc_html__( '%1$s to %2$s', 'cpr' ),
				tribe_get_start_date( $this->get_post_id(), true, 'g:i A' ),
				tribe_get_end_date( $this->get_post_id(), true, 'g:i A' )
			);
			$this->set_config( 'date', $start_date );
			$this->set_config( 'time', $time );
		} else {
			$date_time = sprintf(
				/* translators: 1. start date/time of event 2. end date/time of event */
				esc_html__( '%1$s to %2$s', 'cpr' ),
				tribe_get_start_date( $this->get_post_id(), true, 'l, F j, Y g:i A' ),
				tribe_get_end_date( $this->get_post_id(), true, 'l, F j, Y g:i A' )
			);
			$this->set_config( 'date_time', $date_time );
		}

		return $this;
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
					'content-list' => 'river',
					'content-item' => 'river',
					'eyebrow'      => 'small',
					'title'        => 'grid',
				]
			);
	}
}
