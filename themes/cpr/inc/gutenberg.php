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
 * Add to array of non-dynamic blocks.
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
 * Vuhaus doesn't have oembed, so short-circuit the
 * logic and put the URL in an iframe.
 *
 * @param null|string $result The HTML to be used to embed. Default null.
 * @param string      $url    The URL to the content to be embedded.
 * @param array       $args   Arguments passed. Optional, default empty.
 * @return null|string
 */
function vuhaus_embed( $result, $url, $args ) {
	if ( false === strpos( $url, 'https://www.vuhaus.com/embed/v2/videos/' ) ) {
		return $result;
	}
	return "<iframe src={$url}></iframe>";
}
add_filter( 'pre_oembed_result', __NAMESPACE__ . '\vuhaus_embed', 10, 3 );

/**
 * Insert an image's credit to the markup if present.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 * @return string
 */
function render_block( $block_content, $block ) {

	// Bail if it isn't an image block.
	if ( 'core/image' !== ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}

	$credit = get_post_meta( $block['attrs']['id'] ?? 0, 'credit', true );

	if ( empty( $credit ) ) {
		return $block_content;
	}

	// Insert the credit in a span tag after the image.
	return preg_replace(
		'#(<img.*/>)#',
		'$1<span class="image-credit">' . $credit . '</span>',
		$block_content
	);
}
add_filter( 'render_block', __NAMESPACE__ . '\render_block', 10, 3 );


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
