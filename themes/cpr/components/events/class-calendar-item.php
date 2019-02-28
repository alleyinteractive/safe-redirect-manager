<?php
/**
 * Calendar Item component.
 *
 * @package CPR
 */

namespace CPR\Components\Events;

/**
 * Calendar Item.
 */
class Calendar_Item extends \WP_Components\Component {

	use \WP_Components\WP_Post;

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
	public function default_config() : array {
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
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$timestamp = get_post_meta( $this->post->ID, 'start_datetime', true );

		$this->wp_post_set_title();
		$this->wp_post_set_permalink();
		$this->merge_config(
			[
				'type'       => $this->post->post_type,
				'location'   => get_post_meta( $this->post->ID, 'location', true ),
				'day'        => date( 'd', $timestamp ),
				'month'      => date( 'M', $timestamp ),
				'start_time' => date( 'g:i a', $timestamp ),
			]
		);
		return $this;
	}
}
