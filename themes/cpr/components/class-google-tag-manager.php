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
				'content_id'   => '',
				'channel'      => '',
				'tags'         => [],
				'headline'     => '',
			],
		];
	}

	/**
	 * Set targeting arguments from wp_query.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return Freestar_Provider
	 */
	public function set_data_layer_from_query( $wp_query ) : self {
		return $this->merge_config(
			[
				'data_layer' => [
					'content_type' => $this->get_content_type( $wp_query ),
					'content_id'   => $this->get_content_id( $wp_query ),
					'tags'         => $this->get_tags( $wp_query ),
					'headline'     => $this->get_headline( $wp_query ),
				],
			]
		);
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
			case (
				'landing-page' === $wp_query->get( 'dispatch' )
				&& 'homepage' === $wp_query->get( 'landing-page-type' )
			):
				return 'homepage';

			// Terms.
			case $wp_query->is_tax():
			case $wp_query->is_tag():
			case $wp_query->is_category():
				return 'section';

			// Single.
			case $wp_query->is_singular( 'post' ):
				return 'article';

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
	public function get_content_id( $wp_query ) : ?string {
		if ( $wp_query->is_single() ) {
			return $wp_query->post->ID;
		}

		return null;
	}

	/**
	 * Get value for tags targeting.
	 *
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return array
	 */
	public function get_tags( $wp_query ) : array {
		// Single article.
		if ( $wp_query->is_single() ) {
			$tags = wp_get_post_tags( $wp_query->queried_object->ID ?? 0 );

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
}
