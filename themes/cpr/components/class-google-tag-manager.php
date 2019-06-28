<?php
/**
 * Google Tag Manager component.
 *
 * @package WP_Components
 */

namespace CPR\Components;

/**
 * Google Tag Manager.
 */
class Google_Tag_Manager extends \WP_Components\Integrations\Google_Tag_Manager {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'google-tag-manager';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'container_id' => '',
			'data_layer'   => [
				'content_type' => '',
				'story_id'     => '',
				'section'      => '',
				'tags'         => [],
				'headline'     => '',
			],
		];
	}

	/**
	 * Set page meta for data layer and meta tags in head.
	 *
	 * @param \WP_Query           $wp_query WP_Query object.
	 * @param \WP_Components\Head $head Head component for appending meta tags.
	 * @return Freestar_Provider
	 */
	public function set_meta_from_query( $wp_query, $head ) : self {
		$meta = [
			'author'        => $this->get_author( $wp_query ),
			'category'      => $this->get_category( $wp_query ),
			'has_audio'     => $this->has_audio( $wp_query ),
			'programs'      => $this->get_programs( $wp_query ),
			'datePublished' => $this->get_publish_date( $wp_query ),
			'story_id'      => $this->get_story_id( $wp_query ),
			'tags'          => $this->get_tags( $wp_query ),
		];

		// Add meta tags.
		foreach ( $meta as $name => $content ) {
			$head->add_tag(
				'meta',
				[
					'name'    => $name,
					'content' => is_array( $content ) ? esc_attr( implode( ',', $content ) ) : esc_attr( $content ),
				]
			);
		}

		// Add data to data layer.
		return $this->merge_config(
			[
				'data_layer' => [
					'author'        => $meta['author'],
					'content_type'  => $this->get_content_type( $wp_query ),
					'category'      => $meta['category'],
					'datePublished' => $meta['datePublished'],
					'has_audio'     => $meta['has_audio'],
					'headline'      => $this->get_headline( $wp_query ),
					'programs'      => $meta['programs'],
					'section'       => $this->get_section( $wp_query ),
					'story_id'      => $meta['story_id'],
					'tags'          => $meta['tags'],
				],
			]
		);
	}

	/**
	 * Get author.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return array
	 */
	public function get_author( $wp_query ) : ?array {
		// Set bylines.
		if ( $wp_query->is_single() ) {
			$bylines = \WP_Components\Byline::get_post_bylines( $wp_query->post->ID );

			if ( ! empty( $bylines ) && is_array( $bylines ) ) {
				$author_names = array_map(
					function( $byline ) {
						return $byline->get_config( 'name' );
					},
					$bylines
				);

				return $author_names;
			}
		}

		return null;
	}

	/**
	 * Get publish date.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_publish_date( $wp_query ) : ?string {
		// Set bylines.
		if ( $wp_query->is_single() ) {
			return get_the_date( 'c', $wp_query->post->ID ?? 0 );
		}

		return null;
	}

	/**
	 * Get value for content type targeting.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_content_type( $wp_query ) : ?string {
		switch ( true ) {
			// Homepage.
			case 'landing-page' === $wp_query->get( 'dispatch' ):
				if ( 'homepage' === $wp_query->get( 'landing-page-type' ) ) {
					return 'homepage';
				}

				return 'landing-page-' . $wp_query->get( 'landing-page-type' );

			// Terms.
			case $wp_query->is_tax():
			case $wp_query->is_tag():
			case $wp_query->is_category():
				return 'section';

			// Search.
			case $wp_query->is_search():
				return 'search';

			// Just get the post type.
			case ! empty( $wp_query->post->ID ):
				return get_post_type( $wp_query->post->ID );

			default:
				return null;
		}
	}

	/**
	 * Get value for content id targeting.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_story_id( $wp_query ) : ?string {
		if ( $wp_query->is_single() ) {
			return $wp_query->post->ID;
		}

		return null;
	}

	/**
	 * Get programs.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_programs( $wp_query ) : ?string {
		if (
			$wp_query->is_singular( 'podcast-episode' )
			|| $wp_query->is_singular( 'show-segment' )
			|| $wp_query->is_singular( 'show-episode' )
		) {
			$shows = get_the_terms( $wp_query->queried_object->ID ?? 0, 'show' );

			return $shows[0]->name ?? null;
		}

		if ( is_tax( 'show' ) ) {
			return $wp_query->queried_object->name ?? null;
		}

		return null;
	}

	/**
	 * Get tags.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return array
	 */
	public function get_tags( $wp_query ) : array {
		// Single article.
		if ( $wp_query->is_single() ) {
			$tags = get_the_tags( $wp_query->queried_object->ID ?? 0 );

			return array_map(
				function( $tag ) {
					return $tag->name;
				},
				$tags
			);
		}

		// Tag landing.
		if ( $wp_query->is_tag() ) {
			return [ $wp_query->queried_object->name ];
		}

		return [];
	}

	/**
	 * Check if post has audio.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return int
	 */
	public function has_audio( $wp_query ) : int {
		if ( $wp_query->is_single() ) {
			$audio_id = get_post_meta( $wp_query->queried_object->ID ?? 0, 'audio_id', true );

			return ! empty( $audio_id ) ? 1 : 0;
		}

		return 0;
	}

	/**
	 * Get category.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_category( $wp_query ) : ?string {
		// Single article.
		if ( $wp_query->is_single() ) {
			$categories = get_the_category( $wp_query->queried_object->ID ?? 0 );

			return $categories[0]->name ?? null;
		}

		// Tag landing.
		if ( $wp_query->is_category() ) {
			return $wp_query->queried_object->name ?? null;
		}

		return null;
	}

	/**
	 * Get headline, if applicable
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_headline( $wp_query ) : ?string {
		if ( $wp_query->is_single() ) {
			return $wp_query->post->post_title ?? '';
		}

		return null;
	}

	/**
	 * Get section, if applicable.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return string
	 */
	public function get_section( $wp_query ) : ?string {
		// Single article.
		if ( $wp_query->is_single() ) {
			$sections = wp_get_post_terms( $wp_query->post->ID, 'section' );

			return $sections[0]->name ?? null;
		}

		// Section taxonomy landing.
		if ( $wp_query->is_tax( 'section' ) ) {
			return $wp_query->queried_object->name ?? null;
		}

		return null;
	}
}
