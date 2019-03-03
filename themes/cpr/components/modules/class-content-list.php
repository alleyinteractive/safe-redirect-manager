<?php
/**
 * Content List component.
 *
 * @package CPR
 */

namespace CPR\Components\Modules;

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
			'background_color'     => '',
			'call_to_action_label' => '',
			'call_to_action_link'  => '',
			'eyebrow_label'        => '',
			'eyebrow_link'         => '',
			'heading'              => '',
			'heading_link'         => '',
			'image_size'           => '',
			'show_excerpt'         => false,
			'theme_name'           => '',
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
	 * @return \CPR\Components\Content_Item
	 */
	public function create_content_item( $content_item_id ) {

		// Track content item ID as already used.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $content_item_id );

		return ( new \CPR\Components\Content_Item() )
			->merge_config(
				[
					'image_size'   => $this->get_config( 'image_size' ),
					'show_excerpt' => $this->get_config( 'show_excerpt' ),
				]
			)
			->set_post( $content_item_id );
	}

	/**
	 * Create an eyebrow component.
	 *
	 * @param string $label Text content of eyebrow.
	 * @param string $link URL for eyebrow to link to.
	 * @return void
	 */
	public function set_eyebrow( $label, $link = '' ) {
		if ( ! empty( $label ) ) {
			$this->append_child(
				( new \CPR\Components\Content\Eyebrow() )
					->set_name( 'content-list-eyebrow' )
					->set_theme( 'black' )
					->merge_config(
						[
							'eyebrow_label' => $label,
							'eyebrow_link'  => $link,
						]
					)
			);
		}
	}

	/**
	 * Set content list heading from FM data.
	 *
	 * @param array $fm_data Stored Fieldmanager data.
	 * @return self
	 */
	public function set_heading_from_fm_data( $fm_data ) {
		return $this->merge_config(
			[
				'heading'              => (string) ( $fm_data['heading'] ?? '' ),
				'heading_link'         => (string) ( $fm_data['heading_link'] ?? '' ),
			]
		);
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
				'call_to_action_link'  => (string) ( $fm_data['call_tso_action_link'] ?? '' ),
			]
		);

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		$this->set_eyebrow(
			(string) ( $fm_data['eyebrow_label'] ?? $this->get_config( 'eyebrow_label' ) ),
			(string) ( $fm_data['eyebrow_link'] ?? $this->get_config( 'eyebrow_link' ) )
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

	/**
	 * Add content items with featured video children from a list of post IDs,
	 * backfilling as needed.
	 *
	 * @param array   $ids           Post IDs.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function add_video_items( array $ids, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		// Backfill as needed.
		$ids = $this->backfill_content_item_ids(
			$ids,
			$backfill_to,
			$backfill_args
		);

		// Generate content items, each with an HTML child for the video embed.
		foreach ( $ids as $id ) {
			// @todo Get the video URL dynamically for each post.
			$youtube_url = 'https://www.youtube.com/watch?v=7yujlqgVz5M';
			$markup      = wp_oembed_get( $youtube_url );

			$this->children[] = $this->create_content_item( $id )
				->append_child(
					( new \WP_Components\HTML() )
						->set_config( 'content', $markup )
						->set_name( 'featured-video' )
				);
		}

		return $this;
	}
}
