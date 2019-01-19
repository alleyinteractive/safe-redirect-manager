<?php
/**
 * Routing.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Execute components based on routing.
 *
 * @param array            $data     Data for response.
 * @param \WP_Query        $wp_query WP_Query object corresponding to this
 *                                   request.
 * @param string           $context  The context for this request.
 * @param string           $path     The path for this request.
 * @param \WP_REST_Request $request  WP_REST_Request object.
 * @return  array Data for response.
 */
function build_components_endpoint(
	array $data,
	\WP_Query $wp_query,
	string $context,
	string $path,
	\WP_REST_Request $request
) : array {

	// Build defaults.
	if ( 'site' === $context ) {
		$data['defaults'] = [
			new Component\Slim_Navigation\Slim_Navigation(),
			new Component\Header\Header(),
			new \WP_Component\Body(),
			new Component\Footer\Footer(),
		];
	}

	$template = new Template\Error();

	// Build page.
	switch ( true ) {

		// Landing page.
		case 'landing-page' === $wp_query->get( 'dispatch' ):
			$template = ( new Template\Landing_Page() )->set_post( $wp_query->post );
			break;

		// Article.
		case $wp_query->is_single():
			$template = ( new Template\Article() )->set_post( $wp_query->post );
			break;

		// Pages.
		case $wp_query->is_page():
			$template = ( new Template\Page() )->set_post( $wp_query->post );
			break;
	}

	$data['page'] = $template->to_array()['children'];
	return $data;
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
