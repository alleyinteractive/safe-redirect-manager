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
			( new \WP_Components\Head() )
				->set_query( $wp_query )
				->set_title( __( 'Colorado Public Radio - In-Depth News and Streaming Music', 'cpr' ) ),
			new Components\Slim_Navigation\Slim_Navigation(),
			new Components\Primary_Navigation\Primary_Navigation(),
			( new Components\Header\Header() ),
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
					$head->set_title( __( 'Colorado Public Radio - In-Depth News and Streaming Music', 'cpr' ) );
					$template = ( new Components\Templates\Homepage() )->set_post( $wp_query->post );
					break;

				case 'news':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'Colorado Public Radio News | CPR', 'cpr' ) );
					$template = ( new Components\Templates\News() )->set_post( $wp_query->post );
					break;

				case 'classical':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'Colorado Public Radio Classical | CPR', 'cpr' ) );
					$template = ( new Components\Templates\Classical() )->set_post( $wp_query->post );
					break;

				case 'indie':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'CPR\'s Indie 102.3 - New and Independent Music | CPR', 'cpr' ) );
					$template = ( new Components\Templates\Indie() )->set_post( $wp_query->post );
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
		 * Underwriters archive
		 */
		case $wp_query->is_post_type_archive( 'underwriter' ):
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Underwriter_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Jobs archive.
		 */
		case $wp_query->is_post_type_archive( 'job' ):
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Job_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Press Releases archive.
		 */
		case $wp_query->is_post_type_archive( 'press-release' ):
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Press_Release_Archive() )->set_query( $wp_query );
			break;
		/**
		 * Podcast/Show single.
		 */
		case $wp_query->is_tax( 'podcast' ):
		case $wp_query->is_tax( 'show' ):
			// This is a Term_Post_Link, so get the linked post (since all the
			// meta is there).
			$post_id = \Alleypack\Term_Post_Link::get_post_from_term( $wp_query->get_queried_object_id() );

			$head->set_post( $post_id );
			$template = ( new Components\Templates\Podcast_And_Show() )->set_post( $post_id );
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
		 * Page.
		 */
		case $wp_query->is_page():
		case $wp_query->is_singular( 'job' ):
			$head->set_post( $wp_query->post );

			// Decide on a page template.
			$template = (string) get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
			switch ( $template ) {
				case 'grid-group':
					$template = ( new Components\Templates\Grid_Group_Page() )->set_post( $wp_query->post );
					break;

				default:
					$template = ( new Components\Templates\Page() )->set_post( $wp_query->post );
					break;
			}
			break;

		/**
		 * Event.
		 */
		case $wp_query->is_singular( 'tribe_events' ):
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Event() )->set_post( $wp_query->post );
			break;

		/**
		 * Article.
		 */
		case $wp_query->is_single():
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Article() )->set_post( $wp_query->post );
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

	$data['page'][] = ( new Components\Header\Header() )->set_query( $wp_query );

	return $data;
}
add_filter( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
