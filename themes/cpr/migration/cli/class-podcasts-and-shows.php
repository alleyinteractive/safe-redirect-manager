<?php
/**
 * Migration CLI scripts for CPR Podcasts and Shows.
 *
 * @package CPR
 */

namespace CPR\Migration;

use WP_CLI;

/**
 * Podcasts and Shows migration CLI command.
 */
class Podcasts_And_Shows extends \CLI_Command {

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
		WP_CLI::runcommand( 'cpr-cleanup create_terms' );
		WP_CLI::runcommand( 'cpr-cleanup create_redirects' );
	}

	/**
	 * Create terms.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-cleanup create_terms
	 */
	public function create_terms() {
	}

	/**
	 * Create redirects for older podcasts.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-cleanup create_redirects
	 */
	public function create_redirects() {

		srm_create_redirect( '/underwrite/', '/underwriters/', 301 );
		srm_create_redirect( '/list-of-underwriters/', '/underwriters/', 301 );
		srm_create_redirect( '/about/employment-opportunities/', '/jobs/', 301 );

		\WP_CLI::success( 'Redirects created.' );
	}
}
WP_CLI::add_command( 'cpr-cleanup', __NAMESPACE__ . '\Cleanup' );
