<?php
/**
 * Calendar Item component.
 *
 * @package CPR
 */

namespace CPR\Components\Widgets;

/**
 * Calendar Item.
 */
class Content_List_Item extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'widget-content-list-item';

	/**
	 * Available themes for this component. If you attempt to set a theme that is
	 * not in this array, it will fail (and fall back to 'default').
	 *
	 * @var array.
	 */
	public $themes = [
		'default', // Used for a standard river of posts.
		'event', // Used to display a river of upcoming events.
		'external-link', // Use these as general-purpose advertisements.
	];

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'day'        => '',
			'location'   => '',
			'month'      => '',
			'permalink'  => '',
			'start_time' => '',
			'title'      => '',
			'type'       => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		$this->wp_post_set_title();
		$this->wp_post_set_permalink();

		switch ( $this->post->post_type ) {
			case 'tribe_events':
				$this->set_theme( 'event' );

				// @todo Setup additional configs.
				$this->merge_config(
					[
						'location'   => get_post_meta( $this->post->ID, 'location', true ),
					]
				);
				break;

			case 'external-link':
				$this->set_theme( 'external-link' );
				break;
		}

		return $this;
	}
}
