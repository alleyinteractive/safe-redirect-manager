<?php
/**
 * Show and Podcast Segment component.
 *
 * @package CPR
 */

namespace CPR\Components\Podcast_And_Show;

/**
 * Show and Podcast Segment
 */
class Segment extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'segment';

	/**
	 * Show and Podcast Header default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'segment_index' => '',
			'title'         => '',
			'duration'      => '',
			'duration_raw'  => '',
			'src'           => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$audio_metadata = $this->get_audio_metadata();

		if ( empty( $audio_metadata ) || empty( $audio_metadata['src'] ) ) {
			$this->set_invalid();
		}

		$this->merge_config( $audio_metadata );

		return $this;
	}
}
