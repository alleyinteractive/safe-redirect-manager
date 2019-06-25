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

	// Remove misc fields.
	remove_meta_box( 'sharing_meta', null, 'side' );
	remove_meta_box( 'pageparentdiv', null, 'side' );
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\remove_postcustom', 100 );

/**
 * Modify post type support for the 'post' post type.
 */
function modify_post_supports() {

	// Removes unused panels.
	foreach ( [ 'post', 'podcast-episode', 'show-episode', 'show-segment' ] as $post_type ) {
		remove_post_type_support( $post_type, 'comments' );
		remove_post_type_support( $post_type, 'page-attributes' );
		remove_post_type_support( $post_type, 'trackbacks' );
		remove_post_type_support( $post_type, 'thumbnail' );
		remove_post_type_support( $post_type, 'excerpt' );
	}
}
add_action( 'init', __NAMESPACE__ . '\modify_post_supports' );

/**
 * Make post type searchable in the backend so Zoninator can find it.
 */
function allow_searching_post_types_in_admin() {
	if ( is_admin() ) {
		global $wp_post_types;
		foreach ( [ 'guest-author', 'podcast-post', 'show-post', 'external-link' ] as $post_type ) {
			if ( ! empty( $wp_post_types[ $post_type ] ) ) {
				$wp_post_types[ $post_type ]->exclude_from_search = false;
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\allow_searching_post_types_in_admin', 15 );

/**
 * Ensure some FM elements get triggered after Gutenberg has fully loaded.
 */
function gutenberg_shim() {
	$js = <<<EOT
<script>
	// Ensure everything has loaded.
	window.addEventListener('load', function() {
		if (wp.domReady) {
			wp.domReady(function() {
				jQuery('.fm-wrapper').trigger('fm_added_element');
				jQuery(document).on('click', '.postbox', function() {
					jQuery('.fm-wrapper').trigger('fm_added_element');
				});
			});
		}
	});
</script>
EOT;

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $js;
}
add_filter( 'admin_footer', __NAMESPACE__ . '\gutenberg_shim' );

/**
 * Remove unused menu items.
 */
function remove_menu_pages() {
	remove_menu_page( 'edit.php?post_type=feedback' );
	remove_menu_page( 'edit.php?post_type=npr_story_post' );
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
	// phpcs:disable WordPress.WP.GlobalVariablesOverride.OverrideProhibited
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
	// phpcs:enable

	return $parent_file;
}
add_filter( 'parent_file', __NAMESPACE__ . '\set_current_menu' );
