<?php
/**
 * Underwriter Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Underwriter Archive template.
 */
class Underwriter_Archive extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'underwriter-archive-template';

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
				->set_theme( 'oneColumn' )
				->merge_config(
					[
						'heading'           => __( 'List of CPR Sponsors', 'cpr' ),
						'heading_cta_label' => __( 'Sponsor CPR', 'cpr' ),
						'heading_cta_link'  => site_url( '/become-a-sponsor-underwriter/' ),
					]
				)
				->append_children(
					[
						( new \CPR\Components\Underwriter\Corporate_Partners() )
							->set_query( $this->query ),

						( new \CPR\Components\Underwriter\Directory() ),
					]
				),
		];
	}

	/**
	 * Modify results to include all underwriters.
	 *
	 * @param object $wp_query wp_query object.
	 */
	public static function pre_get_posts( $wp_query ) {
		if ( $wp_query->is_post_type_archive( 'underwriter' ) && ! empty( $wp_query->get( 'irving-path' ) ) ) {
			$wp_query->set( 'posts_per_page', 30 );
			$wp_query->set( 'meta_key', 'is_corporate_partner' );
			$wp_query->set( 'meta_value', true );
		}
	}
}
