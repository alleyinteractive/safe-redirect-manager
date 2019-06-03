<?php
/**
 * Feed for migrating post content into blocks.
 *
 * @package CPR
 */

namespace CPR\Migration\Post_Blocks_Conversion;

use Alleypack\Block\Converter;

// phpcs:ignoreFile WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

/**
 * Feed.
 */
class Feed extends \Alleypack\Sync_Script\Feed {

	// Enable functionality for this feed.
	use \Alleypack\Sync_Script\Endpoint;
	use \Alleypack\Sync_Script\GUI;

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'post-blocks-conversion';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Post_Blocks_Conversion\Feed_Item';

	/**
	 * Add some post content conversion methods.
	 */
	public function __construct() {
		add_filter( 'cpr_block_converter_replace_media', [ $this, 'remove_paragraph_dir' ] );
		add_filter( 'cpr_block_converter_replace_media', [ $this, 'replace_media' ] );
		add_filter( 'alleypack_block_converter_html_tag', [ $this, 'remove_unnecessary_paragraphs' ], 10, 2 );
	}

	/**
	 * Load source data using a limit and offset.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return bool Loaded successfully?
	 */
	public function load_source_data_by_limit_and_offset( int $limit, int $offset ) : bool {

		// Run a post query.
		$source_query = new \WP_Query(
			[
				'offset'         => $offset,
				'post_type'      => 'post',
				'posts_per_page' => $limit,
			]
		);

		$data = array_map(
			function( $post ) {
				return (array) $post;
			},
			$source_query->posts ?? []
		);

		$this->set_source_data( $data );

		return $this->has_source_data();
	}

	/**
	 * Load source data using a unique ID.
	 *
	 * @param string $unique_id Unique ID.
	 * @return bool Loaded successfully?
	 */
	public function load_source_data_by_unique_id( string $unique_id ) : bool {
		$data = (array) get_post( $unique_id );
		if ( ! empty( $data ) ) {
			$this->set_source_data( [ $data ] );
		}
		return $this->has_source_data();
	}

	/**
	 * Remove the extraneous 'dir' attribute.
	 *
	 * @param string $post_content Post content.
	 * @return string
	 */
	public function remove_paragraph_dir( string $post_content ) : string {
		$post_content = str_replace( ' dir="ltr"', '', $post_content );
		return $post_content;
	}

	/**
	 * Convert [[nid]] widgets into media.
	 *
	 * @todo Setup galleries.
	 *
	 * @param string $post_content Post content.
	 * @return string
	 */
	public function replace_media( string $post_content ) : string {
		$media_args = [
			'field_align',
			'field_format',
		];

		preg_match_all( '#\[\[nid:(\d+)(.+)\]\]#', $post_content, $matches );

		foreach ( $matches[0] as $key => $value ) {

			// Extra fields and parse as needed.
			$legacy_nid          = $matches[1][ $key ];
			$attachment_settings = wp_parse_args( $matches[2][ $key ] );

			// Migrate images.
			$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', $legacy_nid );
			if ( ! empty( $source ) ) {
				$attachment   = \CPR\Migration\Image\Feed_Item::get_or_create_object_from_source( $source );
				$post_content = str_replace(
					$matches[0][ $key ],
					sprintf(
						'<img src="%1$s" />',
						esc_url( wp_get_attachment_url( $attachment->ID ) )
					),
					$post_content
				);
			}

			// @todo Setup galleries.
			if ( empty( $source ) ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'gallery', $legacy_nid );
			}
		}

		return $post_content;
	}

	/**
	 * Map legacy tags to blocks.
	 *
	 * @param string   $content HTML content, already blocks.
	 * @param \DOMNode $node    The node.
	 * @return string
	 */
	public function remove_unnecessary_paragraphs( $content, \DOMNode $node ) {
		if ( 'p' === $node->tagName && 'img' === ( $node->firstChild->tagName ?? '' ) ) {
			$html = Converter::get_node_html( $node->firstChild );
			return ( new Converter( $html ) )->convert_to_block();
		}
		return $content;
	}
}
