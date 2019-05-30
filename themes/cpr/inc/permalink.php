<?php
/**
 * Permalink
 *
 * @package CPR
 */

namespace CPR;

/**
 * Replaces the permalink for external content posts with a post meta
 * permalink.
 *
 * @param string       $url  The post url.
 * @param int|\WP_Post $post The post id or object.
 * @return string $url The new permalink for the post.
 */
function replace_permalink_external_link( $url, $post ) {

	// Handle different hook parameters.
	$post = get_post( $post->ID ?? $post );

	switch ( $post->post_type ) {
		case 'external-link':
			return get_post_meta( $post->ID, 'link', true );

		case 'page':
			// Prepend the sections where necessary.
			$section = wp_get_post_terms( $post->ID, 'section' )[0] ?? null;
			if ( $section instanceof \WP_Term && 'colorado-public-radio' !== $section->slug ) {
				$path = wp_parse_url( $url, PHP_URL_PATH );
				return home_url( $section->slug . $path );
			}
			return $url;
	}
	return $url;
}
add_filter( 'post_type_link', __NAMESPACE__ . '\replace_permalink_external_link', 10, 2 );
add_filter( 'page_link', __NAMESPACE__ . '\replace_permalink_external_link', 10, 2 );

/**
 * Modify the section term links.
 *
 * @param string   $url  Original url.
 * @param \WP_Term $term Term object.
 * @return string
 */
function term_link_filter( string $url, \WP_Term $term ) {

	// Modify section term links.
	if ( 'section' === $term->taxonomy ) {
		$url = home_url( $term->slug . '/all/' );
	}

	return $url;
}
add_filter( 'term_link', __NAMESPACE__ . '\term_link_filter', 10, 2 );
