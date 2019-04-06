<?php
/**
 * Routing.
 *
 * @package CPR
 */

namespace CPR;

// Filter the namespace for autoloading.
add_filter(
	'wp_components_theme_components_namespace',
	function() {
		return 'CPR\Components';
	}
);

// Filter the theme component path for autoloading.
add_filter(
	'wp_components_theme_components_path',
	function( $path, $class, $dirs, $filename ) {
		// Remove last $dirs entry since we don't nest our components in an extra folder.
		array_pop( $dirs );
		return get_template_directory() . '/components/' . implode( '/', $dirs ) . "/class-{$filename}.php";
	},
	10,
	4
);

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
			( new \WP_Components\Head() )->set_query( $wp_query ),
			new Components\Slim_Navigation\Slim_Navigation(),
			new Components\Primary_Navigation\Primary_Navigation(),
			( new Components\Header\Header() )->set_query( $wp_query ),
			new \WP_Components\Body(),
			new Components\Footer\Footer(),
		];
	}

	// Begin building a head instance for this page.
	$head = new \WP_Components\Head();

	// Build page.
	switch ( true ) {

		/**
		 * Search results.
		 */
		case $wp_query->is_search():
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Search() )->set_query( $wp_query );
			break;

		/**
		 * Landing Pages.
		 */
		case 'landing-page' === $wp_query->get( 'dispatch' ):
			switch ( $wp_query->get( 'landing-page-type' ) ) {
				case 'homepage':
					$head->set_post( $wp_query->post );
					$template = ( new Components\Templates\Homepage() )->set_post( $wp_query->post );
					break;

				case 'news':
					$head->set_post( $wp_query->post );
					$template = ( new Components\Templates\News() )->set_post( $wp_query->post );
					break;

				case 'classical':
					$head->set_post( $wp_query->post );
					$template = ( new Components\Templates\Classical() )->set_post( $wp_query->post );
					break;

				case 'openair':
					$head->set_post( $wp_query->post );
					$template = ( new Components\Templates\Openair() )->set_post( $wp_query->post );
					break;
			}
			break;


		/**
		 * Author archive.
		 */
		case $wp_query->is_author():
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Author_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Top 30 archive.
		 */
		case $wp_query->is_post_type_archive( 'top-30' ):
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Top_30_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Term archives.
		 */
		case $wp_query->is_tax():
		case $wp_query->is_tag():
		case $wp_query->is_category():
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Term_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Article.
		 */
		case $wp_query->is_single():
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Article() )->set_post( $wp_query->post );
			break;

		/**
		 * Page.
		 */
		case $wp_query->is_page():
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Page() )->set_post( $wp_query->post );
			break;

		/**
		 * Error page.
		 */
		case $wp_query->is_404():
		default:
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Error() )->set_query( $wp_query );
			break;
	}

	// Setup the page data based on routing.
	$data['page'] = $template->to_array()['children'];

	// Unshift the head to the top.
	array_unshift(
		$data['page'],
		apply_filters( 'cpr_head', $head )
	);
	// Unshift the head to the top.
	array_unshift(
		$data['page'],
		( new Components\Header\Header() )->set_query( $wp_query )
	);

	return $data;
}
add_filter( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
