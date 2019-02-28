<?php
/**
 * Comments component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Comments class.
 */
class Comments extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'comments';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'label' => __( 'Show Comments', 'cpr' ),
		];
	}
}
