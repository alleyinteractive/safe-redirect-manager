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
			'align_item_content'   => 'left',
			'call_to_action_label' => '',
			'call_to_action_link'  => '',
			'eyebrow_label'        => '',
			'eyebrow_link'         => '',
			'eyebrow_size'         => 'small',
			'heading'              => '',
			'heading_border'       => false,
			'heading_link'         => '',
			'heading_cta_label'    => '',
			'heading_cta_link'     => '',
			'image_size'           => '',
			'show_excerpt'         => false,
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
	 * Create a content item.
	 *
	 * @param number $content_item_id Post ID for content item.
	 * @return \CPR\Component\Content_Item
	 */
	public function create_content_item( $content_item_id ) {

		// Track content item ID as already used.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $content_item_id );

		return ( new \CPR\Component\Content_Item() )
			->merge_config(
				[
					'align_content'  => $this->get_config( 'align_item_content' ),
					'theme'          => $this->get_config( 'theme' ),
					'image_size'     => $this->get_config( 'image_size' ),
					'heading_border' => $this->get_config( 'heading_border' ),
					'eyebrow_size'   => $this->get_config( 'eyebrow_size' ),
					'show_excerpt'   => $this->get_config( 'show_excerpt' ),
				]
			)
			->set_post( $content_item_id );
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
		$this->merge_config(
			[
				'call_to_action_label' => (string) ( $fm_data['call_to_action_label'] ?? '' ),
				'call_to_action_link'  => (string) ( $fm_data['call_to_action_link'] ?? '' ),
				'eyebrow_label'        => (string) ( $fm_data['eyebrow_label'] ?? '' ),
				'eyebrow_link'         => (string) ( $fm_data['eyebrow_link'] ?? '' ),
				'heading'              => (string) ( $fm_data['heading'] ?? '' ),
				'heading_link'         => (string) ( $fm_data['heading_link'] ?? '' ),
			]
		);

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = $this->create_content_item( $content_item_id );
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
			$this->children[] = $this->create_content_item( $content_item_id );
		}

		return $this;
	}

	/**
	 * Setup the content items based on Jetpack Related Posts results.
	 *
	 * @param integer $post_id       Post ID.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
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
			$this->children[] = $this->create_content_item( $content_item_id );
		}

		return $this;
	}

	/**
	 * Parse an array of IDs to be used by this component.
	 *
	 * @param array   $ids           Post IDs.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_ids( array $ids, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		// Backfill as needed.
		$ids = $this->backfill_content_item_ids(
			$ids,
			$backfill_to,
			$backfill_args
		);

		foreach ( $ids as $id ) {
			$this->children[] = $this->create_content_item( $id );
		}

		return $this;
	}
}
