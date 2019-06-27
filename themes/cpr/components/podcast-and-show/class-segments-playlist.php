<?php
/**
 * Show and Podcast Segments_Playlist component.
 *
 * @package CPR
 */

namespace CPR\Components\Podcast_And_Show;

/**
 * Show and Podcast Segments_Playlist
 */
class Segments_Playlist extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'segments-playlist';

	/**
	 * Show and Podcast Header default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'heading'  => __( 'Listen Now', 'cpr' ),
			'title'    => '',
			'duration' => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$segment_ids = get_post_meta( $this->get_post_id(), 'show_segment_ids', true );

		// Invalidate if no segments.
		if ( empty( $segment_ids ) ) {
			return $this->set_invalid();
		}

		$audio_metadata = $this->get_audio_metadata();

		foreach ( $segment_ids as $idx => $segment_id ) {
			$this->append_child(
				( new Segment() )
					->set_post( $segment_id )
					->set_config( 'segment_index', $idx + 1 )
			);
		}

		$this->append_child(
			( new \CPR\Components\Audio\Play_Pause_Button() )
				->merge_config(
					[
						'src'   => $audio_metadata['src'] ?? '',
						'title' => [ $audio_metadata['title'] ?? '' ],
					]
				)
				->set_theme( 'inverse' )
		);

		$this->merge_config(
			[
				'eyebrow' => sprintf(
					// translators: %1$s audio duration.
					'Episode | %1$s',
					$audio_metadata['duration']
				),
				'title'   => $this->wp_post_get_title(),
			]
		);

		return $this;
	}
}
