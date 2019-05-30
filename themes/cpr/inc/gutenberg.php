<?php
/**
 * External Links
 *
 * @package CPR
 */

namespace CPR;

/**
 * Disable Gutenberg editor for certain post types.
 *
 * @todo Remove 'show-episode' from the list once FM Zones is compatible with GB.
 *
 * @param string $current_status The status of the post.
 * @param string $post_type      The post type.
 * @return string $current_status The status of the post.
 */
function disable_gutenberg_for_selected_post_type( $current_status, $post_type ) {
	if ( in_array( $post_type, [ 'external-link', 'show-episode' ], true ) ) {
		return false;
	}
	return $current_status;
}
add_filter( 'use_block_editor_for_post_type', __NAMESPACE__ . '\disable_gutenberg_for_selected_post_type', 10, 2 );
