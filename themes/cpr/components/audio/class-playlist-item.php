<?php
/**
 * Playlist Item component.
 *
 * @package CPR
 */

namespace CPR\Component\Audio;

/**
 * Playlist Item.
 */
class Playlist_Item extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'playlist-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'album'        => '', // OpenAir.
			'name'         => '', // The personality (OpenAir) or composer (Classical).
			'performed_by' => '', // Classical.
			'time'         => '',
			'title'        => '',
		];
	}
}
