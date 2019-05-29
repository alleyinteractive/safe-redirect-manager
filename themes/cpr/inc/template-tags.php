<?php
/**
 * Helper functions
 *
 * @package CPR
 */

namespace CPR;

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

/**
 * Replace excerpt's `[...]` with a `Read More` link to the content.
 *
 * @param string $more Read more text.
 * @return string
 */
function modify_excerpt_more( $more ) {
	return sprintf(
		'<p><small><a href="%1$s" class="more-link">%2$s</a></small></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		esc_html__( 'Read More', 'cpr' )
	);
}
add_filter( 'excerpt_more', __NAMESPACE__ . '\modify_excerpt_more' );

/**
 * End the excerpt on a full sentance.
 *
 * @param string $excerpt Excerpt.
 * @return string
 */
function end_excerpt_at_sentance_punctuation( $excerpt ) {

	$allowed_end   = [ '.', '!', '?', '...' ];
	$excerpt_parts = explode( ' ', $excerpt );
	$found         = false;
	$last          = '';

	while ( ! $found && ! empty( $excerpt_parts ) ) {
		$last  = array_pop( $excerpt_parts );
		$end   = strrev( $last );
		$found = in_array( $end[0], $allowed_end, true );
	}

	if ( empty( $excerpt_parts ) ) {
		return $excerpt;
	}

	return rtrim( implode( ' ', $excerpt_parts ) . ' ' . $last );
}
add_filter( 'get_the_excerpt', __NAMESPACE__ . '\end_excerpt_at_sentance_punctuation' );
