<?php
/**
 * Error 404 component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Class for the Error 404 component.
 */
class Error_404 extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'error-404';

	/**
	 * Error default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'heading' => __( 'We could not find anything for that URL.', 'cpr' ),
			'msg'     => __( 'Try searching for your content below.', 'cpr' ),
			'link'    => __( 'Return to the homepage', 'cpr' ),
		];
	}
}
