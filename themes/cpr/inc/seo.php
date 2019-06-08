<?php
/**
 * SEO modifications
 *
 * @package CPR
 */

namespace CPR;

/**
 * Modify post types that WP SEO applies to.
 *
 * @param  array $post_types Default post types for WP SEO.
 * @return array             Corrected post types for WP SEO.
 */
function wp_seo_single_post_types( $post_types ) {

	// Build an array of post types to remove from WP SEO.
	$post_types_to_remove = array(
		'album',
		'external-link',
		'guest-author',
		'landing-page',
		'newsletter-post',
		'newsletter-single',
		'post',
		'top-30',
		'underwriter',
	);

	// Remove post types.
	foreach ( $post_types_to_remove as $post_type_to_remove ) {
		if ( isset( $post_types[ $post_type_to_remove ] ) ) {
			unset( $post_types[ $post_type_to_remove ] );
		}
	}

	return $post_types;
}
add_filter( 'wp_seo_single_post_types', __NAMESPACE__ . '\wp_seo_single_post_types' );
