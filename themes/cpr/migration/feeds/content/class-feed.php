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
	 * Convert [[nid]] into its proper media.
	 *
	 * @param string $post_content Post content.
	 * @return string
	 */
	public function replace_media( string $post_content ) : string {
		$media_args = [
			'field_align',
			'field_format',
		];

		preg_match_all( '/(<p>)?\[\[nid:(\d+)(.*?)\]\](<\/p>)?/', $post_content, $matches );

		foreach ( $matches[0] as $key => $value ) {

			// Extra fields and parse as needed.
			$legacy_nid          = $matches[2][ $key ];
			$attachment_settings = wp_parse_args( $matches[3][ $key ] );

			// Migrate images.
			$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', $legacy_nid );
			if ( ! empty( $source ) ) {
				$attachment = \CPR\Migration\Image\Feed_Item::get_or_create_object_from_source( $source );

				// Check alignment.
				switch ( trim( $matches[3][ $key ] ) ) {
					case 'field_align=left':
						$align = 'left';
						break;
					case 'field_align=right':
						$align = 'right';
						break;
					case 'field_align=center':
						$align = '';
						break;
					default:
						$align = '';
						break;
				}

				$post_content = str_replace(
					$matches[0][ $key ],
					sprintf(
						'<img src="%1$s" class="cpr-image-block" data-alignment="%2$s" alt="%3$s" data-caption="%4$s" />',
						esc_url( wp_get_attachment_url( $attachment->ID ) ),
						$align,
						$source['title'] ?? '',
						$source['body']['und'][0]['value'] ?? ''
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

			// Migrate galleries.
			if ( empty( $source ) ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'gallery', $legacy_nid );
				if ( ! empty( $source ) && ! empty( $source['field_images']['und'] ) ) {
					$post_content = str_replace(
						$matches[0][ $key ],
						sprintf(
							'<span class="cpr-gallery-migration" id="%1$s" />',
							$source['nid']
						),
						$post_content
					);
				}
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
	public function apply_custom_block_logic( $content, \DOMNode $node ) : string {
		switch ( $node->nodeName ) {
			case 'iframe':
				return $this->video_to_block( $content, $node );
			case 'img':
				if ( $this->has_class( $node->getAttribute( 'class' ), 'cpr-image-block' ) ) {
					return $this->custom_img( $node );
				}
				return $content;
			case 'div':
				$class = $node->getAttribute( 'class' );

				// Remove those divs.
				if ( $this->has_class( $class, 'pane-ads' ) || $this->has_class( $class, 'ad-toggle' ) ) {
					return '';
				}

				// Convert video embed.
				if ( $this->has_class( $class, 'embed' ) ) {
					return $this->video_to_block( $content, $node );
				}

				// Save staticly to persist other checks.
				static $caption = '';

				if ( 'div' === $node->nodeName ) {
					$caption = $node->nodeValue;
				}

				if ( $node->hasChildNodes() ) {

					foreach ( $node->childNodes as $span ) {

						if ( 'img' === $span->nodeName ) {
							if ( ! empty( $caption ) ) {
								return $this->custom_img_block( $span, $caption );
							} else {
								return ( new Converter( '' ) )->img( $span );
							}
						}

						if ( $span->hasChildNodes() ) {
							foreach ( $span->childNodes as $innerChild ) {
								if ( 'img' === $innerChild->nodeName && $this->has_class( $innerChild->getAttribute( 'class' ), 'cpr-image-block' ) ) {
									if ( ! empty( $innerChild->getAttribute( 'data-alignment' ) ) ) {
										return $this->custom_img( $innerChild );
									} else {
										return ( new Converter( '' ) )->img( $innerChild );
									}
								}
							}
						}
					}
				}
				return ( new Converter( '' ) )->p( $node );
			case 'span':
				if ( 'cpr-gallery-migration' === $node->getAttribute( 'class' ) ) {
					return $this->migrate_galleries( $content, $node );
				}
				return $content;
			case 'p':

				// Fix for nested galleries inside a paragraph.
				if ( 'cpr-gallery-migration' === $node->getAttribute( 'class' ) ) {
					return $this->migrate_galleries( $content, $node );
				}

				if ( ! $node->hasChildNodes() ) {
					return $content;
				}

				foreach ( $node->childNodes as $span ) {

					if ( '#text' === $span->nodeName ) {
						return $content;
					}

					if ( $span->hasChildNodes() ) {
						foreach ( $span->childNodes as $innerChild ) {
							if ( '#text' === $innerChild->nodeName ) {
								return $content;
							}

							// Fix for nested galleries inside a paragraph and span.
							if ( 'cpr-gallery-migration' === $innerChild->getAttribute( 'class' ) ) {
								return $this->migrate_galleries( $content, $innerChild );
							}

							// Fix for nested image block inside a paragraph and span.
							if ( 'img' === $innerChild->nodeName && 'cpr-image-block' === $innerChild->getAttribute( 'class' ) ) {
								return $this->custom_img( $innerChild );
							}
						}
					}

					return $content;
				}

				return $content;
			default:
				return $content;
		}
	}

	/**
	 * Create custom img block.
	 *
	 * @param \DOMNode $node The node.
	 * @return string The HTML.
	 */
	private function custom_img( \DOMNode $node ) : string {
		$alignment = $node->getAttribute( 'data-alignment' ) ?? '';
		$alt       = $node->getAttribute( 'alt' ) ?? '';
		$image_src = $node->getAttribute( 'src' ) ?? '';
		$caption   = $node->getAttribute( 'data-caption' ) ?? '';
		$image_src = ( new Converter( '' ) )->upload_image( $image_src, $alt );
		
		// Check alignment.
		switch ( $alignment ) {
			case 'right':
				$figure_alignment = 'alignright';
				break;
			case 'left':
				$figure_alignment = 'alignleft';
				break;
			default:
				$figure_alignment = '';
				break;
		}

		if ( empty( $figure_alignment ) ) {
			return '<!-- wp:image -->' . PHP_EOL .
			'<figure class="wp-block-image">' . PHP_EOL .
				'<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $alt ) . '" />' . PHP_EOL .
				'<figcaption>' . wp_strip_all_tags( $caption ) . '</figcaption>' . PHP_EOL .
			'</figure>' . PHP_EOL .
			'<!-- /wp:image -->';
		}

		return '<!-- wp:image {"align":"' . $alignment . '","width":300,"height":200} -->' . PHP_EOL .
		'<div class="wp-block-image">' . PHP_EOL .
			'<figure class="' . esc_attr( $figure_alignment ) . ' is-resized">' . PHP_EOL .
				'<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $alt ) . '" width="300" height="200" />' . PHP_EOL .
				'<figcaption>' . wp_strip_all_tags( $caption ) . '</figcaption>' . PHP_EOL .
			'</figure>' . PHP_EOL .
		'</div>' . PHP_EOL .
		'<!-- /wp:image -->';
	}

	/**
	 * Create custom img wide block.
	 *
	 * @param \DOMNode $node The node.
	 * @return string The HTML.
	 */
	private function custom_img_block( \DOMNode $node, $caption ) : string {
		$alt       = $node->getAttribute( 'alt' ) ?? '';
		$image_src = $node->getAttribute( 'src' ) ?? '';
		$image_src = ( new Converter( '' ) )->upload_image( $image_src, $alt );

		return '<!-- wp:image -->' . PHP_EOL .
		'<figure class="wp-block-image">' . PHP_EOL .
			'<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $alt ) . '" />' . PHP_EOL .
			'<figcaption>' . wp_strip_all_tags( $caption ) . '</figcaption>' . PHP_EOL .
		'</figure>' . PHP_EOL .
		'<!-- /wp:image -->';
	}

	/**
	 * Map gallery to its custom block.
	 *
	 * @param string   $content HTML content, already blocks.
	 * @param \DOMNode $node    The node.
	 * @return string
	 */
	private function migrate_galleries( $content, \DOMNode $node ) : string {
		$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'gallery', absint( $node->getAttribute( 'id' ) ) );

		if ( empty( $source ) && empty( $source['field_images']['und'] ) ) {
			return $content;
		}

		// Build gallery output.
		$gallery = [
			'images' => [],
		];

		array_map(
			function( $target ) use ( &$gallery ) {
				$source     = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', absint( $target['target_id'] ) );
				$attachment = \CPR\Migration\Image\Feed_Item::get_or_create_object_from_source( $source );

				// Validate attachment.
				if ( ! $attachment instanceof \WP_Post ) {
					return false;
				}

				$gallery['images'][] = [
					'original' => wp_get_attachment_url( $attachment->ID ) ?? '',
					'alt'      => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ?? '',
					'caption'  => wp_get_attachment_caption( $attachment->ID ) ?? '',
					'id'       => $attachment->ID,
				];

			},
			$source['field_images']['und']
		);

		$attributes = wp_json_encode(
			$gallery,
			JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
		);

		return '<!-- wp:cpr/galleries ' . addslashes( $attributes ) . ' /-->';
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
		$video_url = $iframe->item( 0 )->getAttribute( 'src' ) ?? '';

		// Bail early.
		if ( empty( $video_url )
			&& ! (
			$this->is_video( $video_url, 'youtube.com' )
			&& $this->is_video( $video_url, 'youtu.be' )
			&& $this->is_video( $video_url, 'vimeo.com'
			) ) ) {
			return $content;
		}

		// Return vimeo first.
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

	/**
	 * Has class.
	 *
	 * @param string $attrs Node attrs.
	 * @param string $class Class.
	 * @return boolean
	 */
	private function has_class( $attrs, $class ) : bool {
		return ( false !== strpos( $attrs, $class ) );
	}
}
