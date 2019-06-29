<?php
/**
 * Migration Cleanup CLI scripts for CPR.
 *
 * @package CPR
 */

namespace CPR\Migration;

use WP_CLI;

/**
 * Migration Cleanup CLI command.
 */
class Cleanup extends \CLI_Command {

	use \Alleypack\CLI_Bulk_Post_Task;
	use \Alleypack\CLI_Helpers;

	/**
	 * Run all menu commands.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-clean run_all
	 */
	public function run_all() {
		WP_CLI::runcommand( 'cpr-cleanup set_site_settings' );
		WP_CLI::runcommand( 'cpr-cleanup create_redirects' );
		WP_CLI::runcommand( 'cpr-cleanup post_type_redirects' );
		WP_CLI::runcommand( 'cpr-cleanup image_caption_decode' );
	}

	/**
	 * Set the initial site settings. This will override previously chnaged
	 * values.
	 *
	 * ## EXAMPLE
	 *
	 * $ wp cpr-cleanup set_site_settings
	 */
	public function set_site_settings() {

		// Get the current settings so we can override/build upon.
		$settings = (array) get_option( 'cpr-settings' );

		// Todo: Add Parsely shortname.
		$settings['analytics']['parsely_site'] = '';

		// Giving.
		$settings['giving'] = [
			'cta' => [
				'donate_cta' => [
					'heading'      => 'Donate to CPR',
					'description'  => '<p>Support impartial journalism, music exploration, and discovery with your monthly gift today.</p>',
					'button_label' => 'Donate Now',
				],
			],
			'button' => [
				'donate_button' => [
					'label' => 'Donate to CPR',
					'url'   => '/donate/',
				],
			],
		];

		// Add social.
		$settings['social']['social'] = [
			'facebook'  => 'https://www.facebook.com/ColoradoPublicRadio/',
			'instagram' => 'https://www.instagram.com/newscpr/',
			'twitter'   => 'https://twitter.com/copublicradio/',
		];

		// Todo: Add Disqus shortname.
		$settings['engagement']['forum_shortname'] = '';

		// Add newsletter settings.
		$settings['engagement']['newsletter'] = [
			'heading'     => 'News That Matters, Delivered To Your Inbox',
			'tagline'     => 'Sign up for a smart, compelling, and sometimes funny take on your daily news briefing.',
			'account_id'  => '',
			'public_key'  => '',
			'private_key' => '',
		];

		// Todo: Add footer content.
		$settings['careers']['footer_content'] = '';

		update_option( 'cpr-settings', $settings );

		WP_CLI::success( 'Settings created.' );
	}

	/**
	 * Generate redirects as part of the migration process.
	 *
	 * ## EXAMPLE
	 *
	 * $ wp cpr-cleanup create_redirects
	 */
	public function create_redirects() {

		srm_create_redirect( '/underwrite/', '/underwriters/', 301 );
		srm_create_redirect( '/list-of-underwriters/', '/underwriters/', 301 );

		// Jobs archive and singles.
		srm_create_redirect( '/about/employment-opportunities/', '/jobs/', 301 );
		srm_create_redirect( '/employment-opportunity/*', '/job/*', 301 );

		// Press Release archive and singles.
		srm_create_redirect( '/about/press-room/', '/press-releases/', 301 );
		srm_create_redirect( '/about/press-room/*', '/press-release/*', 301 );

		// Top 30 archive and singles.
		srm_create_redirect( '/openair/blogs/top-30/', '/indie/top-30/', 301 );
		srm_create_redirect( '/openair/blog/*', '/indie/top-30/*', 301 );

		\WP_CLI::success( 'Redirects created.' );
	}

	/**
	 * Generate redirects for the specific post type.
	 *
	 * [--post_type=<post_type>]
	 * : Post type to create redirects.
	 * ---
	 * default: post
	 * ---
	 *
	 * [--post_id=<id>]
	 * : Post ID to create redirect from.
	 * ---
	 * default: 0
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *   $ wp cpr-cleanup post_type_redirects
	 *   $ wp cpr-cleanup post_type_redirects --post_type=another_post_type
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function post_type_redirects( $args, $assoc_args ) {
		// Default values.
		$query_args = [
			'post_type' => [ $assoc_args['post_type'] ],
			'fields'    => 'ids',
		];

		// Unique post ID.
		if ( ! empty( $assoc_args['post_id'] ) ) {
			$query_args['p'] = absint( $assoc_args['post_id'] );
		}

		$this->bulk_task(
			$query_args,
			function ( $post_id ) {
				$legacy_path = get_post_meta( $post_id, 'legacy_path', true );
				$new_path    = str_replace(
					home_url(),
					'',
					get_permalink( $post_id )
				);

				if ( ! empty( $legacy_path ) ) {
					$retval = \WPCOM_Legacy_Redirector::insert_legacy_redirect( '/' . $legacy_path, $new_path, false );

					if ( true === $retval ) {
						WP_CLI::log(
							sprintf( 'Redirect created for post %d.', $post_id )
						);
					} else {
						WP_CLI::log(
							sprintf( 'Creation of redirect failed for Post ID: %d.', $post_id )
						);
					}
				} else {
					WP_CLI::log(
						sprintf( 'Post ID %d has no legacy path.', $post_id )
					);
				}
			}
		);

		\WP_CLI::success( 'Post types redirects created. o/' );
	}

	/**
	 * Decode caption data from attachments.
	 *
	 * ## EXAMPLE
	 *
	 *   $ wp cpr-cleanup image_caption_decode
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function image_caption_decode( $args, $assoc_args ) {
		// Default values.
		$query_args = [
			'post_type' => [ 'attachment' ],
			'fields'    => 'ids',
		];

		// Unique post ID.
		if ( ! empty( $assoc_args['post_id'] ) ) {
			$query_args['p'] = absint( $assoc_args['post_id'] );
		}

		$this->bulk_task(
			$query_args,
			function ( $post_id ) {
				$current_caption = wp_get_attachment_caption( $post_id );

				if ( ! empty( $current_caption ) ) {
					wp_update_post(
						[
							'ID'           => $post_id,
							'post_excerpt' => html_entity_decode( $current_caption ),
						]
					);

					WP_CLI::log(
						sprintf( 'Post ID %d caption updated.', $post_id )
					);
				} else {
					WP_CLI::log(
						sprintf( 'No caption for Post ID %d.', $post_id )
					);
				}
			}
		);

		\WP_CLI::success( 'Captions decoded. o/' );
	}
}
WP_CLI::add_command( 'cpr-cleanup', __NAMESPACE__ . '\Cleanup' );
