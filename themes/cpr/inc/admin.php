<?php
/**
 * Add any admin manipulations here
 *
 * @package CPR
 */

namespace CPR;

/**
 * Remove the "Custom Fields" meta box.
 *
 * It generates an expensive query and is almost never used in practice.
 */
function remove_postcustom() {
	remove_meta_box( 'postcustom', null, 'normal' );

	// Remove all default coauthor meta fields.
	remove_meta_box( 'coauthors-manage-guest-author-bio', null, 'normal' );
	remove_meta_box( 'coauthors-manage-guest-author-contact-info', null, 'normal' );

	// Remove sharing meta.
	remove_meta_box( 'sharing_meta', null, 'side' );
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\remove_postcustom', 100 );

/**
 * Make post type searchable in the backend so Zoninator can find it.
 */
function allow_searching_post_types_in_admin() {
	if ( is_admin() ) {
		global $wp_post_types;
		foreach ( [ 'guest-author', 'external-link' ] as $post_type ) {
			if ( ! empty( $wp_post_types[ $post_type ] ) ) {
				$wp_post_types[ $post_type ]->exclude_from_search = false;
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\allow_searching_post_types_in_admin', 15 );
