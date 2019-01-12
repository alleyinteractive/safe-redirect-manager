<?php
/**
 * Routing.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Routing.
 */
class Routing {

	/**
	 * Hook into WP-Irving.
	 */
	public function __construct() {
		add_action( 'wp_irving_components_route', [ $this, 'build_components_endpoint' ], 10, 5 );
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
	public function build_components_endpoint(
		array $data,
		\WP_Query $wp_query,
		string $context,
		string $path,
		\WP_REST_Request $request
	) : array {

		// Build defaults.
		if ( 'site' === $context ) {
			$data['defaults'] = [
				new Component\Slim_Navigation(),
				new \Alleypack\WP_Component\Body(),
				new Component\Footer(),
			];
		}

		switch ( true ) {
			case $wp_query->is_page():
				$template = new Template\Homepage();
				$template->set_post( $wp_query->post );
				break;
		}

		return $data;
	}
}

add_action(
	'init',
	function() {
		new Routing();
	}
);
