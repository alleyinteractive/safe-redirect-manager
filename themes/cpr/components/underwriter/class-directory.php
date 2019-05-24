<?php
/**
 * Underwriter Directory component.
 *
 * @package CPR
 */

namespace CPR\Components\Underwriter;

/**
 * Underwriter directory.
 */
class Directory extends \WP_Components\Component {

	use \WP_Components\WP_Query;
	use \WP_Components\WP_Term;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'underwriter-directory';

	/**
	 * Underwriter Directory default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		// Get all underwriter categories.
		$underwriter_categories = new \WP_Term_Query(
			[
				'taxonomy' => 'underwriter-category',
				'orderby'  => 'name',
				'order'    => 'ASC',
			]
		);

		// Reduce to only the fields we need.
		$categories = array_filter(
			array_map(
				function( $underwriter_term ) {
					return [
						'name' => $underwriter_term->name,
						'slug' => $underwriter_term->slug,
					];
				},
				$underwriter_categories->terms ?? []
			)
		);

		return [
			'categories' => $categories,
		];
	}

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		return $this;
	}
}
