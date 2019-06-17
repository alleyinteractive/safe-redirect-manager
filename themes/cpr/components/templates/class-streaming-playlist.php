<?php
/**
 * Streaming Playlist Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Streaming Playlist template.
 */
class Streaming_Playlist extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'streaming-playlist-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		$body           = new \WP_Components\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		$station = $this->query->get( 'station' );
		$today   = date( 'F j, Y', current_time( 'timestamp' ) );

		return [
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->append_children(
					[
						( new \WP_Components\Component() )
							->set_name( 'streaming-playlist-wrapper' )
							->append_children(
								[
									( new \CPR\Components\Audio\Streaming_Playlist_Header() )
										->set_config( 'date', $today ),
									( new \CPR\Components\Audio\Streaming_Playlist_Results() )
										->set_config_for_station( $station ),
								]
							),
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->set_sidebar( "{$station}-sidebar'" ),
					]
				),
		];
	}

	/**
	 * Modify rewrite rules.
	 */
	public static function dispatch_rewrites() {
		\Alleypack\Path_Dispatch()->add_paths(
			[
				[
					'path'    => 'streaming-playlist',
					'rewrite' => [
						'rule'       => '^(classical|indie)/playlist/?$',
						'redirect'   => 'index.php?dispatch=streaming_playlist&station=$matches[1]',
						'query_vars' => 'station',
					],
				],
			]
		);
	}
}
