<?php
/**
 * Social Links component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Social Links.
 */
class Social_Links extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'social-links';

/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() {
		return [
			( new Social_Links_Item() )
				->merge_config(
					[
						'type' => 'facebook',
						'url' => '#'
					]
				),
			( new Social_Links_Item() )
			->merge_config(
				[
					'type' => 'twitter',
					'url' => '#'
				]
			),
			( new Social_Links_Item() )
				->merge_config(
					[
						'type' => 'instagram',
						'url' => '#'
					]
			)
		];
	}
}
