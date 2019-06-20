<?php
/**
 * Grid Group Item.
 *
 * @package CPR
 */

namespace CPR\Components\Modules;

/**
 * Grid group item.
 */
class Grid_Group_Item extends \WP_Components\Component {

	use \Alleypack\FM_Module;
	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'grid-group-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'permalink' => '',
			'role'      => '',
			'show_name' => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		if ( 'guest-author' === $this->post->post_type ) {
			$this->set_config( 'role', __( 'Host', 'cpr' ) );
			$this->wp_post_set_featured_image( 'grid-group-host' );
		} else {
			$this->wp_post_set_featured_image( 'grid-group-item' );
			$this->append_child(
				( new \WP_Components\Component() )
					->set_name( 'excerpt' )
					->set_config( 'content', get_post_meta( $this->get_post_id(), 'teaser', true ) )
			);
		}

		$this->append_child(
			( new \CPR\Components\Content\Content_Title() )
				->merge_config(
					[
						'content' => $this->wp_post_get_title(),
						'link'    => $this->wp_post_get_permalink(),
					]
				)
		);

		$this->wp_post_set_permalink();

		return $this;
	}
}
