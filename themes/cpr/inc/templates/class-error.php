<?php
/**
 * Error Template.
 *
 * @package Cpr
 */

namespace Cpr\Template;

/**
 * Error routing and components for WP Irving.
 */
class Error {

	/**
	 * Return Irving components for 404 templates.
	 *
	 * @param  array     $data     Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array               Updated response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {

		// Apply 404 status.
		add_filter(
			'wp_irving_components_route_status',
			function( $status ) {
				return 404;
			}
		);

		$data['page'][] = ( new \Cpr\Component\Body() )
			->set_children(
				[
					new \WP_Irving\Component\Component( 'error' ),
				]
			);

		return $data;
	}
}
