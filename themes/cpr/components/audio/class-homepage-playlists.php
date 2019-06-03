<?php
/**
 * Homepage Playlists component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Homepage Playlists.
 */
class Homepage_Playlists extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'homepage-playlists';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		return [
			/**
			 * Classical Playlist.
			 */
			( new \CPR\Components\Audio\Live_Stream() )
				->set_source( 'classical' )
				->set_theme( 'playlist' )
				->merge_config(
					[
						'count'          => 4,
						'playlist_title' => '',
					]
				),
			/**
			 * Indie Playlist.
			 */
			( new \CPR\Components\Audio\Live_Stream() )
				->set_source( 'indie' )
				->set_theme( 'playlist' )
				->merge_config(
					[
						'count'          => 4,
						'playlist_title' => '',
					]
				),
		];
	}
}
