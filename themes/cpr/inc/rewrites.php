<?php
/**
 * Custom rewrite modifications
 *
 * @package CPR
 */

namespace CPR;

/**
 * Custom rewrites for podcasts.
 */
function podcast_rewrites() {
	add_rewrite_rule(
		'^(news|classical|openair)/podcast/([^/]+)/?$',
		'index.php?podcast=$matches[2]&podcast-episode-post=$matches[2]',
		'top'
	);
}
add_action( 'init', __NAMESPACE__ . '\podcast_rewrites', 11 );

// Add search rewrites.
add_action( 'init', [ '\\CPR\\Component\\Templates\\Search', 'rewrite_rules' ] );
