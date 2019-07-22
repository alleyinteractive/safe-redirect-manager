<?php
/**
 * Customizations for internal (this site's) RSS, Atom, JSON, etc. feeds.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Setup feed routing.
 */
function setup_feed_routing() {

	// Feeds.
	\Alleypack\Path_Dispatch()->add_paths(
		[
			[
				'path'     => 'app-feed',
				'template' => 'app-feed',
				'callback' => function() {
					get_template_part( 'inc/feeds/app' );
					exit();
				},
				'rewrite'  => array(
					'rule'       => 'rss\/app\/(.*).rss?',
					'redirect'   => 'index.php?dispatch=app-feed&custom-feed-slug=$matches[1]',
					'query_vars' => 'custom-feed-slug',
				),
			],
			[
				'path'     => 'podcast-feed',
				'template' => 'podcast-feed',
				'callback' => function() {
					get_template_part( 'inc/feeds/podcast' );
					exit();
				},
				'rewrite'  => array(
					'rule'       => 'rss\/(.*).podcast.rss?',
					'redirect'   => 'index.php?dispatch=podcast-feed&custom-feed-slug=$matches[1]',
					'query_vars' => 'custom-feed-slug',
				),
			],
		]
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_feed_routing' );
