<?php
/**
 * Custom rewrite modifications
 *
 * @package CPR
 */

namespace CPR;

/**
 * Custom rewrites.
 */
function rewrites() {

	// Podcasts.
	add_rewrite_rule(
		'^(news|classical|indie)/podcast/([^/]+)/?$',
		'index.php?podcast=$matches[2]&podcast-episode-post=$matches[2]',
		'top'
	);

	// All content archive.
	add_rewrite_rule( '^/all/?$', 'index.php?post_type=post', 'top' );
	add_rewrite_rule( '^/all/page/?([0-9]{1,})/?$', 'index.php?post_type=post&paged=$matches[1]', 'top' );

	// Section archive.
	add_rewrite_rule(
		'^(news|classical|indie)/all/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=post',
		'top'
	);

	add_rewrite_rule(
		'^(news|classical|indie)/all/page/?([0-9]{1,})/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=post&paged=$matches[2]',
		'top'
	);
}
add_action( 'init', __NAMESPACE__ . '\rewrites', 11 );

// Add search rewrites.
add_action( 'init', [ '\\CPR\\Components\\Templates\\Search', 'rewrite_rules' ] );
