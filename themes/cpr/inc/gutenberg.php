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
 * Add to array of non-dinamic blocks.
 *
 * @param array $blocks Blocks.
 * @return array
 */
function bypass_dynamic_block( $blocks ) : array {
	$blocks[] = 'pym-shortcode/pym';

	return $blocks;
}
add_filter( 'wp_components_block_render_exceptions', __NAMESPACE__ . '\bypass_dynamic_block' );

/**
 * Hide some taxonomies from displaying the default Gutenberg metabox.
 *
 * @param \WP_REST_Response $response Rest response object.
 * @param \WP_Taxonomy      $taxonomy WP taxonomy object.
 * @return \WP_REST_Response
 */
function hide_taxonomy_ui( \WP_REST_Response $response, \WP_Taxonomy $taxonomy ) : \WP_REST_Response {
	if ( in_array( $taxonomy->name, [ 'category', 'post_tag', 'podcast', 'show' ], true ) ) {
		$response->data['visibility']['show_ui'] = false;
	}
	return $response;
}
add_filter( 'rest_prepare_taxonomy', __NAMESPACE__ . '\hide_taxonomy_ui', 10, 2 );
