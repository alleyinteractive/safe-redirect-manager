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

		// Get the section, if set.
		if ( 'section' === $this->query->get( 'taxonomy' ) && $this->query->get( 'term' ) ) {
			$section_slug = $this->query->get( 'term' );
		}

		// Get the category, if set.
		$category_slug = $this->query->get( 'tribe_events_cat' );

		// Create the base for the calendar URLs.
		$calendar_base_path = \Tribe__Settings_Manager::get_option( 'eventsSlug', 'events' );

		$calendar_url = '/';

		if ( ! empty( $section_slug ) ) {
			$calendar_url = trailingslashit( $calendar_url . $section_slug . '/' );
		}

		$calendar_url = trailingslashit( $calendar_url . $calendar_base_path );

		if ( ! empty( $category_slug ) ) {
			$calendar_url = trailingslashit( $calendar_url . 'category/' . $category_slug );
		}

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
