<?php
/**
 * Listen Live component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Listen Live.
 */
class Listen_Live extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'listen-live';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		return [
			( new \CPR\Components\Audio\Live_Stream() )
				->set_source( 'news' )
				->set_theme( 'listenLive' )
				->merge_config(
					[
						'title'  => __( 'CPR News', 'cpr' ),
						'count' => 1,
					]
				),
			( new \CPR\Components\Audio\Live_Stream() )
				->set_source( 'classical' )
				->set_theme( 'listenLive' )
				->merge_config(
					[
						'title'  => __( 'CPR Classical', 'cpr' ),
						'count' => 1,
					]
				),
			( new \CPR\Components\Audio\Live_Stream() )
				->set_source( 'indie' )
				->set_theme( 'listenLive' )
				->merge_config(
					[
						'title'  => __( 'Indie 102.3', 'cpr' ),
						'count' => 1,
					]
				),
		];
	}
}
