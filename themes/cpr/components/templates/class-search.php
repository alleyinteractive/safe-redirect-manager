<?php
/**
 * Search Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * Search template.
 */
class Search extends \WP_Component\Component {

	use \WP_Component\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'search-template';

	/**
	 * Hook into query being set.
	 */
	public function query_has_set() {
		$body = new \WP_Component\Body();
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
			 * Search results.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'theme', 'grid' )
				->parse_from_wp_query( $this->query )
				->set_config( 'heading', __( 'Search Results for', 'cpr' ) ),

			/**
			 * Pagination.
			 *
			 * @todo Implement.
			 */
		];
	}

	/**
	 * Modify rewrite rules.
	 */
	public static function rewrite_rules() {
		add_rewrite_rule( '^search/?$', 'index.php?s', 'top' );
	}
}
