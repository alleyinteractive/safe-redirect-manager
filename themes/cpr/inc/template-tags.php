<?php
/**
 * Helper functions
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Get the base template part for the current request.
 */
function get_main_template() {
	ai_get_template_part( Wrapping::$base );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">';
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\pingback_header' );
