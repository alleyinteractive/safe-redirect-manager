<?php
/**
 * Job Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Job Archive template.
 */
class Job_Archive extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'job-archive-template';

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
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->append_children(
					[
						/**
						 * Job archive.
						 */
						( new \CPR\Components\Modules\Content_List() )
							->parse_from_wp_query( $this->query ),

						/**
						 * Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' ),
					]
				),
		];
	}
}
