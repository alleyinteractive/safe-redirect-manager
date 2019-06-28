<?php
/**
 * Audio block component logic.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Modify the Audio block component attributes to add the title and artist.
 *
 * @param \WP_Components\Component $component Audio block component.
 * @return object Updated component instance
 */
function audio_block_component( $component ) {
	$audio_id   = $component->get_config( 'id' );
	$audio_meta = wp_get_attachment_metadata( $audio_id );

	if ( ! empty( $audio_id ) ) {
		$component->merge_config(
			[
				'title'  => get_the_title( $audio_id ),
				'artist' => $audio_meta['artist'] ?? '',
			]
		);
	}

	return $component;
};

add_filter( 'wp_components_dynamic_block', __NAMESPACE__ . '\audio_block_component', 10, 4 );
