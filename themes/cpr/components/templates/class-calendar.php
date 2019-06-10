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
				->set_config( 'heading', __( 'Calendar', 'cpr' ) )
				->set_config( 'subheading', $this->get_subheading() )
				->append_children(
					[

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
		return tribe_get_events_title();
	}

	/**
	 * Modify results.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {

		global $wp_query;

		error_log( print_r( $wp_query, true ) );

		// If no args were passed to the constructor, get them from $wp_query.
		$args                 = $wp_query->query;
		$args['post_type']    = \Tribe__Events__Main::POSTTYPE;
		$args['eventDisplay'] = 'month';
		$args['eventDate']    = $wp_query->get( 'eventDate' );
		$args['post_status']  = [ 'publish' ];

		if ( ! empty( $wp_query->query_vars['meta_query'] ) ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$args['meta_query'] = $wp_query->query_vars['meta_query'];
		}

		// Hijack the main query to load the events via provided $args.
		if (
			'tribe_events' === $wp_query->get( 'post_type' ) &&
			$wp_query->is_archive() &&
			! empty( $wp_query->get( 'irving-path' ) )
		) {
			error_log( print_r( $args, true ) );
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
			// $wp_query = \Tribe__Events__Main::tribe_get_events( $args, true );
		}

		// if (
		// 	'tribe_events' === $wp_query->get( 'post_type' ) &&
		// 	$wp_query->is_archive() &&
		// 	! empty( $wp_query->get( 'irving-path' ) )
		// ) {
		// 	$wp_query->set( 'orderby', 'meta_value_num' );
		// 	$wp_query->set( 'meta_key', '_EventStartDate' );
		// 	$wp_query->set( 'order', 'ASC' );
		// 	error_log( print_r( $wp_query, true ) );
		// }
	}
}
