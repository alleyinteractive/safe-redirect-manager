<?php
/**
 * Live Stream component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Live Stream.
 */
class Live_Stream extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'live-stream';

	/**
	 * Mapping of sections to their corresponding livestream URLs.
	 *
	 * @var string
	 */
	public $stream_map = [
		'news'      => [
			'stream_src' => 'https://stream1.cprnetwork.org/cpr1_lo',
			'endpoint'   => 'https://playlist.cprnetwork.org/won_plus3/KCFR.json',
		],
		'classical' => [
			'stream_src' => 'https://stream1.cprnetwork.org/cpr2_lo',
			'endpoint'   => 'https://playlist.cprnetwork.org/won_plus3/KVOD.json',
		],
		'indie'     => [
			'stream_src' => 'https://stream1.cprnetwork.org/cpr3_lo',
			'endpoint'   => 'https://playlist.cprnetwork.org/won_plus3/KVOQ.json',
		],
	];

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'endpoint'       => '',
			'count'          => 0, // Display all in feed.
			'stream_src'     => '',
			'source'         => '',
			'title'          => '',
			'title_link'     => home_url(),
			'playlist_title' => __( 'Playlist', 'cpr' ),
		];
	}

	/**
	 * Hook into term being set.
	 *
	 * @param string $source Source to be used for this stream's data.
	 * @return self
	 */
	public function set_source( $source ) : self {
		$stream_data = $this->stream_map[ $source ];

		return $this->merge_config(
			array_merge(
				[
					'source' => $source,
				],
				$stream_data
			)
		);
	}
}
