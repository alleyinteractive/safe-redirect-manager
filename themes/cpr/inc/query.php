<?php
/**
 * One-off query modifications and manipulations (e.g. through pre_get_posts).
 * Modifications tied to a larger features should reside with the rest of the
 * code for that feature.
 *
 * @package CPR
 */

namespace CPR;

// Modfiy search results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Search', 'pre_get_posts' ] );

// Modify term archive results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Term_Archive', 'pre_get_posts' ] );

// Modify author archive results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Author_Archive', 'pre_get_posts' ] );

// Get all underwriters.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Underwriter_Archive', 'pre_get_posts' ] );

// Modify calendar results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Calendar', 'pre_get_posts' ] );

// Modify all content results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\All_Archive', 'pre_get_posts' ] );

// Modify Podcast and Shows results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Podcast_And_Show', 'pre_get_posts' ] );

/**
 * Unhook expensive Tribe events query in the admin.
 */
function remove_tribe_events_query() {
	if ( ! is_admin() ) {
		return;
	}

	$screen = get_current_screen();
	if ( 'landing-page' === ( $screen->post_type ?? '' ) ) {
		remove_action( 'pre_get_posts', [ 'Tribe__Events__Query', 'pre_get_posts' ], 50 );
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\remove_tribe_events_query' );
