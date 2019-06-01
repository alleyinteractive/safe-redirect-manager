<?php
/**
 * Slim Navigation Search Bar component.
 *
 * @package CPR
 */

namespace CPR\Components\Search;

/**
 * Class for the Search Bar component.
 */
class Search_Bar extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'search-bar';

	/**
	 * Define the default config of a search bar.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'query' => '',
		];
	}
}
