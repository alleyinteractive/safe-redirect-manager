<?php
/**
 * Author Archive Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * Author Archive template.
 */
class Author_Archive extends \WP_Component\Component {

	use \WP_Component\Author;
	use \WP_Component\Guest_Author;
	use \WP_Component\WP_Query;
	use \WP_Component\WP_User;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'author-archive-template';

	/**
	 * Hook into post being set.
	 */
	public function query_has_set() {

		// Set the author.
		$this->set_author( $this->get_queried_object() );

		// Begin building the body.
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
				->set_config(
					'heading',
					sprintf(
						// translators: %1$s: Author display name.
						__( 'Author: %1$s', 'cpr' ),
						$this->get_author_display_name()
					)
				),

			/**
			 * Pagination.
			 *
			 * @todo Implement.
			 */
		];
	}
}
