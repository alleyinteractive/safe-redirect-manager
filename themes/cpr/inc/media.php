<?php
/**
 * This file holds configuration settings and functions for media, including
 * image sizes and custom field handling.
 *
 * @package CPR
 */

namespace CPR;

\WP_Components\Image::register_fallback_image(
	get_option( 'cpr-settings', [] )['general']['fallback_image_id'] ?? 0
);

\WP_Components\Image::register_breakpoints(
	[
		'xxl' => '90rem',
		'xl'  => '80rem',
		'lg'  => '64rem',
		'md'  => '48rem',
		'sm'  => '32rem',
	]
);

/**
 * Register image sizes for use by the Image component.
 */
\WP_Components\Image::register_sizes(
	[
		'content_single' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 434, 244 ],
					],
					'descriptor' => 434,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'resize' => [ 722, 406 ],
					],
					'descriptor' => 722,
					'media' => [ 'max' => 'md' ],
				],
				[
					'transforms' => [
						'resize' => [ 948, 533 ],
					],
					'descriptor' => 948,
					'media' => [ 'max' => 'lg' ],
				],
				[
					'transforms' => [
						'resize' => [ 854, 480 ],
					],
					'descriptor' => 854,
					'media' => [ 'max' => 'xl' ],
				],
				[
					'transforms' => [
						'resize' => [ 1032, 580 ],
					],
					'descriptor' => 1032,
					'default'    => true,
				],
			],
			'aspect_ratio' => 9 / 16,
			'retina'       => true,
		],
		'external-link-widget' => [
			'sources' => [
				[
					'default'    => true,
					'descriptor' => 500,
					'transforms' => [
						'resize' => [ 500, 500 ],
					],
				],
			],
			'retina'       => true,
			'aspect_ratio' => 1,
			'fallback_image_url' => get_template_directory_uri() . '/images/default-thumbnail.png',
		],
		'feature_item' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 480, 277 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'resize' => [ 683, 384 ],
					],
					'descriptor' => 683,
					'default'    => true,
				],
			],
			'aspect_ratio' => 9 / 16,
			'retina'       => true,
		],
		'feature_item_small' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 480, 360 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'resize' => [ 640, 480 ],
					],
					'descriptor' => 640,
					'default'    => true,
				],
			],
			'aspect_ratio' => 3 / 4,
			'retina'       => true,
		],
		'grid_item' => [
			'sources' => [
				[
					'default'    => true,
					'transforms' => [
						'resize' => [ 330, 185 ],
					],
					'descriptor' => 330,
				],
			],
			'aspect_ratio' => 9 / 16,
			'retina'       => true,
		],
		'album_cover' => [
			'sources' => [
				[
					'default'    => true,
					'transforms' => [
						'resize' => [ 195, 184 ],
					],
					'descriptor' => 195,
				],
			],
			'aspect_ratio' => 184 / 195,
			'retina'  => true,
		],
		'content_image' => [
			'sources' => [
				[
					'default' => true,
					'transforms' => [
						'resize' => [ 960, 540 ],
					],
					'descriptor' => 960,
				],
			],
			'retina' => true,
			'aspect_ratio' => 9 / 16,
		],
		'avatar' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 107, 107 ],
					],
					'default'    => true,
					'descriptor' => 107,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'default'    => true,
					'transforms' => [
						'resize' => [ 400, 400 ],
					],
					'descriptor' => 400,
				],
			],
			'retina'       => true,
			'aspect_ratio' => 1,
			'fallback_image_url' => get_template_directory_uri() . '/images/default-avatar.png',
		],
		// @todo add proper sizing
		'grid-group-item' => [
			'sources' => [
				[
					'default' => true,
					'descriptor' => 300,
					'transforms' => [
						'resize' => [ 300, 300 ],
					],
				],
			],
			'retina' => true,
			'aspect_ratio' => 1,
		],
		'underwriter' => [
			'sources' => [
				[
					'default'    => true,
					'descriptor' => 250,
					'transforms' => [
						'fit' => [ 250, 250 ],
					],
				],
			],
			'aspect_ratio' => false,
			'retina'       => true,
		],
		'show-and-podcast-header' => [
			'sources' => [
				[
					'default'    => true,
					'descriptor' => 500,
					'transforms' => [
						'resize' => [ 500, 500 ],
					],
				],
			],
			'retina'       => true,
			'aspect_ratio' => 1,
			'fallback_image_url' => get_template_directory_uri() . '/images/default-thumbnail.png',
		],
		// @todo add proper sizing
		'grid-group-host' => [
			'sources' => [
				[
					'default' => true,
					'descriptor' => 300,
					'transforms' => [
						'resize' => [ 300, 300 ],
					],
				],
			],
			'retina' => true,
			'aspect_ratio' => 1,
			'fallback_image_url' => get_template_directory_uri() . '/images/default-avatar.png',
		],
	]
);

/**
 * Override the default WordPress media templates.
 */
function cpr_custom_media_template() {
	require_once CPR_PATH . '/template-parts/media-template/attachment-details-two-column.php';
}
add_action( 'print_media_templates', __NAMESPACE__ . '\cpr_custom_media_template', 10, 1 );

/**
 * Augment the attachment details to include audio processing flags and file paths.
 *
 * @param array    $response   The response array to augment.
 * @param \WP_Post $attachment The original attachment object.
 * @param array    $meta       The metadata for the attachment.
 * @return array The modified response object.
 */
function filter_wp_prepare_attachment_for_js( $response, $attachment, $meta ) {
	// If the type is not audio, bail out.
	if ( empty( $response['type'] ) || 'audio' !== $response['type'] ) {
		return $response;
	}

	// Augment the response with custom metadata for audio files.
	$response['meta']['cpr_transcoding_status'] = intval( get_post_meta( $response['id'], 'cpr_transcoding_status', true ) );
	$response['meta']['cpr_audio_stereo_url']   = esc_url_raw( get_post_meta( $response['id'], 'cpr_audio_stereo_url', true ) );
	$response['meta']['cpr_audio_mono_url']     = esc_url_raw( get_post_meta( $response['id'], 'cpr_audio_mono_url', true ) );
	$response['meta']['cpr_audio_mp3_url']      = esc_url_raw( get_post_meta( $response['id'], 'cpr_audio_mp3_url', true ) );

	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', __NAMESPACE__ . '\filter_wp_prepare_attachment_for_js', 10, 3 );
