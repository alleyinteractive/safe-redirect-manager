<?php
/**
 * Form Endpoints.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Setup form endpoints and callbacks
 *
 * @param  array $form_endpoints Form endpoint slugs and callback functions.
 * @return array Form endpoints with Thrive forms merged in.
 */
function form_endpoints( $form_endpoints ) {
	array_push(
		$form_endpoints,
		[
			'slug' => 'newsletter',
			'callback' => [ __NAMESPACE__ . '\Component\Modules\Newsletter', 'get_route_response' ],
		]
	);

	return $form_endpoints;
}
add_filter( 'wp_irving_form_endpoints', __NAMESPACE__ . '\form_endpoints', 10, 1 );
