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

/**
 * Get podcast term ids by section.
 *
 * @param string|array $section_slugs Section slug, or array of section slugs.
 * @return array Podcast term ids that correspond to the given slug(s).
 */
function get_podcast_term_ids_by_section( $section_slugs ) {

    // @todo Add some transient caching here.
    $term_ids = [];

    $linked_posts = new \WP_Query(
        [
            'post_type' => 'podcast-post',
            'tax_query' => [
                [
                    'taxonomy' => 'section',
                    'field'    => 'slug',
                    'terms'    => $section_slugs, // This can be a single string, or an array of strings.
                ],
            ],
        ]
    );

    foreach ( ( $linked_posts->posts ?? [] ) as $linked_post ) {
        $term_ids[] = \Alleypack\Term_Post_Link::get_term_from_post( $linked_post->ID );
    }

    return $term_ids;
}
