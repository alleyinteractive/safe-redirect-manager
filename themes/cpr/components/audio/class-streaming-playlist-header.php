<?php
/**
 * Streaming Playlist Header Component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Streaming Playlist Header component.
 */
class Streaming_Playlist_Header extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'streaming-playlist-header';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'date' => '2019-06-15T10:00:00Z',
		];
	}
}
