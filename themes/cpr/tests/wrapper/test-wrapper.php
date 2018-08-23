<?php

class WrapperTest extends WP_UnitTestCase {

	/**
	 * Provides data for test_wrapper_exceptions.
	 *
	 * @return array Test cases.
	 */
	public function wrapper_exceptions_data() {
		return array(
			array( '/var/www/cpr/wp-content/themes/cpr/index.php', 'update' ),
			array( '/var/www/cpr/wp-content/themes/cpr/single.php', 'update' ),
			array( '/var/www/cpr/wp-content/themes/cpr/single-person.php', 'update' ),
			array( '/var/www/cpr/wp-content/themes/cpr/404.php', 'update' ),
			array( '/var/www/cpr/wp-content/themes/vip/cpr/category.php', 'update' ),
			array( '/var/www/cpr/wp-content/themes/vip/cpr/page.php', 'update' ),
			array( '/var/www/cpr/wp-content/plugins/msm-sitemap/templates/full-sitemap.php', 'ignore' ),
			array( '/var/www/cpr/wp-content/themes/vip/plugins/msm-sitemap/templates/full-sitemap.php', 'ignore' ),
		);
	}

	/**
	 * @dataProvider wrapper_exceptions_data
	 *
	 * @param  string $template Template path to test.
	 * @param  string $expected_result Expected result. Either 'update' or
	 *                                 'ignore' to update the path (to
	 *                                 wrapper.php) or ignore to keep as-is.
	 */
	public function test_wrapper_exceptions( $template, $expected_result ) {
		$new_template = Cpr\Wrapping::wrap( $template );
		if ( 'ignore' === $expected_result ) {
			$this->assertSame( $template, $new_template );
		} else {
			$this->assertNotSame( $template, $new_template );
			$this->assertContains( 'wrapper.php', $new_template );
		}
	}

	public function test_wrapper_exception_filter() {
		$template = '/var/www/cpr/wp-content/themes/cpr/index.php';

		$new_template = Cpr\Wrapping::wrap( $template );
		$this->assertNotSame( $template, $new_template );
		$this->assertContains( 'wrapper.php', $new_template );

		add_filter( 'cpr_skip_theme_wrapper', '__return_true' );
		$new_template = Cpr\Wrapping::wrap( $template );
		$this->assertSame( $template, $new_template );
		remove_filter( 'cpr_skip_theme_wrapper', '__return_true' );
	}

	public function test_404() {
		// Go to a 404 page
		$this->go_to( '?pagename=' . rand_str() );

		/**
		 * For Gutenberg, we need to define `$GLOBALS['pagenow']`.
		 *
		 * @see wp_deregister_script()
		 */
		$GLOBALS['pagenow'] = 'index.php';

		// Cpr\Wrapping will filter this to store the base template (404)
		$template = apply_filters( 'template_include', get_404_template() );

		$this->expectOutputRegex( '/error-404 not-found/' );
		include $template;
	}
}
