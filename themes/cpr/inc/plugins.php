<?php
/**
 * Load and customize plugins
 *
 * @package CPR
 */

namespace CPR;

// Ensure the basic Google Maps API is always used.
add_filter( 'tribe_is_using_basic_gmaps_api', '__return_true' );

/**
 * Add audio to NPR feed.
 *
 * @param array $audio   Audio meta to include in the feed.
 * @param int   $post_id Post ID.
 * @return array
 */
function npr_nprml_audio_override( $audio, $post_id ) {

	// Compile default children.
	$audio_children = [
		[
			'tag'  => 'region',
			'text' => 'all',
		],
		[
			'tag' => 'rightsHolder',
		],
		[
			'tag'      => 'permissions',
			'children' => [
				[
					'tag'  => 'download',
					'attr' => [
						'allow' => 'true',
					],
				],
				[
					'tag'  => 'stream',
					'attr' => [
						'allow' => 'true',
					],
				],
				[
					'tag'  => 'embed',
					'attr' => [
						'allow' => 'false',
					],
				],
			],
		],
		[
			'tag'  => 'stream',
			'attr' => [
				'active' => 'false',
			],
		],
	];

	// Use the directly set source for audio, if present.
	$src = get_post_meta( $post_id, 'audio_url', true );
	if ( ! empty( $src ) ) {
		$audio_children = array_merge(
			$audio_children,
			[
				[
					'tag'      => 'format',
					'children' => [
						[
							'tag'  => 'mp3',
							'text' => $src,
						],
					],
				],
			]
		);

		$audio[] = [
			'tag'      => 'audio',
			'attr'     => [
				'type' => 'primary',
			],
			'children' => $audio_children,
		];

		return $audio;
	}

	// Get the primary file.
	$audio_id = get_post_meta( $post_id, 'audio_id', true );
	if ( ! empty( $audio_id ) ) {

		// Get the mp3 version.
		$transcoded_mp3_url = get_post_meta( $audio_id, 'cpr_audio_mp3_url', true );
		if ( ! empty( $transcoded_mp3_url ) ) {
			$src = $transcoded_mp3_url;
		} else {
			// Get the wav version.
			$src = wp_get_attachment_url( $audio_id );
		}
	}

	// Fallback to NPR mp3.
	if ( empty( $audio_id ) ) {
		$audio_id = get_post_meta( $post_id, 'npr_id', true );
		if ( ! empty( $audio_id ) ) {
			$src = wp_get_attachment_url( $audio_id );
		}
	}

	// Fallback to migrated mp3.
	if ( empty( $audio_id ) ) {
		$audio_id = get_post_meta( $post_id, 'mp3_id', true );
		if ( ! empty( $audio_id ) ) {
			$src = wp_get_attachment_url( $audio_id );
		}
	}

	// No audio.
	if ( empty( $src ) ) {
		return [];
	}

	$audio_meta = wp_get_attachment_metadata( $audio_id );

	$audio_children = array_merge(
		$audio_children,
		[
			[
				'tag'      => 'format',
				'children' => [
					[
						'tag'  => 'mp3',
						'text' => $src,
					],
					[
						'tag'  => 'mediastream',
						'text' => $src,
					],
				],
			],
			[
				'tag'  => 'duration',
				'text' => $audio_meta['length'] ?? '',
			],
		]
	);

	$audio[] = [
		'tag'      => 'audio',
		'attr'     => [
			'type' => 'primary',
			'id'   => $audio_id,
		],
		'children' => $audio_children,
	];

	return $audio;
}
add_filter( 'npr_nprml_audio_override', __NAMESPACE__ . '\npr_nprml_audio_override', 10, 2 );
