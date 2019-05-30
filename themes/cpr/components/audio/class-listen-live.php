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
				// @todo should this be term meta perhaps?
				->set_term( get_term_by( 'slug', 'news', 'section' ) ),
			( new \CPR\Components\Audio\Live_Stream() )
				->set_term( get_term_by( 'slug', 'classical', 'section' ) ),
			( new \CPR\Components\Audio\Live_Stream() )
				->set_term( get_term_by( 'slug', 'indie', 'section' ) ),
		];
	}
}
