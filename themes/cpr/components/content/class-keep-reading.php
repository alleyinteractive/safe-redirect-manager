<?php
/**
 * Keep Reading component.
 *
 * @package CPR
 */

namespace CPR\Component\Content;

/**
 * Keep Reading class.
 */
class Keep_Reading extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'keep-reading';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'heading' => __( 'Keep Reading', 'cpr' ),
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 */
	public function post_has_set() {
		$post_ids = (array) get_post_meta( $this->get_post_id(), 'keep_reading_ids', true );

		foreach ( $post_ids as $post_id ) {
			$this->append_child(
				( new \CPR\Component\Content_Item() )->set_post( $post_id )
			);
		}
	}
}
