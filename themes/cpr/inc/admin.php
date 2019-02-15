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
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\remove_postcustom', 100 );
