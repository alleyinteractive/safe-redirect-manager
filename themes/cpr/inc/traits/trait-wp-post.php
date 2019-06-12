<?php
/**
 * WP_Post trait.
 *
 * @package CPR
 */

namespace CPR;

/**
 * WP_Post trait.
 */
trait WP_Post {

	/**
	 * Set publish date.
	 */
	public function set_publish_date() {
		$this->set_config( 'publish_date', get_the_date( 'F j, Y', $this->get_post_id() ) );
	}

	/**
	 * Set the eyebrow.
	 *
	 * @param array $additional_config Additional configuration to add to Eyebrow component.
	 */
	public function set_eyebrow( $additional_config = [] ) {
		$eyebrow = ( new \CPR\Components\Content\Eyebrow() )
			->merge_config( $additional_config );

		switch ( $this->post->post_type ?? '' ) {
			case 'post':
				// Use primary category as the eyebrow.
				$primary_category_component = $this->get_primary_category_component();
				if ( $primary_category_component->is_valid_term() ) {
					$eyebrow->merge_config(
						[
							'eyebrow_label' => $primary_category_component->get_config( 'name' ),
							'eyebrow_link'  => $primary_category_component->get_config( 'link' ),
						]
					);
				} else {

					// Use section as the eyebrow.
					$section_component = $this->get_section_component();
					if ( $section_component->is_valid_term() ) {
						$eyebrow->merge_config(
							[
								'eyebrow_label' => $section_component->get_config( 'name' ),
								'eyebrow_link'  => $section_component->get_config( 'link' ),
							]
						);
					}
				}

				$this->append_child( $eyebrow );
				break;

			case 'podcast-episode':
				$podcast_terms = wp_get_post_terms( $this->get_post_id(), 'podcast' );

				if ( ! empty( $podcast_terms ) && $podcast_terms[0] instanceof \WP_Term ) {
					$eyebrow->merge_config(
						[
							'eyebrow_label' => $podcast_terms[0]->name,
							'eyebrow_link'  => get_term_link( $podcast_terms[0], $podcast_terms[0]->taxonomy ),
						]
					);
				}

				$this->append_child( $eyebrow );
				break;

			case 'podcast-post':
			case 'show-post':
				// Use section as the eyebrow.
				$section_component = $this->get_section_component();
				if ( $section_component->is_valid_term() ) {
					$eyebrow->merge_config(
						[
							'eyebrow_label' => $section_component->get_config( 'name' ),
							'eyebrow_link'  => $section_component->get_config( 'link' ),
						]
					);
				}

				$this->append_child( $eyebrow );
				break;

			case 'external-link':
				// Get a custom label and link from meta.
				$label = (string) get_post_meta( $this->get_post_id(), 'eyebrow_label', true );
				$link  = (string) get_post_meta( $this->get_post_id(), 'eyebrow_link', true );

				// Validate the fields and append as an eyebrow.
				if ( ! empty( $link ) && ! empty( $label ) ) {
					$eyebrow->merge_config(
						[
							'eyebrow_label' => (string) get_post_meta( $this->get_post_id(), 'eyebrow_label', true ),
							'eyebrow_link'  => (string) get_post_meta( $this->get_post_id(), 'eyebrow_link', true ),
						]
					);
					$this->append_child( $eyebrow );
				}
				break;
		}
	}

	/**
	 * Create byline components and add to children.
	 */
	public function set_byline() {
		$bylines = \CPR\Components\Avatar_Byline::get_post_bylines( $this->get_post_id() );
		$this->append_children( $bylines );
	}

	/**
	 * Create byline components and add to children.
	 */
	public function get_audio_metadata() {
		$audio_id = get_post_meta( $this->post->ID, 'audio_id', true );

		if ( empty( $audio_id ) ) {
			return [];
		}

		$meta = wp_get_attachment_metadata( $audio_id );
		$src  = wp_get_attachment_url( $audio_id );

		return [
			'album'    => $meta['album'] ?? '',
			'artist'   => $meta['artist'] ?? '',
			'title'    => get_the_title( $audio_id ),
			'src'      => $src,
			'duration' => $meta['length_formatted'] ?? false,
		];
	}

	/**
	 * Get the primary category component.
	 *
	 * @return null|\WP_Components\Term
	 */
	public function get_primary_category_component() {
		$category_id = get_post_meta( $this->get_post_id(), 'primary_category_id', true );
		return ( new \WP_Components\Term() )->set_term( $category_id );
	}

	/**
	 * Get the section component.
	 *
	 * @return null|\WP_Components\Term
	 */
	public function get_section_component() {
		$sections = wp_get_post_terms( $this->get_post_id(), 'section' );
		return ( new \WP_Components\Term() )->set_term( $sections[0] ?? null );
	}

	/**
	 * Get the section slug.
	 *
	 * @return string
	 */
	public function get_section_slug() {
		$section_component = $this->get_section_component();
		return $section_component->wp_term_get_slug();
	}
}
