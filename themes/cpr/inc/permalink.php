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
 * @param string   $url  The post url.
 * @param \WP_Post $post The post object.
 * @return string $url The new permalink for the post.
 */
function replace_permalink_external_link( $url, $post ) {
	if ( 'external-link' === $post->post_type ) {
		$url = get_post_meta( $post->ID, 'link', true );
	}
	return $url;
}
add_filter( 'post_type_link', __NAMESPACE__ . '\replace_permalink_external_link', 10, 2 );
