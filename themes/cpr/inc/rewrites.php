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

	// Calendars.
	add_rewrite_rule(
		'^(news|classical|indie)/calendar/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=tribe_events&eventDisplay=month',
		'top'
	);

	add_rewrite_rule(
		'^(news|classical|indie)/calendar/([^/]+)/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=tribe_events&eventDisplay=month&eventDate=$matches[2]',
		'top'
	);

	add_rewrite_rule(
		'^(news|classical|indie)/calendar/([^/]+)/page/?([0-9]{1,})/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=tribe_events&eventDisplay=month&eventDate=$matches[2]&paged=$matches[3]',
		'top'
	);

	add_rewrite_rule(
		'^(news|classical|indie)/calendar/category/([^/]+)/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=tribe_events&eventDisplay=month&tribe_events_cat=$matches[2]',
		'top'
	);

	add_rewrite_rule(
		'^(news|classical|indie)/calendar/category/([^/]+)/([^/]+)/?$',
		'index.php?taxonomy=section&term=$matches[1]&post_type=tribe_events&eventDisplay=month&tribe_events_cat=$matches[2]&eventDate=$matches[3]',
		'top'
	);

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

	// All content archive.
	add_rewrite_rule(
		'^all/?$',
		'index.php?post_type=post',
		'top'
	);

	add_rewrite_rule(
		'^all/page/?([0-9]{1,})/?$',
		'index.php?post_type=post&paged=$matches[1]',
		'top'
	);
}
add_action( 'init', __NAMESPACE__ . '\rewrites', 11 );

// Add search rewrites.
add_action( 'init', [ '\\CPR\\Components\\Templates\\Search', 'rewrite_rules' ] );

// Add additional path for newsletters to proxy through.
add_action( 'after_setup_theme', [ '\\CPR\\Components\\Templates\\Newsletter', 'dispatch_rewrites' ] );

// Add path for streaming playlist landing pages.
add_action( 'after_setup_theme', [ '\\CPR\\Components\\Templates\\Streaming_Playlist', 'dispatch_rewrites' ] );
