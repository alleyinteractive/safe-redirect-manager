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
				->set_config( 'heading', __( 'Employment Opportunities', 'cpr' ) )
				->append_children(
					[
						/**
						 * Job archive.
						 */
						( new \CPR\Components\Modules\Content_List() )
							->set_theme( 'riverFull' )
							->set_config( 'show_excerpt', true )
							->parse_from_wp_query( $this->query )
							->set_child_themes(
								[
									'content-item' => 'river',
									'title'        => 'grid',
								]
							),

						$this->get_footer_disclaimer(),

						/**
						 * Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' ),

						/**
						 * Pagination
						 */
						( new \WP_Components\Pagination() )
							->set_config( 'url_params_to_remove', [ 'path', 'context' ] )
							->set_config( 'base_url', "/jobs/" )
							->set_query( $this->query ),
					]
				),
		];
	}

	/**
	 * Get the job archive footer component.
	 *
	 * @return null|\WP_Components\HTML
	 */
	public function get_footer_disclaimer() : ?\WP_Components\HTML {

		$footer_content = get_option( 'cpr-settings' )['careers']['footer_content'] ?? '';

		if ( empty( $footer_content ) ) {
			return null;
		}

		return ( new \WP_Components\HTML() )
			->set_config( 'content', $footer_content );
	}
}
