<?php
/**
 * Irving implementation.
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Entry point for Thrive Global's Irving implementation.
 */
class Irving {

	/**
	 * Hook into WP-Irving.
	 */
	public function __construct() {
		add_action( 'wp_irving_components_route', [ $this, 'setup_page_data' ], 10, 5 );
		add_action( 'wp_irving_components_route', [ $this, 'default_components' ], 10, 3 );
		add_action( 'wp_irving_components_route', [ $this, 'set_embed_scripts' ], 11, 3 );
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

			// Homepage.
			case '' === $path:
			case '/' === $path:
			case '/news' === $path:
			case '/classical' === $path:
			case '/openair' === $path:
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
			case 'stories' === $path_parts[0]:
				$data = ( new Template\Single() )->get_irving_components( $data, $wp_query );
				break;

			// Page.
			case $wp_query->is_page():
			case 'page-india' === $wp_query->get( 'post_type' ):
			case 'page-greece' === $wp_query->get( 'post_type' ):
				$data = ( new Template\Page() )->get_irving_components( $data, $wp_query );
				break;

			// Error.
			default:
				$data = ( new Template\Error() )->get_irving_components( $data, $wp_query );
		}

		/**
		 * Disable admin bar until it's completed.
		 *
		 * Always include the admin bar.
		 * $admin_bar_component = ( new \WP_Irving\Component\Admin_Bar() )->parse_query( $wp_query )->to_array();
		 * array_unshift( $data['page'], $admin_bar_component );
		 */

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
}

add_action(
	'init',
	function() {
		new Irving();
	}
);
