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
class Bylines extends \WP_Components\Byline {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-bylines';

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		// Setup byline using guest authors.
		$coauthors = get_coauthors( $this->post->ID );
		$bylines   = [];

		// Loop through coauthors, creating new byline objects as needed.
		foreach ( $coauthors as $coauthor ) {
			$byline = new Byline();

			if ( 'guest-author' === ( $coauthor->type ?? '' ) ) {
				$byline->set_guest_author( $coauthor );
			}

			$bylines[] = $byline;
		}

		$this->append_children( $bylines );

		return $this;
	}
}
