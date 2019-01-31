<?php
/**
 * Irving implementation.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Entry point for CPR's Irving implementation.
 */
class Irving {

	/**
	 * Hook into WP-Irving.
	 */
	public function __construct() {
		add_action( 'wp_irving_components_route', [ $this, 'build_components_endpoint' ], 10, 5 );

		// Handle forms.
		add_filter( 'wp_irving_form_endpoints', [ $this, 'form_endpoints' ], 10, 1 );
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
				new Slim_Navigation(),
				new \Alleypack\WP_Components\Body(),
				new Footer(),
			];
		}

		switch ( true ) {
			case $wp_query->is_home():
				$data['page'] = ( new Component\Homepage( $data, $wp_query ) )->get_components();
				break;
		}

		return $data;
	}

	/**
	 * Setup form endpoints and callbacks
	 *
	 * @param  array $form_endpoints Form endpoint slugs and callback functions.
	 * @return array Form endpoints with Thrive forms merged in.
	 */
	public function form_endpoints( $form_endpoints ) {
		array_push(
			print_r('butts');
			$form_endpoints,
			[
				'slug' => 'newsletter',
				'callback' => [ __NAMESPACE__ . '\Component\Modules\Newsletter', 'get_route_response' ],
			],
		);

		return $form_endpoints;
	}
}

add_action(
	'init',
	function() {
		new Irving();
	}
);
