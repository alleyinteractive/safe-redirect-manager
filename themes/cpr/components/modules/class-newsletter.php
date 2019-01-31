<?php
/**
 * Newsletter component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Newsletter.
 */
class Newsletter extends \WP_Components\Component {

	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'newsletter';

	/**
	 * Get the default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'News That Matters, Delivered To Your Inbox', 'cpr' );
	}

	/**
	 * Get the default tagline.
	 *
	 * @return string
	 */
	public function get_default_tagline() {
		return __( 'Sign up for a smart, compelling, and sometimes funny take on your daily news briefing.', 'cpr' );
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		$settings = get_option( 'cpr-settings' );

		return [
			'heading' => $settings['engagement']['newsletter']['heading'] ?? $this->get_default_heading(),
			'tagline' => $settings['engagement']['newsletter']['tagline'] ?? $this->get_default_tagline(),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_heading(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Heading', 'cpr' ),
				]
			),
			'tagline' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_tagline(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Tagline', 'cpr' ),
				]
			),
			'account_id' => new \Fieldmanager_Textfield( __( 'Emma account ID', 'cpr' ) ),
			'public_key' => new \Fieldmanager_Textfield( __( 'Emma API public key', 'cpr' ) ),
			'private_key' => new \Fieldmanager_Textfield( __( 'Emma API private key', 'cpr' ) ),
		];
	}

	/**
	 * Callback for newsletter form route.
	 *
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function get_route_response( $request ) {
		$request_url_base = 'https://api.e2ma.net/';

		// Get params.
		$email = $request->get_param( 'email' ) ?? '';
		$group = $request->get_param( 'group' ) ?? '';

		// Send back validation if email is somehow empty.
		if ( empty( $email ) ) {
			return \WP_Irving\REST_API\Form_Endpoint::response_invalid(
				[
					'email' => __( 'Please enter an email address.', 'cpr' ),
				],
			);
		}

		// Get necessary API connection fields from settings.
		$settings = get_option( 'cpr-settings' );
		$account_id = $settings['engagement']['newsletter']['account_id'] ?? '';
		$public_key = $settings['engagement']['newsletter']['public_key'] ?? '';
		$private_key = $settings['engagement']['newsletter']['private_key'] ?? '';

		// Send back 500 error if any of these fields are missing
		if ( empty( $account_id ) || empty( $public_key ) || empty( $private_key ) ) {
			return \WP_Irving\REST_API\Form_Endpoint::response_error();
		}

		// All API calls must include an HTTP Basic authentication header containing the public & private API keys for your account.
		// build HTTP Basic Auth headers
		$headers = array(
			'Content-Type: application/json; charset=utf-8',
			'Accept:application/json, text/javascript, */*; q=0.01',
			'Authorization' => 'Basic ' . base64_encode( $public_key . ':' . $private_key ),
		);

		// POST body.
		$body = [];

		if ( 'lookout' === $group ) {
			$body = wp_json_encode( [
				'fields'    => [
					'lookoutemail'   => 'Yes'
				],
				'group_ids' => [ '4507989' ],
				'email'     => $email,
			] );
		} else if ( 'spinsider' === $group ) {
			$body = wp_json_encode( [
				'fields'    => [
					'spinsideremail'   => 'Yes'
				],
				'group_ids' => [ '5116245' ],
				'email'     => $email,
			] );
		}

		// Send off to /add endpoint.
		$emma_response = wp_remote_post( $request_url_base . $account_id . '/members/add',
			[
				'headers' => $headers,
				'body' => $body,
			]
		);

		// If it fails, send back a validation message.
		if ( is_wp_error( $emma_response ) ) {
			return \WP_Irving\REST_API\Form_Endpoint::response_invalid(
				[
					'email' => __( 'There was an error adding you to our mailing list. Please check that the email address you entered is correct.', 'thrive-global' ),
				]
			);
		}

		// We're good.
		if ( $emma_response ) {
			return \WP_Irving\REST_API\Form_Endpoint::response_success();
		}

		// If something else unknown goes wrong send back a 500.
		return \WP_Irving\REST_API\Form_Endpoint::response_error();
	}
}
