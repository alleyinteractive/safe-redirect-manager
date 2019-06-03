<?php
/**
 * Events Widget
 *
 * @package CPR
 */

namespace CPR;

if ( ! class_exists( '\FM_Widget' ) ) {
	return;
}

/**
 * Class for Events widget.
 */
class Events_Widget extends \FM_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'events_widget',
			__( 'CPR Upcoming Events', 'cpr' ),
			[
				'description' => __( 'CPR upcoming events.', 'cpr' ),
			]
		);
	}

	/**
	 * Create a component from widget instance.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 * @return null|\CPR\Components\Widgets\Content_List()
	 */
	public function create_component( $args, $instance ) : ?\CPR\Components\Widgets\Content_List {

		$backfill_args = [
			'post_type' => 'tribe_events',
		];

		if ( ! empty( $instance['backfill_source'] ) ) {
			$backfill_args['tax_query'] = [
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => $instance['backfill_source'],
				],
			];
		}

		return ( new \CPR\Components\Widgets\Content_List() )
			->set_config( 'header_text', $instance['heading'] ?? __( 'Upcoming Events', 'cpr' ) )
			->set_config( 'header_link', $instance['heading_link'] ?? '' )
			->parse_from_post_ids(
				$instance['event_ids'] ?? [],
				4,
				$backfill_args
			);
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {
		return [
			'heading'         => new \Fieldmanager_TextField(
				[
					'label'         => __( 'Heading', 'cpr' ),
					'default_value' => 'Upcoming Events',
				]
			),
			'heading_link'    => new \Fieldmanager_Textfield(
				[
					'label' => __( 'Heading Link', 'cpr' ),
				]
			),
			'event_ids'       => new \Fieldmanager_Zone_Field(
				[
					'label'      => __( 'Events', 'cpr' ),
					'post_limit' => 4,
					'query_args' => [
						'post_type' => [ 'tribe_events' ],
					],
				]
			),
			'backfill_source' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Events backfill source', 'cpr' ),
					'options' => [
						''          => __( 'All events', 'cpr' ),
						'classical' => __( 'Classical events', 'cpr' ),
						'indie'     => __( 'Indie events', 'cpr' ),
						'news'      => __( 'News events', 'cpr' ),
					],
				]
			),
		];
	}
}
