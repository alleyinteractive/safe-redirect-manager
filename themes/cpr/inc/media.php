<?php
/**
 * This file holds configuration settings and functions for media, including
 * image sizes and custom field handling.
 *
 * @package CPR
 */

namespace CPR;

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
						'resize' => [ 480, 270 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
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
		'content_image' => [
			'sources' => [
				[
					'default' => true,
					'transforms' => [
						'resize' => [ 960, 540 ],
					],
				],
			],
			'retina' => true,
			'aspect_ratio' => 9 / 16,
		],
	]
);
