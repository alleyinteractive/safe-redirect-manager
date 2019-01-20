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

	// Build page.
	switch ( true ) {

		/**
		 * Search results.
		 */
		case $wp_query->is_search():
			$template = ( new Component\Templates\Search() )->set_query( $wp_query );
			break;

		/**
		 * Landing Pages.
		 */
		case 'landing-page' === $wp_query->get( 'dispatch' ):
			switch ( $wp_query->get( 'landing-page-type' ) ) {
				case 'homepage':
					$template = ( new Component\Templates\Homepage() )->set_post( $wp_query->post );
					break;

				case 'news':
					$template = ( new Component\Templates\News() )->set_post( $wp_query->post );
					break;

				case 'classical':
					$template = ( new Component\Templates\Classical() )->set_post( $wp_query->post );
					break;

				case 'openair':
					$template = ( new Component\Templates\Openair() )->set_post( $wp_query->post );
					break;
			}
			break;


		/**
		 * Author archive.
		 */
		case $wp_query->is_author():
			$template = ( new Component\Templates\Author_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Term archives.
		 */
		case $wp_query->is_tax():
		case $wp_query->is_tag():
		case $wp_query->is_category():
			$template = ( new Component\Templates\Term_Archive() )->set_post( $wp_query->post );
			break;

		/**
		 * Article.
		 */
		case $wp_query->is_single():
			$template = ( new Component\Templates\Article() )->set_post( $wp_query->post );
			break;

		/**
		 * Page.
		 */
		case $wp_query->is_page():
			$template = ( new Component\Templates\Page() )->set_post( $wp_query->post );
			break;

		/**
		 * Error page.
		 */
		case $wp_query->is_404():
		default:
			$template = ( new Component\Templates\Error() )->set_post( $wp_query->post );
			break;
	}

	$data['page'] = $template->to_array()['children'];
	return $data;
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
