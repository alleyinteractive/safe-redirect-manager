<?php
/**
 * Podcast and Show Single Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Podcast and Show template.
 */
class Podcast_And_Show extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'podcast-and-show-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body = new \WP_Components\Body();
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
		$data = get_post_meta( $this->get_post_id(), 'settings', true );
		return [
			/**
			 * Header.
			 */
			( new \CPR\Components\Podcast_And_Show\Header() )->set_post( $this->post ),

			/**
			 * Highlighted episodes.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->set_config( 'heading', __( 'Highlighted Episodes', 'cpr' ) )
				->parse_from_fm_data( $data['highlighted_content'] ?? [], 4 )
				->set_theme( 'gridLarge' )
				->set_child_themes(
					[
						'content-item' => 'grid',
						'title'        => 'grid',
						'eyebrow'      => 'small',
					]
				),

			( new \CPR\Components\Modules\Grid_Group() )
				->parse_from_fm_data( (array) get_post_meta( $this->get_post_id(), 'hosts', true ) ),

		];
	}
}
