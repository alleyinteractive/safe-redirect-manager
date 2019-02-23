<?php
/**
 * Calendar Item component.
 *
 * @package CPR
 */

namespace CPR\Component\Events;

/**
 * Calendar Item.
 */
class Calendar_Item extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'calendar-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'day'        => '',
			'location'   => '',
			'month'      => '',
			'start_time' => '',
			'title'      => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 */
	public function post_has_set() {
		$this->set_title(); // @todo update to use $this->wp_post_set_title();
		// @todo parse the date and add to config.
		$this->merge_config(
			[
				'permalink' => get_permalink( $this->post->ID ), // @todo use wp_post_get_permalink
				'type'      => $this->post->post_type ?? 'post',
				'location'  => get_post_meta( $this->post->ID, 'location', true ),
			]
		);
	}
}
