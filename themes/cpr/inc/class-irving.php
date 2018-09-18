<?php
/**
 * Irving implementation.
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Entry point for CPR's Irving implementation.
 */
class Irving {

	/**
	 * Hook into WP-Irving.
	 */
	public function __construct() {
		// Handle routing.
		add_action( 'wp_irving_components_route', [ $this, 'setup_page_data' ], 10, 5 );
		add_action( 'wp_irving_components_route', [ $this, 'default_components' ], 10, 3 );
		add_action( 'wp_irving_components_route', [ $this, 'set_embed_scripts' ], 11, 3 );

		// Redirect template calls.
		add_action( 'template_redirect', [ $this, 'redirect_template_calls' ] );
	}

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
	public function setup_page_data(
		array $data,
		\WP_Query $wp_query,
		string $context,
		string $path,
		\WP_REST_Request $request
	) : array {

		$path_parts = explode( '/', ltrim( $path, '/' ) );

		switch ( true ) {
			// Search results.
			// Takes precedence over Homepage to facilitate root search results
			// page ie. /?s=asdf.
			case $wp_query->is_search():
				$data = ( new Template\Search() )->get_irving_components( $data, $wp_query );
				break;

			// Homepages.
			case '' === $path:
			case '/' === $path:
			case '/news/' === $path:
			case '/classical/' === $path:
			case '/openair/' === $path:
				$data = ( new Template\Homepage() )->get_irving_components( $data, $wp_query );
				break;

			// Author archive.
			case $wp_query->is_author():
				$data = ( new Template\Author() )->get_irving_components( $data, $wp_query );
				break;

			// Terms.
			case $wp_query->is_tax():
			case $wp_query->is_tag():
			case $wp_query->is_category():
				$data = ( new Template\Term() )->get_irving_components( $data, $wp_query );
				break;

			// Single.
			case $wp_query->is_single():
				$data = ( new Template\Single() )->get_irving_components( $data, $wp_query );
				break;

			// Page.
			case $wp_query->is_page():
				$data = ( new Template\Page() )->get_irving_components( $data, $wp_query );
				break;

			// Error.
			default:
				$data = ( new Template\Error() )->get_irving_components( $data, $wp_query );
		}

		return (array) $data;
	}

	/**
	 * Setup the default components.
	 *
	 * @param  array     $data     Data for response.
	 * @param  \WP_Query $wp_query WP_Query object corresponding to this
	 *                             request.
	 * @param  string    $context  The context for this request.
	 * @return array Data for response.
	 */
	public function default_components( array $data, \WP_Query $wp_query, string $context ) : array {

		// If not a `site` context, return regular response.
		if ( 'site' !== $context ) {
			return $data;
		}

		$data['defaults'] = [
			new \WP_Irving\Component\Head(),
			( new \WP_Irving\Component\Header() )
				->set_config( 'siteUrl', home_url() )
				->set_children(
					[
						( new \WP_Irving\Component\Menu() )
							->parse_wp_menu_by_location( 'header' )
							->set_config( 'location', 'header' ),
					]
				),
			new \WP_Irving\Component\Component( 'body' ),
			( new \WP_Irving\Component\Footer() )
				->set_children(
					[
						( new \WP_Irving\Component\Menu() )
							->parse_wp_menu_by_location( 'footer' )
							->set_config( 'location', 'footer' ),
					]
				),
		];

		return $data;
	}

	/**
	 * Add embed scripts to head component.
	 *
	 * @param  array     $data     Data for response.
	 * @param  \WP_Query $wp_query WP_Query object corresponding to this
	 *                             request.
	 * @param  string    $context  The context for this request.
	 * @return array Data for response.
	 */
	public function set_embed_scripts( array $data, \WP_Query $wp_query, string $context ) : array {
		$head_component = null;
		$components     = array_merge( $data['page'], $data['defaults'] );
		foreach ( $components as $component ) {
			if ( 'head' === $component->name ) {
				$head_component = $component;
				break;
			}
		}

		if ( $head_component ) {
			$scripts = \WP_Irving\Component\Embed::get_scripts();
			foreach ( $scripts as $script ) {
				$head_component->add_script( $script['src'], $script['defer'], $script['async'] );
			}
		}

		return $data;
	}

	/**
	 * Redirect all template calls to the head of the site.
	 */
	public function redirect_template_calls() {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		// Don't redirect feed, sitemap, admin, or logged-in requests.
		if (
			is_feed()
			|| is_admin()
			|| is_user_logged_in()
			|| ( false !== strpos( $request_uri, '.xml' ) )
			|| ! defined( 'WP_HOME' )
		) {
			return;
		}

		$irving_url = WP_HOME . $request_uri;
		wp_redirect( $irving_url );
		exit();
	}
}

add_action(
	'init',
	function() {
		new Irving();
	}
);
