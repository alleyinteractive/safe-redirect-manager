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
	$settings = get_option( 'cpr-settings' );

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
			( new Components\Footer\Footer_Sidebar() )
				->set_sidebar( 'footer-sidebar' ),
			new Components\Footer\Footer(),
		];
	}

	// Begin building a head instance for this page.
	$head = new \WP_Components\Head();

	// Begin building a new header instance for the request.
	$header = ( new Components\Header\Header() )->set_cpr_header();

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
		case 'landing-page' === $wp_query->get( 'post_type' ):
			switch ( $wp_query->get( 'landing-page-type' ) ) {
				case 'homepage':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'Colorado Public Radio - In-Depth News and Streaming Music', 'cpr' ) );
					$template = ( new Components\Templates\Homepage() )->set_post( $wp_query->post );
					break;

				case 'news':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'Colorado Public Radio News | Colorado Public Radio', 'cpr' ) );
					$header->set_news_header();
					$template = ( new Components\Templates\News() )->set_post( $wp_query->post );
					break;

				case 'classical':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'Colorado Public Radio Classical | Colorado Public Radio', 'cpr' ) );
					$header->set_classical_header();
					$template = ( new Components\Templates\Classical() )->set_post( $wp_query->post );
					break;

				case 'indie':
					$head->set_post( $wp_query->post );
					$head->set_title( __( 'CPR\'s Indie 102.3 - New and Independent Music | Colorado Public Radio', 'cpr' ) );
					$header->set_indie_header();
					$template = ( new Components\Templates\Indie() )->set_post( $wp_query->post );
					break;
			}
			break;

		/**
		 * Landing Pages.
		 */
		case 'streaming_playlist' === $wp_query->get( 'dispatch' ):
			$station = $wp_query->get( 'station' );
			$head->set_query( $wp_query );

			switch ( $station ) {
				case 'news':
					$head->set_title( __( 'Colorado Public Radio News Playlist | Colorado Public Radio', 'cpr' ) );
					$header->set_news_header();
					break;

				case 'classical':
					$head->set_title( __( 'Colorado Public Radio Classical Playlist | Colorado Public Radio', 'cpr' ) );
					$header->set_classical_header();
					break;

				case 'indie':
					$head->set_title( __( 'CPR\'s Indie 102.3 - New and Independent Music Playlist | Colorado Public Radio', 'cpr' ) );
					$header->set_indie_header();
					break;
			}

			$template = ( new Components\Templates\Streaming_Playlist() )->set_query( $wp_query );
			break;

		/**
		 * Author archive.
		 */
		case ( $wp_query->is_author() || ! empty( $wp_query->query_vars['author_name'] ) ):
			if ( empty( $wp_query->posts ) ) {
				$wp_query->is_author = true;
				$wp_query->is_404    = false;
			}
			$template = ( new Components\Templates\Author_Archive() )->set_query( $wp_query );
			$head->set_query( $wp_query );
			$head->set_title(
				sprintf(
					// translators: Author Display name.
					__( '%1$s | Colorado Public Radio', 'cpr' ),
					$template->get_author_display_name()
				)
			);
			break;

		/**
		 * Top 30 archive.
		 */
		case $wp_query->is_post_type_archive( 'top-30' ):
			$head->set_query( $wp_query );
			$header->set_indie_header();
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
		 * Newsletter archive.
		 */
		case $wp_query->is_post_type_archive( 'newsletter-single' ):
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Newsletter_Archive() )->set_query( $wp_query );
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
			$head->set_query( $wp_query );
			$header->set_query( $wp_query );
			$template = ( new Components\Templates\Podcast_And_Show() )->set_query( $wp_query );
			break;

		/**
		 * Calendar.
		 *
		 * The second condition prevents 404s when viewing a month without
		 * any events.
		 */
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
		case $wp_query->is_post_type_archive( 'tribe_events' ):
		case (
			'tribe_events' === ( $wp_query->query['post_type'] ?? '' )
			&& isset( $wp_query->query['eventDisplay'] )
			&& 'month' === $wp_query->query['eventDisplay']
		):
			$template = ( new Components\Templates\Calendar() )->set_query( $wp_query );
			$head->set_query( $wp_query );
			$head->set_title(
				sprintf(
					// translators: Calendar Header.
					__( '%1$s | Colorado Public Radio', 'cpr' ),
					$template->get_heading()
				)
			);
			break;

		/**
		 * Term archives.
		 */
		case $wp_query->is_tax():
		case $wp_query->is_tag():
		case $wp_query->is_category():
			$head->set_query( $wp_query );
			$header->set_query( $wp_query );
			$template = ( new Components\Templates\Term_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Archive for all post (post_type) content.
		 */
		case ( strpos( $path, '/all/' ) === 0 ):
			$head->set_query( $wp_query );
			$head->set_title( __( 'All CPR\'s | Colorado Public Radi', 'cpr' ) );
			$header->set_query( $wp_query );
			$template = ( new Components\Templates\All_Archive() )->set_query( $wp_query );
			break;

		/**
		 * Pages and other simple content super page style singles.
		 */
		case $wp_query->is_page():
		case $wp_query->is_singular( 'job' ):
		case $wp_query->is_singular( 'press-release' ):
			$head->set_post( $wp_query->post );
			$header->set_post( $wp_query->post );

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
		 * Top 30.
		 */
		case $wp_query->is_singular( 'top-30' ):
			$head->set_post( $wp_query->post );
			$header->set_indie_header();
			$template = ( new Components\Templates\Top_30() )->set_post( $wp_query->post );
			break;

		/**
		 * Event.
		 */
		case $wp_query->is_singular( 'tribe_events' ):
			$head->set_post( $wp_query->post );
			$header->set_post( $wp_query->post );
			$template = ( new Components\Templates\Event() )->set_post( $wp_query->post );
			break;

		/**
		 * Newsletter.
		 */
		case $wp_query->is_singular( 'newsletter-single' ):
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Newsletter() )->set_post( $wp_query->post );
			break;

		/**
		 * Article.
		 */
		case $wp_query->is_single():
			$head->set_post( $wp_query->post );
			$header->set_post( $wp_query->post );
			$template = ( new Components\Templates\Article() )->set_post( $wp_query->post );
			break;

		/**
		 * Error page.
		 */
		case $wp_query->is_404():
			// Attempt to redirec to a legacy piece of content.
			$wp_query = new \WP_Query(
				[
					'fields'      => 'ids',
					'meta_key'    => 'legacy_path',
					'meta_value'  => substr( $path, 1 ), // Trim first slash to match meta values.
					'post_status' => 'publish',
					'post_type'   => 'any',
				]
			);

			// Handle redirects.
			if ( 0 !== absint( $wp_query->posts[0] ?? 0 ) && class_exists( '\WPCOM_Legacy_Redirector' ) ) {
				$redirect_to = get_permalink( $wp_query->posts[0] );
				\WPCOM_Legacy_Redirector::insert_legacy_redirect( site_url( $path ), $redirect_to );
				wp_safe_redirect( $redirect_to, 301 );
				exit();
			}

			// no break.
		default:
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Error() )->set_query( $wp_query );
			break;
	}

	// Set up context providers.
	$data['providers'] = [
		( new Components\Google_Tag_Manager() )
			->set_config( 'container_id', $settings['gtm']['container_id'] ?? '' )
			->set_meta_from_query( $wp_query, $head ),
		( new Components\Advertising\Ad_Provider() )
			->set_config( 'dfp_network_id', '12925303' )
			->set_targeting_from_query( $wp_query ),
	];

	// Setup the page data based on routing.
	$data['page'] = $template->to_array()['children'];

	// Unshift the head to the top.
	array_unshift(
		$data['page'],
		apply_filters( 'cpr_head', $head )
	);

	$data['page'][] = $header;

	return $data;
}
add_filter( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
