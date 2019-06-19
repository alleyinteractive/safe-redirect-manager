<?php
/**
 * Feed for migrating post content into blocks.
 *
 * @package CPR
 */

namespace CPR\Migration\Content;

use Alleypack\Block\Converter;

// phpcs:ignoreFile WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

/**
 * Feed.
 */
class Feed extends \CPR\Migration\Post_Datasource_Feed {

	// Enable functionality for this feed.
	use \Alleypack\Sync_Script\Endpoint;
	use \Alleypack\Sync_Script\GUI;

	/**
	 * Post type for this feed to execute on itself.
	 *
	 * @var string
	 */
	public $post_type = [
		'post',
		'podcast-episode',
		'show-episode',
		'show-segment',
		'press-release',
	];

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'content';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Content\Feed_Item';

	/**
	 * Add some post content conversion methods.
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'cpr_block_converter_replace_media', [ $this, 'remove_paragraph_dir' ] );
		add_filter( 'cpr_block_converter_replace_media', [ $this, 'replace_media' ] );
		add_filter( 'alleypack_block_converter_html_tag', [ $this, 'apply_custom_block_logic' ], 10, 2 );
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

		preg_match_all( '/(<p>)?\[\[nid:(\d+)(.+)\]\](<\/p>)?/', $post_content, $matches );

		foreach ( $matches[0] as $key => $value ) {

			// Extra fields and parse as needed.
			$legacy_nid          = $matches[2][ $key ];
			$attachment_settings = wp_parse_args( $matches[3][ $key ] );

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

			// Migrate videos.
			$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'video', $legacy_nid );
			if ( ! empty( $source ) ) {
				$post_content = str_replace(
					$matches[0][ $key ],
					wp_oembed_get( $source['field_video_embed']['und'][0]['video_url'] ) ?? '',
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
	public function apply_custom_block_logic( $content, \DOMNode $node ) {
		if ( 'p' === $node->tagName && 'img' === ( $node->firstChild->tagName ?? '' ) ) {
			$html = Converter::get_node_html( $node->firstChild );
			return ( new Converter( $html ) )->convert_to_block();
		}

		// Fix for nested images.
		if ( 'p' === $node->tagName ) {
			$img = Converter::get_nodes( $node, 'img' );

			// Bail if there is no img tag.
			if ( empty( $img->item( 0 ) ) ) {
				return $content;
			}

			return ( new Converter( '' ) )->img( $img->item( 0 ) );
		}

		if ( 'iframe' === $node->tagName || ( 'div' === $node->tagName && 'embed' === $node->getAttribute( 'class' )  ) ) {
			return $this->video_to_block( $content, $node );
		}

		return $content;
	}

	/**
	 * Map legacy youtube/vimeo iframe into their core blocks.
	 *
	 * @param string   $content HTML content, already blocks.
	 * @param \DOMNode $node    The node.
	 * @return string
	 */
	public function video_to_block( $content, \DOMNode $node ) {

		// Get iframe.
		$iframe = Converter::get_nodes( $node, 'iframe' );

		// Bail if there is no iframe.
		if ( empty( $iframe->item( 0 ) ) ) {
			return $content;
		}

		// Get the iframe src/url.
		$video_url = $iframe->item( 0 )->getAttribute( 'src' );

		// Bail early.
		if ( empty( $video_url )
			&& ! (
			$this->is_video( $video_url, 'youtube.com' )
			&& $this->is_video( $video_url, 'youtu.be' )
			&& $this->is_video( $video_url, 'vimeo.com'
			) ) ) {
			return $content;
		}

		if ( $this->is_video( $video_url, 'vimeo.com' ) ) {
			return '<!-- wp:core-embed/vimeo {"url":"' . esc_url( $video_url ) . '","type":"video","providerNameSlug":"vimeo","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->' . PHP_EOL .
				'<figure class="wp-block-embed-vimeo wp-block-embed is-type-video is-provider-vimeo wp-embed-aspect-16-9 wp-has-aspect-ratio">' . PHP_EOL .
					'<div class="wp-block-embed__wrapper">' . PHP_EOL .
						esc_url( $video_url ) . PHP_EOL .
					'</div>' . PHP_EOL .
				'</figure>' . PHP_EOL .
			'<!-- /wp:core-embed/vimeo -->';
		}

		// Remove some options.
		$url = remove_query_arg( [ 'list', 'controls', 'showinfo', 'feature' ], $video_url );

		// This is in case there are other types of urls.
		preg_match( '/embed/', $url, $is_embed );
		if ( 'embed' !== $is_embed[0] ) {
			return $content;
		}

		// Get video id.
		preg_match( '/embed\/([^\s]+)/', $url, $video_id );

		// Create new url.
		$youtube_url = 'https://www.youtube.com/watch?v=' . $video_id[1];

		return '<!-- wp:core-embed/youtube {"url":"' . esc_url( $youtube_url ) . '","type":"video","providerNameSlug":"youtube","className":"wp-embed-aspect-9-16 wp-has-aspect-ratio"} -->' . PHP_EOL .
				'<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-9-16 wp-has-aspect-ratio">' . PHP_EOL .
					'<div class="wp-block-embed__wrapper">' . PHP_EOL .
						esc_url( $youtube_url ) . PHP_EOL .
					'</div>' . PHP_EOL .
				'</figure>' . PHP_EOL .
		'<!-- /wp:core-embed/youtube -->';
	}

	/**
	 * Check video url.
	 *
	 * @param string $url  Video url.
	 * @param string $type Video type.
	 * @return boolean
	 */
	private function is_video( $url, $type ) : bool {
		return ( false !== strpos( $url, $type ) );
	}
}
