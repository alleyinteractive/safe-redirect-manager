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
		'xl' => '80rem',
		'lg' => '64rem',
		'md' => '48rem',
		'sm' => '32rem',
	]
);

\WP_Components\Image::register_crop_sizes(
	[
		'16:9' => [
			'card' => [
				'height' => 1920,
				'width'  => 1080,
			],
		],
	]
);

/**
 * Register image sizes for use by the Image component.
 */
\WP_Components\Image::register_sizes(
	[
		'fullwidth_background' => [
			'sources' => [
				[
					'transforms' => [
						'w' => [ 1920 ],
					],
					'descriptor' => 1920,
					'media' => [ 'min' => 'xxl' ],
				],
				[
					'transforms' => [
						'w' => [ 1440 ],
					],
					'descriptor' => 1440,
					'media' => [ 'max' => 'xxl' ],
				],
				[
					'transforms' => [
						'w' => [ 1300 ],
					],
					'descriptor' => 1300,
					'media' => [ 'max' => 'xl' ],
				],
				[
					'transforms' => [
						'w' => [ 900 ],
					],
					'descriptor' => 900,
					'media' => [ 'max' => 'lg' ],
				],
				[
					'transforms' => [
						'w' => [ 600 ],
					],
					'descriptor' => 600,
					'media' => [ 'max' => 'md' ],
				],
				[
					'transforms' => [
						'w' => [ 480 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
			],
			'retina'       => false,
			'aspect_ratio' => false,
		],
		'fullwidth' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 1440, 650 ],
					],
					'descriptor' => 1440,
				],
				[
					'transforms' => [
						'resize' => [ 1300, 585 ],
					],
					'descriptor' => 1300,
					'media' => [ 'max' => 'xl' ],
				],
				[
					'transforms' => [
						'resize' => [ 900, 405 ],
					],
					'descriptor' => 900,
					'media' => [ 'max' => 'lg' ],
				],
				[
					'transforms' => [
						'resize' => [ 600, 270 ],
					],
					'descriptor' => 600,
					'media' => [ 'max' => 'md' ],
				],
				[
					'transforms' => [
						'resize' => [ 480, 216 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
			],
			'retina'       => false,
			'aspect_ratio' => 5 / 11,
		],
		'feature_item' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 480, 277 ],
					],
					'descriptor' => 173,
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
		],
		'grid_item' => [
			'sources' => [
				[
					'default'    => true,
					'transforms' => [
						'resize' => [ 800, 480 ],
					],
					'descriptor' => 480,
				],
			],
			'aspect_ratio' => 0.6,
			'retina'       => false,
		],
		'feature' => [
			'sources' => [
				[
					'transforms' => [
						'w' => [ 480 ],
					],
					'descriptor' => 480,
					'media' => [ 'max' => 'sm' ],
				],
				[
					'transforms' => [
						'w' => [ 600 ],
					],
					'descriptor' => 600,
					'media' => [ 'max' => 'md' ],
				],
				[
					'transforms' => [
						'w' => [ 900 ],
					],
					'descriptor' => 900,
					'media' => [ 'max' => 'lg' ],
				],
				[
					'transforms' => [
						'w' => [ 1180 ],
					],
					'descriptor' => 1180,
					'media' => [ 'max' => 'xl' ],
				],
				[
					'transforms' => [
						'w' => [ 1550 ],
					],
					'descriptor' => 1550,
					'media' => [ 'min' => 'xl' ],
				],
			],
			'aspect_ratio' => false,
			'lazyload'     => false,
			'retina'       => false,
		],
		'avatar' => [
			'sources' => [
				[
					'transforms' => [
						'resize' => [ 200, 200 ],
					],
					'descriptor' => 200,
				],
			],
			'aspect_ratio' => 1,
		],
	]
);
