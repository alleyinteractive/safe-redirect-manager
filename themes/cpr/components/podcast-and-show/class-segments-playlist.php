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
			'title'                => __( 'Listen Now', 'cpr' ),
			'episode_title'        => '',
			'total_duration'       => '',
			'first_segment_source' => '',
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

		$this->set_config( 'episode_title', $this->wp_post_get_title() );

		foreach ( $segment_ids as $idx => $segment_id ) {
			$this->append_child(
				( new Segment() )
					->set_post( $segment_id )
					->set_config( 'segment_index', $idx + 1 )
			);
		}

		// Calculate total duration of all episode segments.
		$total_duration = array_reduce(
			$this->children,
			function ( $carry, $segment ) {
				$carry += $segment->get_config( 'duration_raw' );
				return $carry;
			},
			0
		);

		$this->merge_config(
			[
				'total_duration'       => gmdate( 'i:s', $total_duration ),
				'first_segment_source' => $this->children[0]->get_config( 'src' ) ?? '',
			]
		);

		return $this;
	}
}
