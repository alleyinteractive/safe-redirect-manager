<?php
/**
 * Playlist Item component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

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
	public function default_config() : array {
		return [
			'album'        => '',
			'name'         => '',
			'performed_by' => '',
			'time'         => '',
			'title'        => '',
		];
	}
}
