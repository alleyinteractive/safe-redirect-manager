<?php
/**
 * Calendar Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Calendar template.
 */
class Calendar extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'calendar-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
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
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->set_config( 'heading', __( 'Events Calendar', 'cpr' ) )
				->set_config( 'subheading', $this->get_subheading() )
				->append_children(
					[

						/**
						 * Pagination.
						 */
						( new \CPR\Components\Calendar_Pagination() )
							->set_query( $this->query ),

						/**
						 * Calendar events archive.
						 */
						( new \CPR\Components\Modules\Content_List() )
							->set_theme( 'calendarEvent' )
							->set_config( 'show_excerpt', false )
							->parse_from_wp_query( $this->query )
							->set_child_themes(
								[
									'content-item'  => 'event',
									'content-title' => 'grid',
								]
							),

						/**
						 * Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' ),
					]
				),
		];
	}

	/**
	 * Returns a string with the month and year that we are looking at.
	 *
	 * @return string
	 */
	public function get_subheading() {
		$event_month = new \DateTime( $this->query->get( 'eventDate' ) );

		return date_i18n( 'F Y', $event_month->format( 'U' ) );
	}

	/**
	 * Modify results.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {
		if (
			'tribe_events' !== $wp_query->get( 'post_type' ) ||
			! $wp_query->is_archive() ||
			empty( $wp_query->get( 'irving-path' ) )
		) {
			return;
		}

		$wp_query->set( 'orderby', 'meta_value_num' );
		$wp_query->set( 'meta_key', '_EventStartDate' );
		$wp_query->set( 'order', 'ASC' );

		// Check the month that we're looking at.
		$event_month = $wp_query->get( 'eventDate' );

		if ( empty( $event_month ) ) {
			$event_month = ( new \DateTime() )->format( 'Y-m' );
		}

		// First of month.
		$start_date = new \DateTime( $event_month );

		// Last of month.
		$end_date = new \DateTime( $event_month );
		$end_date->modify( 'last day of this month' );

		// Find events that end any time after the start of the month,
		// and start any time before the end of the month.
		$wp_query->set(
			'meta_query',
			[
				'ends-after'    => [
					'key'     => '_EventEndDateUTC',
					'compare' => '>',
					'value'   => $start_date->format( \Tribe__Date_Utils::DBDATETIMEFORMAT ),
					'type'    => 'DATETIME',
				],
				'starts-before' => [
					'key'     => '_EventStartDateUTC',
					'compare' => '<',
					'value'   => $end_date->format( \Tribe__Date_Utils::DBDATETIMEFORMAT ),
					'type'    => 'DATETIME',
				],
			]
		);
	}
}
