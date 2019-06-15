<?php
/**
 * Streaming Playlist Results Component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Streaming Playlist Results component.
 */
class Streaming_Playlist_Results extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'streaming-playlist-results';

	/**
	 * Mapping of sections to their corresponding livestream URLs.
	 *
	 * @var string
	 */
	public $endpoint_map = [
		'classical' => [
			'playlist_endpoint' => 'https://playlist.cprnetwork.org/api/playlistCL',
			'search_endpoint'   => 'https://playlist.cprnetwork.org/api/searchCL',
		],
		'indie'     => [
			'playlist_endpoint' => 'https://playlist.cprnetwork.org/api/playlistCO',
			'search_endpoint'   => 'https://playlist.cprnetwork.org/api/searchCO',
		],
	];

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'station'           => '',
			'playlist_endpoint' => '',
			'search_endpoint'   => '',
		];
	}

	/**
	 * Set config for a given station.
	 *
	 * @param string $station Station to be used for this stream's data.
	 * @return self
	 */
	public function set_config_for_station( $station ) : self {
		$stream_data = $this->endpoint_map[ $station ];

		return $this->merge_config(
			array_merge(
				[
					'station' => $station,
				],
				$stream_data
			)
		);
	}
}
