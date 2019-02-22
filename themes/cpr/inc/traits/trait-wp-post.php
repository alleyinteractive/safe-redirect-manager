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
	 * Set the title.
	 */
	public function set_title() {
		$this->set_config( 'title', html_entity_decode( (string) get_the_title( $this->post ) ) );
	}

	/**
	 * Set the excerpt.
	 */
	public function set_excerpt() {
		// Modify global state.
		global $post;

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$backup_post = $post;

		// Setup post data for this item.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$post = get_post( $this->post->ID );
		setup_postdata( $post );

		$this->set_config( 'excerpt', html_entity_decode( (string) get_the_excerpt() ) );

		// Undo global modification.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$post = $backup_post;
		setup_postdata( $post );
	}

	/**
	 * Set publish date.
	 */
	public function set_publish_date() {
		$this->set_config( 'publish_date', get_the_date( 'F j, Y', $this->get_post_id() ) );
	}

	/**
	 * Set the eyebrow.
	 */
	public function set_eyebrow() {
		switch ( $this->post->post_type ?? '' ) {
			case 'post':

				// Use primary category as the eyebrow.
				$primary_category_component = $this->get_primary_category_component();
				if ( $primary_category_component->is_valid_term() ) {
					$this->set_config( 'eyebrow_label', $primary_category_component->get_config( 'name' ) );
					$this->set_config( 'eyebrow_link', $primary_category_component->get_config( 'link' ) );
				} else {
					$this->set_config( 'eyebrow_label', __( 'No Primary Category Found', 'cpr' ) );
					$this->set_config( 'eyebrow_link', home_url( '/placeholder/' ) );
				}

				break;

			case 'podcast-episode':
				$podcast_terms = wp_get_post_terms( $this->get_post_id(), 'podcast' );
				if ( ! empty( $podcast_terms ) && $podcast_terms[0] instanceof \WP_Term ) {
					$this->set_config( 'eyebrow_label', $podcast_terms[0]->name );
					$this->set_config( 'eyebrow_link', get_term_link( $podcast_terms[0], $podcast_terms[0]->taxonomy ) );
				}
				break;
		}
	}

	/**
	 * Create byline components and add to children.
	 */
	public function set_byline() {
		$bylines = \WP_Components\Byline::get_post_bylines( $this->get_post_id() );
		$this->append_children( $bylines );
	}

	/**
	 * Create Image component and add to children.
	 *
	 * @param string $size Image size to use for child image component.
	 * @todo add fallback image.
	 */
	public function set_featured_image( $size = 'full' ) {
		$this->append_children(
			[
				( new \WP_Components\Image() )
					->set_post_id( $this->post->ID )
					->set_config_for_size( $size ),
			]
		);
	}

	/**
	 * Set audio.
	 *
	 * @todo Do real things.
	 */
	public function set_audio() {
		// @todo pull the actual article audio.
		$this->set_config( 'audio_url', 'http://google.com/test.mp3' );
		$this->set_config( 'audio_length', 415 );
	}

	/**
	 * Get the primary category component.
	 *
	 * @return null|\WP_Components\Term
	 */
	public function get_primary_category_component() {
		$category_id   = get_post_meta( $this->get_post_id(), 'primary_category_id', true );
		return ( new \WP_Components\Term() )->set_term( $category_id );
	}
}
