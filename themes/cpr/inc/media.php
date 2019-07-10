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
						'resize' => [ 434, 289 ],
					],
					'descriptor' => 434,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'resize' => [ 722, 481 ],
					],
					'descriptor' => 722,
					'media' => [ 'max' => 'md' ],
				],
				[
					'transforms' => [
						'resize' => [ 948, 632 ],
					],
					'descriptor' => 948,
					'media' => [ 'max' => 'lg' ],
				],
				[
					'transforms' => [
						'resize' => [ 854, 569 ],
					],
					'descriptor' => 854,
					'media' => [ 'max' => 'xl' ],
				],
				[
					'transforms' => [
						'resize' => [ 1032, 688 ],
					],
					'descriptor' => 1032,
					'default'    => true,
				],
			],
			'aspect_ratio' => 2 / 3,
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
						'resize' => [ 480, 320 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'resize' => [ 683, 455 ],
					],
					'descriptor' => 683,
					'default'    => true,
				],
			],
			'aspect_ratio' => 2 / 3,
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
			'retina'       => true,
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
			'fallback_image_url' => '', // prevents the sitewide default fallback image from displaying for underwriters.
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

/**
 * A REST endpoint for starting the transcoding process.
 *
 * @param \WP_REST_Request $request Request object to be parsed.
 * @return bool True on success, false on failure.
 */
function rest_audio_transcode_start( $request ) {
	// Ensure we are able to copy into the transcoding bucket.
	if ( ! defined( 'S3_TRANSCODING_BUCKET' ) || empty( S3_TRANSCODING_BUCKET ) ) {
		return false;
	}

	// Ensure we got a valid ID.
	$id = intval( $request->get_param( 'id' ) );
	if ( empty( $id ) ) {
		return false;
	}

	// Get the attachment details by ID.
	$attachment = get_post( $id );
	if ( empty( $attachment ) ) {
		return false;
	}

	// If the MIME type is not audio/wav, bail out.
	if ( empty( $attachment->post_mime_type ) || 'audio/wav' !== $attachment->post_mime_type ) {
		return false;
	}

	// Construct filename to be created in the transcoder pipeline.
	$type = $request->get_param( 'type' );
	$to = 's3://' . S3_TRANSCODING_BUCKET . '/' . $id . '-' . $attachment->post_name;
	if ( 'music' === $type ) {
		$to .= '-music';
	}
	$to .= '.wav';

	// Modify the source URL for the attachment from S3.
	$from = str_replace(
		'https://' . S3_UPLOADS_BUCKET . '.s3.amazonaws.com',
		's3://' . S3_UPLOADS_BUCKET,
		wp_get_attachment_url( $id )
	);

	// Override the parameters for the S3 copy to allow copying into our custom bucket.
	add_filter(
		's3_uploads_putObject_params',
		__NAMESPACE__ . '\filter_s3_uploads_putobject_params'
	);

	// Perform the copy operation on S3.
	if ( copy( $from, $to ) ) {
		// Add postmeta indicating status of copy operation and media type.
		update_post_meta( $id, 'cpr_transcoding_status', CPR_TRANSCODING_PROCESSING );
		update_post_meta( $id, 'cpr_audio_type', $type );
		return true;
	}

	// Remove our filter for the S3 bucket.
	remove_filter(
		's3_uploads_putObject_params',
		__NAMESPACE__ . '\filter_s3_uploads_putobject_params'
	);

	// Save error state and bail.
	update_post_meta( $id, 'cpr_transcoding_status', CPR_TRANSCODING_ERROR );

	return false;
}

/**
 * A REST endpoint for ending the transcoding process.
 *
 * @param \WP_REST_Request $request Request object to be parsed.
 * @return bool True on success, false on failure.
 */
function rest_audio_transcode_end( $request ) {
	// Verify token.
	if ( CPR_TRANSCODING_TOKEN !== $request->get_param( 'token' ) ) {
		return false;
	}

	// Ensure there is a file complete URL.
	$url = $request->get_param( 'fileComplete' );
	if ( empty( $url ) ) {
		return false;
	}

	// Parse the file complete URL into bits to extract the filename.
	$path       = wp_parse_url( $url, PHP_URL_PATH );
	$path_parts = explode( '/', $path );
	$filename   = array_pop( $path_parts );
	if ( empty( $filename ) ) {
		return false;
	}

	// Parse the filename to extract the ID and type.
	preg_match( '/^([0-9]+)-.+?(stereo|mono)?\.(m4a|mp3)$/', $filename, $matches );
	if ( 4 !== count( $matches ) ) {
		return false;
	}

	// Ensure we got a valid ID. We need to extract this from the filename ourselves.
	$id = intval( $matches[1] );
	if ( empty( $id ) ) {
		return false;
	}

	// Get the attachment details by ID.
	$attachment = get_post( $id );
	if ( empty( $attachment ) ) {
		return false;
	}

	// If the MIME type is not audio/wav, bail out.
	if ( empty( $attachment->post_mime_type ) || 'audio/wav' !== $attachment->post_mime_type ) {
		return false;
	}

	// Based on type, save to postmeta.
	$channels  = $matches[2];
	$extension = $matches[3];
	switch ( $extension ) {
		case 'mp3':
			update_post_meta( $id, 'cpr_audio_mp3_url', esc_url_raw( $url ) );
			break;
		case 'm4a':
			switch ( $channels ) {
				case 'mono':
					update_post_meta( $id, 'cpr_audio_mono_url', esc_url_raw( $url ) );
					break;
				case 'stereo':
					update_post_meta( $id, 'cpr_audio_stereo_url', esc_url_raw( $url ) );
					break;
				default:
					return false;
			}
			break;
		default:
			return false;
	}

	// Update general status in postmeta to indicate success.
	update_post_meta( $id, 'cpr_transcoding_status', CPR_TRANSCODING_SUCCESS );

	return true;
}

// Add custom REST API endpoints.
add_action(
	'rest_api_init',
	function () {
		// Add the endpoint for starting the audio transcoding process.
		register_rest_route(
			'cpr/v1',
			'/audio-transcode-start',
			[
				'callback' => __NAMESPACE__ . '\rest_audio_transcode_start',
				'methods'  => 'POST',
				'permission_callback' => function () {
					return current_user_can( 'upload_files' );
				},
			]
		);

		// Add the endpoint for completing the audio transcoding process.
		register_rest_route(
			'cpr/v1',
			'/audio-transcode-end',
			[
				'callback' => __NAMESPACE__ . '\rest_audio_transcode_end',
				'methods'  => 'POST',
			]
		);
	}
);

/**
 * A filter callback to modify the parameters that are used when copying S3 files.
 *
 * Allows us to override the S3 bucket to use the transcoding bucket instead.
 *
 * @param array $params Parameters to override.
 * @return array Modified parameters.
 */
function filter_s3_uploads_putobject_params( $params ) {
	// If we are writing to our audio transcoding bucket and the ACL is set to public-read, override to private.
	if ( ! empty( $params['Bucket'] )
		&& S3_TRANSCODING_BUCKET === $params['Bucket']
		&& ! empty( $params['ACL'] )
		&& 'public-read' === $params['ACL']
	) {
		$params['ACL'] = 'private';
	}

	return $params;
}

// phpcs:disable
/**
 * CURL and request modifications for local for uploading audio.
 */
if ( false !== strpos( site_url(), 'alley' ) ) {

	/**
	 * Setting a custom timeout value for cURL. Using a high value for priority to
	 * ensure the function runs after any other added to the same action hook.
	 */
	function custom_curl_timeout( $handle ){
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt( $handle, CURLOPT_TIMEOUT, 30 );
	}
	add_action( 'http_api_curl', __NAMESPACE__ . '\custom_curl_timeout', 9999, 1 );

	/**
	 * Setting custom timeout for the HTTP request.
	 */
	function custom_http_request_timeout( $timeout_value ) {
		return 30;
	}
	add_filter( 'http_request_timeout', __NAMESPACE__ . '\custom_http_request_timeout', 9999 );

	/**
	 * Setting custom timeout in HTTP request args.
	 */
	function custom_http_request_args( $r ){
		$r['timeout'] = 30;
		return $r;
	}
	add_filter( 'http_request_args', __NAMESPACE__ . '\custom_http_request_args', 9999, 1 );
}
// phpcs:enable
