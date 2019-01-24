<?php
/**
 * Content List component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Content List.
 */
class Content_List extends \WP_Components\Component {

	use \Alleypack\FM_Module;
	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-list';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'call_to_action_label' => '',
			'call_to_action_link'  => '',
			'eyebrow_label'        => '',
			'eyebrow_link'         => '',
			'heading'              => '',
			'heading_link'         => '',
			'theme'                => '',
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'call_to_action_label' => new \Fieldmanager_Textfield( __( 'Call to Action Label', 'cpr' ) ),
			'call_to_action_link'  => new \Fieldmanager_Link( __( 'Call to Action Link', 'cpr' ) ),
			'content_item_ids'     => new \Fieldmanager_Zone_Field(),
			'eyebrow_label'        => new \Fieldmanager_Textfield( __( 'Eyebrow Label', 'cpr' ) ),
			'eyebrow_link'         => new \Fieldmanager_Link( __( 'Eyebrow Link', 'cpr' ) ),
			'heading'              => new \Fieldmanager_Textfield( __( 'Heading', 'cpr' ) ),
			'heading_link'         => new \Fieldmanager_Link( __( 'Heading Link', 'cpr' ) ),
		];
	}

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param array   $fm_data       Stored Fieldmanager data.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Content_List {
		$this->set_config( 'call_to_action_label', (string) ( $fm_data['call_to_action_label'] ?? '' ) );
		$this->set_config( 'call_to_action_link', (string) ( $fm_data['call_to_action_link'] ?? '' ) );
		$this->set_config( 'eyebrow_label', (string) ( $fm_data['eyebrow_label'] ?? '' ) );
		$this->set_config( 'eyebrow_link', (string) ( $fm_data['eyebrow_link'] ?? '' ) );
		$this->set_config( 'heading', (string) ( $fm_data['heading'] ?? '' ) );
		$this->set_config( 'heading_link', (string) ( $fm_data['heading_link'] ?? '' ) );

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )->set_post( $content_item_id );
		}

		return $this;
	}

	/**
	 * Parse a WP_Query object to be used by this component.
	 *
	 * @param \WP_Query $wp_query      \WP_Query object.
	 * @param integer   $backfill_to   How many content items should this component
	 *                                 have.
	 * @param array     $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_wp_query( \WP_Query $wp_query, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		// Extract post IDs from the query.
		$content_item_ids = wp_list_pluck( $wp_query->posts ?? [], 'ID' );

		// Backfill as needed.
		$content_item_ids = $this->backfill_content_item_ids(
			$content_item_ids,
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )->set_post( $content_item_id );
		}
		return $this;
	}

	/**
	 * Setup the content items based on Jetpack Related Posts results.
	 *
	 * @param integer   $post_id       Post ID.
	 * @param integer   $backfill_to   How many content items should this component
	 *                                 have.
	 * @param array     $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_jetpack_related( $post_id, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		$content_item_ids = [];

		if (
			class_exists( '\Jetpack_RelatedPosts' )
			&& method_exists( '\Jetpack_RelatedPosts', 'init_raw' )
		) {

			// Query Jetpack Related Posts.
			$related_content = (array) \Jetpack_RelatedPosts::init_raw()
				->get_for_post_id(
					$post_id,
					[
						'size' => $backfill_to,
					]
				);

			// Extract IDs from results.
			if ( ! empty( $related_content ) ) {
				$content_item_ids = wp_list_pluck( $related_content, 'id' );
			}
		}

		// Backfill as needed.
		$content_item_ids = $this->backfill_content_item_ids(
			$content_item_ids,
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )->set_post( $content_item_id );
		}

		return $this;
	}
}
