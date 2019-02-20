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

	// Check for a cache hit.
	$cache_key       = get_podcast_ids_cache_key( $section_slugs );
	$cached_term_ids = get_transient( $cache_key );

	if ( false !== $cached_term_ids ) {
		return $cached_term_ids;
	}

	// Get the linked podcast posts.
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

	$term_ids = [];

	// Get the corresponding term ID for each linked post.
	foreach ( ( $linked_posts->posts ?? [] ) as $linked_post ) {
		$term_ids[] = \Alleypack\Term_Post_Link::get_term_from_post( $linked_post->ID );
	}

	// Cache the term IDs for 15 minutes.
	if ( ! empty( $term_ids ) ) {
		set_transient( $cache_key, $term_ids, 5 * MINUTE_IN_SECONDS );
	}

	return $term_ids;
}

/**
 * Get the cache key for storing podcast term ids for given section(s).
 *
 * @param string|array $section_slugs Section slug, or array of section slugs.
 * @return string
 */
function get_podcast_ids_cache_key( $section_slugs ) {
	if ( is_array( $section_slugs ) ) {
		sort( $section_slugs );
		$section_slugs = implode( '_', array_unique( $section_slugs ) );
	}

	return 'cpr_podcast_term_ids_' . md5( $section_slugs );
}
