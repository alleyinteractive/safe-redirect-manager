<?php
/**
 * All Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * All Archive template.
 */
class All_Archive extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'all-archive-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		$body           = new \WP_Components\Body();
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
		return [
			/**
			 * Column Area
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'one-column' )
				->append_children(
					[
						/**
						 * Content List
						 */
						( new \CPR\Components\Modules\Content_List() )
							->set_config( 'heading', __( 'All CPR articles', 'cpr' ) )
							->set_config( 'image_size', 'grid_item' )
							->set_theme( 'gridLarge' )
							->set_child_themes(
								[
									'content-item'  => 'grid_item',
									'content-title' => 'grid',
									'eyebrow'       => 'small',
								]
							)
							->parse_from_wp_query( $this->query ),

						/**
						 * Pagination.
						 */
						( new \WP_Components\Pagination() )
							->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
							->set_config( 'base_url', '/all/' )
							->set_query( $this->query ),
					]
				),
		];
	}
}
