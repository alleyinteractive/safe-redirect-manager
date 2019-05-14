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
	 * @param int    $count Number of playlist items to set.
	 * @param string $type  Type of playlist items to generate.
	 * @return self
	 */
	public function set_playlist_item_components( int $count = 4, $type = '' ) : self {
		for ( $i = 0; $i < $count; $i++ ) {
			$this->children[] = $this->create_playlist_item( $type );
		}
		return $this;
	}

	/**
	 * Create playlist items.
	 *
	 * @todo Use real data from an API.
	 * @param string $type Type of playlist items to generate.
	 * @return Playlist_Item
	 */
	public function create_playlist_item( $type = '' ) : Playlist_Item {
		switch ( $type ) {
			case 'classical':
				return ( new \CPR\Components\Audio\Playlist_Item() )
					->merge_config(
						[
							'artist'     => __( 'Kevin Puts', 'cpr' ),
							'subheading' => __( 'Fort Worth Symphony Orchestra/Miguel Harth-Bedoya', 'cpr' ),
							'time'       => '1:18 PM',
							'title'      => __( 'Symphony #3: III "it\'s not meant to be a strife"', 'cpr' ),
						]
					);

			case 'indie':
			default:
				return ( new \CPR\Components\Audio\Playlist_Item() )
					->merge_config(
						[
							'artist'     => __( 'Frank Ocean', 'cpr' ),
							'subheading' => __( 'Collection II', 'cpr' ),
							'time'       => '1:18 PM',
							'title'      => __( 'Pink + White', 'cpr' ),
						]
					);
		}
	}
}
