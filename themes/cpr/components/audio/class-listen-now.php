<?php
/**
 * Listen Now component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Listen Now.
 */
class Listen_Now extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'listen-now';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'duration' => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$audio_meta = $this->get_audio_metadata();
		$this->set_config( 'duration', $audio_meta['duration'] );

		return $this->append_child(
			( new \CPR\Components\Audio\Play_Pause_Button() )
				->merge_config(
					[
						'src'   => $audio_meta['src'],
						'title' => [
							$audio_meta['title'],
							$audio_meta['artist'],
							$audio_meta['album'],
						],
					]
				)
				->set_theme( 'player' )
		);
	}
}
