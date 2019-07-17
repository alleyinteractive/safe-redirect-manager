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
				->set_theme( 'calendar' )
				->set_config( 'heading', $this->get_heading() )
				->set_config( 'subheading', $this->get_subheading() )
				->append_children(
					[
						/**
						 * Monthly Pagination.
						 */
						( new \CPR\Components\Calendar_Pagination() )
							->set_query( $this->query ),

						/**
						 * Calendar events archive.
						 */
						( new \CPR\Components\Widgets\Content_List() )
							->set_config( 'show_excerpt', false )
							->parse_from_wp_query( $this->query )
							->set_child_themes(
								[
									'content-title' => 'grid',
								]
							),

						/**
						 * Pagination
						 */
						( new \WP_Components\Pagination() )
							->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
							->set_config( 'base_url', $this->get_pagination_base_url() )
							->set_query( $this->query ),

						/**
						 * Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->append_children(
								[
									/**
									 * First Ad.
									*/
									( new \CPR\Components\Advertising\Ad_Unit() )
										->set_config( 'height', 400 ),

									/**
									 * Second Ad.
									*/
									new \CPR\Components\Advertising\Ad_Unit(),
								]
							),
					]
				),
		];
	}

	/**
	 * Get pagination base url.
	 *
	 * @return string
	 */
	public function get_pagination_base_url() : string {
		$event_month = $this->query->get( 'eventDate' );

		if ( empty( $event_month ) ) {
			$event_month = ( new \DateTime() )->format( 'Y-m' );
		}

		return "/{$this->query->get( 'term' )}/calendar/{$event_month}/";
	}

	/**
	 * Returns a string with the title for the calendar that we are looking at.
	 *
	 * @return string
	 */
	public function get_heading() : string {

		$heading = '';

		// Add in the section name, if set.
		if ( 'section' === $this->query->get( 'taxonomy' ) && $this->query->get( 'term' ) ) {
			$section_slug = $this->query->get( 'term' );

			if ( ! empty( $section_slug ) ) {
				$section = get_term_by( 'slug', $section_slug, 'section' );
			}

			if ( $section ) {
				$heading .= $section->name . ': ';
			}
		}

		$heading .= __( 'Events Calendar', 'cpr' );

		return $heading;
	}

	/**
	 * Returns a string with the month and year that we are looking at.
	 *
	 * @return string
	 */
	public function get_subheading() : string {

		$event_month = new \DateTime( $this->query->get( 'eventDate' ) );
		$subheading  = date_i18n( 'F Y', $event_month->format( 'U' ) );

		// Add in the category name, if set.
		$category_slug = $this->query->get( 'tribe_events_cat' );

		$category = null;
		if ( ! empty( $category_slug ) ) {
			$category = get_term_by( 'slug', $category_slug, 'tribe_events_cat' );
		}

		if ( ! empty( $category ) ) {
			$subheading .= ': ' . $category->name;
		}

		return $subheading;
	}

	/**
	 * Modify results.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 */
	public static function pre_get_posts( \WP_Query $wp_query ) {
		// Only modify events archives when on either the base calendar
		// page or the monthly view.
		if (
			'tribe_events' !== $wp_query->get( 'post_type' )
			|| ! $wp_query->is_archive()
			|| empty( $wp_query->get( 'irving-path' ) )
		) {
			return;
		}

		// Set 20 events per page.
		$wp_query->set( 'posts_per_page', 20 );

		// This removes legacy events.
		$wp_query->set( 'start_date', tribe_beginning_of_day( date_i18n( \Tribe__Date_Utils::DBDATETIMEFORMAT ) ) );

		// Ordering the events based on its start date and event base.
		$wp_query->set( 'orderby', 'meta_value_num' );
		$wp_query->set( 'meta_key', '_EventStartDate' );
		$wp_query->set( 'order', 'ASC' );
		$wp_query->set( 'meta_query', self::events_date_meta_args( $wp_query->get( 'eventDate' ) ) );
	}

	/**
	 * Get date meta query.
	 *
	 * @param string $event_month Event Month.
	 * @return array
	 */
	public static function events_date_meta_args( string $event_month = '' ) : array {

		if ( empty( $event_month ) ) {
			$event_month = ( new \DateTime() )->format( 'Y-m' );
		}

		// First of month.
		$start_date = new \DateTime( $event_month );

		// Last of month.
		$end_date = new \DateTime( $event_month );
		$end_date->modify( 'last day of this month' );

		return [
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
		];
	}

	/**
	 * Get date meta query.
	 *
	 * @param string $section Section slug.
	 * @return array
	 */
	public static function get_events_args_for_widgets( string $section = '' ) : array {
		$event_month = ( new \DateTime() )->format( 'Y-m' );
		$start_date  = new \DateTime( $event_month );

		$args = [
			'post_type'  => [ 'tribe_events' ],
			'orderby'    => 'meta_value_num',
			'meta_key'   => '_EventStartDate', // phpcs:ignore
			'order'      => 'ASC',
			'meta_query' => [ // phpcs:ignore
				[
					'ends-after'    => [ // phpcs:ignore
						'key'     => '_EventEndDateUTC',
						'compare' => '>',
						'value'   => $start_date->format( \Tribe__Date_Utils::DBDATETIMEFORMAT ),
						'type'    => 'DATETIME',
					],
				],
			],
		];

		if ( ! empty( $section ) ) {
			$args['tax_query'] = [ // phpcs:ignore
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => $section,
				],
			];
		}

		return $args;
	}
}
