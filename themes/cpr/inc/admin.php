<?php
/**
 * Add any admin manipulations here
 *
 * @package CPR
 */

namespace CPR;

// Force CAP to use only guest authors.
add_filter( 'coauthors_guest_authors_force', '__return_true' );

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

/**
 * Remove unused menu items.
 */
function remove_menu_pages() {
	remove_menu_page( 'edit.php?post_type=feedback' );
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\remove_menu_pages' );

/**
 * Add menu items.
 */
function add_menu_pages() {

	// Add top-level menu item for shows and podcasts.
	add_menu_page(
		__( 'Shows & Podcasts', 'cpr' ),
		__( 'Shows & Podcasts', 'cpr' ),
		'edit_posts',
		'shows-podcasts',
		// 'edit.php?page=shows-podcasts',
		null,
		'dashicons-format-audio',
		6
	);

	// Add podcast taxonomy to the menu.
	add_submenu_page(
		'shows-podcasts',
		__( 'Podcasts', 'cpr' ),
		__( 'Podcasts', 'cpr' ),
		'edit_posts',
		'edit-tags.php?taxonomy=podcast&post_type=podcast-episode',
		null
	);

	// Add show-episode CPT to the menu.
	add_submenu_page(
		'shows-podcasts',
		__( 'Show Episodes', 'cpr' ),
		__( 'Show Episodes', 'cpr' ),
		'edit_posts',
		'edit.php?post_type=show-episode',
		null
	);

	// Add show-segment CPT to the menu.
	add_submenu_page(
		'shows-podcasts',
		__( 'Show Segments', 'cpr' ),
		__( 'Show Segments', 'cpr' ),
		'edit_posts',
		'edit.php?post_type=show-segment',
		null
	);

	// Add show taxonomy to the menu.
	add_submenu_page(
		'shows-podcasts',
		__( 'Shows', 'cpr' ),
		__( 'Shows', 'cpr' ),
		'edit_posts',
		'edit-tags.php?taxonomy=show&post_type=show-episode',
		null
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_menu_pages' );

/**
 * Set the correct menu and submenu items as active.
 * 
 * @param string $parent_file The top level menu item.
 * @return string
 */
function set_current_menu( $parent_file ) {
	global $submenu_file, $current_screen;

	switch ( $current_screen->id ?? '' ) {
		case 'edit-podcast':
		case 'podcast-post':
			$parent_file  = 'shows-podcasts';
			$submenu_file = 'edit-tags.php?taxonomy=podcast&post_type=podcast-episode';
			break;
		case 'edit-show':
		case 'show-post':
			$parent_file  = 'shows-podcasts';
			$submenu_file = 'edit-tags.php?taxonomy=show&post_type=show-episode';
			break;
		case 'show-segment':
			$parent_file  = 'shows-podcasts';
			$submenu_file = 'edit.php?post_type=show-segment';
			break;
		case 'podcast-episode':
			$parent_file  = 'shows-podcasts';
			$submenu_file = 'edit.php?post_type=podcast-episode';
			break;
		case 'show-episode':
			$parent_file  = 'shows-podcasts';
			$submenu_file = 'edit.php?post_type=show-episode';
			break;
	}

	return $parent_file;
}
add_filter( 'parent_file', __NAMESPACE__ . '\set_current_menu' );
