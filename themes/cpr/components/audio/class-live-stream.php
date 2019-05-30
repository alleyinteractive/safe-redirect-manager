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

	use \WP_Components\WP_Term;

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
			'endpoint'   => '',
			'stream_src' => '',
			'slug'       => '',
			'section'      => '',
		];
	}

	/**
	 * Hook into term being set.
	 *
	 * @return self
	 */
	public function term_has_set() : self {
		$slug        = $this->term->slug;
		$stream_data = $this->stream_map[ $slug ];

		return $this->merge_config(
			array_merge(
				[
					'section' => $this->term->name,
					'slug'  => $slug,
				],
				$stream_data
			)
		);
	}
}
