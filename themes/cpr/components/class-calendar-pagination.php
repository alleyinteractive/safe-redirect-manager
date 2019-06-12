<?php
/**
 * Pagination component.
 *
 * @package WP_Components
 */

namespace CPR\Components;

/**
 * Pagination.
 */
class Calendar_Pagination extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'calendar-pagination';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'previous_month' => '',
			'next_month'     => '',
		];
	}

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {

		// Set the current month from the query.
		$event_month = $this->query->get( 'eventDate' );

		if ( empty( $event_month ) ) {
			$event_month = ( new \DateTime() )->format( 'Y-m' );
		}

		// Get the base for the calendar URLs.
		$calendar_url = tribe_get_events_link();

		// Get the pagination links.
		$previous_month = ( new \DateTime( $event_month ) )
			->modify( '-1 month' )
			->format( 'Y-m' );

		$next_month = ( new \DateTime( $event_month ) )
			->modify( '+1 month' )
			->format( 'Y-m' );

		$this->set_config( 'previous_month', trailingslashit( $calendar_url ) . $previous_month );
		$this->set_config( 'next_month', trailingslashit( $calendar_url ) . $next_month );

		return $this;
	}
}
