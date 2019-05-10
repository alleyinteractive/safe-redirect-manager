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
	}

	/**
	 * Set the initial site settings. This will override previously chnaged
	 * values.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-cleanup set_site_settings
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
}
WP_CLI::add_command( 'cpr-cleanup', __NAMESPACE__ . '\Cleanup' );
