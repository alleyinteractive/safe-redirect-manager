<?php
/**
 * Station Playlist component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Station Playlist.
 */
class Station_Playlist extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'station-playlist';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading'      => __( 'Playlist', 'cpr' ),
			'heading_link' => home_url(),
		];
	}

	/**
	 * Set items for the playlist.
	 *
	 * @param int $count Number of playlist items to set.
	 * @return self
	 */
	public function set_playlist_item_components( int $count = 4 ) : self {
		for ( $i = 0; $i < $count; $i++ ) {
			$this->children[] = $this->create_playlist_item();
		}
		return $this;
	}

	/**
	 * Create a playlist item.
	 *
	 * @todo Use real data from an API.
	 * @return Playlist_Item
	 */
	public function create_playlist_item() : Playlist_Item {
		return ( new \CPR\Components\Audio\Playlist_Item() )
			->merge_config(
				[
					'name'         => __( 'Kevin Puts', 'cpr' ),
					'performed_by' => __( 'Fort Worth Symphony Orchestra/Miguel Harth-Bedoya', 'cpr' ),
					'time'         => '1:18 PM',
					'title'        => __( 'Symphony #3: III "it\'s not meant to be a strife"', 'cpr' ),
				]
			);
	}
}
