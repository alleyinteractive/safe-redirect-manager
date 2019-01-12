<?php
/**
 * Term helpers.
 *
 * @package CPR
 */

namespace CPR;

// Link podcast taxonomy to its post editorial interface.
\Alleypack\create_term_post_link( 'podcast', 'podcast-post' );

/**
 * Modify podcast term permalinks to include its section.
 *
 * @param  string $termlink Term link URL.
 * @param  object $term     Term object.
 * @param  string $taxonomy Taxonomy slug.
 * @return string           The modified term link.
 */
function modify_podcast_term_link( $termlink, $term, $taxonomy ) {
	// Only modify links for podcasts.
	if ( 'podcast' !== $taxonomy ) {
		return $termlink;
	}

	// Get the section this podcast belongs to and include in the permalink.
	$section_id = get_term_meta( $term->term_id, 'section_term_id', true );
	$section    = get_term( $section_id, 'section' );
	if ( $section instanceof \WP_Term ) {
		return str_replace( '/podcast/', "/{$section->slug}/podcast/", $termlink );
	}

	return $termlink;
}
add_filter( 'term_link', __NAMESPACE__ . '\modify_podcast_term_link', 10, 3 );
