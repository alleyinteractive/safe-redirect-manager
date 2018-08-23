<?php
/**
 * Add any admin manipulations here
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Remove the "Custom Fields" meta box.
 *
 * It generates an expensive query and is almost never used in practice.
 */
function remove_postcustom() {
	remove_meta_box( 'postcustom', null, 'normal' );
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\remove_postcustom' );
