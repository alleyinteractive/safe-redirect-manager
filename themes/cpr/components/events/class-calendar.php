<?php
/**
 * Calendar component.
 *
 * @package CPR
 */

namespace CPR\Components\Events;

/**
 * Calendar.
 */
class Calendar extends \WP_Components\Component {

	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'calendar';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading'      => __( 'Concert Calendar', 'cpr' ),
			'heading_link' => home_url( 'events' ),
		];
	}

	/**
	 * Create a calendar item.
	 *
	 * @param number $content_item_id Post ID for content item.
	 * @return \CPR\Components\Events\Calendar_Item
	 */
	public function create_calendar_item( $content_item_id ) : Calendar_Item {

		// Track content item ID as already used.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $content_item_id );

		return ( new Calendar_Item() )
			->set_post( $content_item_id );
	}

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param array   $fm_data       Stored Fieldmanager data.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
	 * @return Calendar
	 */
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Calendar {
		$this->set_config( 'heading', (string) ( $fm_data['heading'] ?? $this->get_config( 'heading' ) ) );

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = $this->create_calendar_item( $content_item_id );
		}

		return $this;
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading'          => new \Fieldmanager_TextField(
				[
					'label'         => __( 'Heading', 'cpr' ),
					'default_value' => $this->get_config( 'heading' ),
				]
			),
			'content_item_ids' => new \Fieldmanager_Zone_Field(
				[
					'label'      => __( 'Events', 'cpr' ),
					'post_limit' => 4,
					'query_args' => [
						'post_type' => [ 'event' ],
					],
				]
			),
		];
	}
}
