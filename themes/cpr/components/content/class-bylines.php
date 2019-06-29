<?php
/**
 * Content Bylines component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Bylines class.
 */
class Bylines extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \WP_Components\WP_User;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-bylines';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'pre_byline' => __( 'By ', 'cpr' ),
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		// Setup byline using guest authors.
		$coauthors = get_coauthors( $this->post->ID );

		// Loop through coauthors, adding an image for each.
		foreach ( $coauthors as $coauthor ) {
			$this->append_child(
				( new \WP_Components\Image() )
					->set_post_id( $coauthor->ID ?? 0 )
					->set_config_for_size( 'avatar' )
			);
		}

		// Loop through coauthors, adding the meta data for each.
		$bylines = [];

		foreach ( $coauthors as $coauthor ) {
			$byline = new \WP_Components\Byline();

			if ( 'guest-author' === ( $coauthor->type ?? '' ) ) {
				$byline->set_guest_author( $coauthor );
			}
			$bylines[] = $byline;
		}

		if ( ! empty( $bylines ) ) {
			$this->append_children( $bylines );
		} else {
			$this->set_invalid();
		}

		return $this;
	}
}
