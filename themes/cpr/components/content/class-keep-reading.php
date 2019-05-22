<?php
/**
 * Keep Reading component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

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
	public function default_config() : array {
		return [
			'heading' => __( 'Keep Reading', 'cpr' ),
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$post_ids = array_filter( (array) get_post_meta( $this->get_post_id(), 'keep_reading_ids', true ) );

		foreach ( $post_ids as $post_id ) {
			$this->append_child(
				( new \CPR\Components\Content\Heading() )
					->set_post( $post_id )
					->set_theme( 'related' )
			);
		}

		return $this;
	}
}
