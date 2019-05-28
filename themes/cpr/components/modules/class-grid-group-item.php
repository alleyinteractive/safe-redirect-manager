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
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		if ( 'guest-author' === $this->post->post_type ) {
			$this->set_theme( 'hosts' );
		} else {
			$this->append_child(
				( new \WP_Components\Component() )
					->set_name( 'excerpt' )
					->set_config( 'content', get_post_meta( $this->get_post_id(), 'description', true ) )
			);
			$this->set_theme( 'testing' );
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

		$this->set_eyebrow();

		$this->wp_post_set_featured_image( 'grid-group-item' );

		return $this;
	}
}
