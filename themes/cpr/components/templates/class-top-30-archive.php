<?php
/**
 * Top 30 Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Top 30 Archive template.
 */
class Top_30_Archive extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'top-30-archive-template';

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
		$components = $this->get_top_30_components();

		// Pagination.
		$components[] = ( new \CPR\Components\Column_Area() )
			->set_theme( 'oneColumn' )
			->append_child(
				( new \WP_Components\Pagination() )
					->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
					->set_config( 'base_url', '/indie/top-30/' )
					->set_query( $this->query )
			);

		return $components;
	}

	/**
	 * Get Top 30 components.
	 *
	 * @return array
	 */
	public function get_top_30_components() : array {
		$top_30_components = [];
		foreach ( $this->query->posts as $post ) {
			$top_30_components[] = ( new \CPR\Components\Audio\Top_30() )
				->set_post( $post )
				->set_config( 'cta_label', '' )
				->set_config( 'cta_link', '' )
				->set_heading();
		}
		return $top_30_components;
	}
}
