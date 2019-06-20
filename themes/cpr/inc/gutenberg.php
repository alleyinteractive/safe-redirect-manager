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
	if ( in_array( $post_type, [ 'external-link', 'newsletter-single' ], true ) ) {
		return false;
	}
	return $current_status;
}
add_filter( 'use_block_editor_for_post_type', __NAMESPACE__ . '\disable_gutenberg_for_selected_post_type', 10, 2 );

/**
 * Map dynamic blocks to local components.
 *
 * @param object $component Component created by Gutenberg Content.
 * @return object
 */
function map_dynamic_blocks( $component ) {
	switch ( $component->name ) {
		/**
		 * Wrap the children highlighted content children with a
		 * Gutenberg_Content block for easy output and formatting.
		 */
		case 'cpr/highlighted-content':
			$component->set_children(
				[
					( new \WP_Components\Gutenberg_Content() )
						->append_children( $component->children ),
				]
			);
			break;
	}
	return $component;
}
add_filter( 'wp_components_dynamic_block', __NAMESPACE__ . '\map_dynamic_blocks' );

/**
 * Enqueue Icon assets.
 */
function enqueue_icons_assets() {
	wp_enqueue_script(
		'font-awesome-v4-shims',
		get_template_directory_uri() . '/assets/font-awesome/v4-shims.min.js',
		array(),
		'5.7.2',
		true
	);

	wp_enqueue_script(
		'font-awesome',
		get_template_directory_uri() . '/assets/font-awesome/all.min.js',
		array( 'font-awesome-v4-shims' ),
		'5.7.2',
		true
	);

	wp_enqueue_script( 'font-awesome' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_icons_assets', 12 );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_icons_assets', 12 );
