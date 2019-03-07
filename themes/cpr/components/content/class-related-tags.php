<?php
/**
 * Related Tags component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Related Tags class.
 */
class Related_Tags extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'related-tags';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading' => __( 'Related Tags', 'cpr' ),
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$tags = (array) get_the_tags( $this->get_post_id() );

		foreach ( $tags as $tag ) {
			$this->append_child(
				( new \WP_Components\Term() )
					->set_term( $tag )
					->set_name( 'related-tag' )
			);
		}
		return $this;
	}
}
