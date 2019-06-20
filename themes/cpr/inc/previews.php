<?php
/**
 * Previews
 *
 * @package CPR
 */

namespace CPR;

// Enable public previews to circumvent a lack of authentication in Irving.
add_filter(
	'wp_irving_components_request',
	function ( $request ) {
		if (
			true === (bool) $request->get_param( 'preview' )
			|| 0 !== absint( $request->get_param( 'p' ) )
		) {
			// Make previews public.
			add_filter(
				'posts_results',
				function ( $posts ) {
					if ( empty( $posts ) ) {
						return $posts;
					}
					$posts[0]->post_status = 'publish';
					return $posts;
				}
			);
		}
	}
);

// Temporarily disable post preview nonces.
remove_action( 'init', '_show_post_preview' );
