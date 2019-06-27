<?php
/**
 * Author Header component.
 *
 * @package CPR
 */

namespace CPR\Components\Header;

/**
 * Author Header.
 */
class Author_Header extends \WP_Components\Component {

	use \WP_Components\Guest_Author;
	use \WP_Components\Author;
	use \WP_Components\WP_User;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'author-header';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'name'      => '',
			'twitter'   => '',
			'short_bio' => '',
			'title'     => '',
		];
	}

	/**
	 * Hook into author being set.
	 *
	 * @return self
	 */
	public function author_has_set() : self {
		$this->set_config( 'name', $this->get_author_display_name() );

		if ( 'guest_author' === $this->get_author_type() ) {
			$this->merge_config(
				[
					'short_bio' => get_post_meta( $this->get_author_id(), 'short_bio', true ),
					'title'     => get_post_meta( $this->get_author_id(), 'title', true ),
					'twitter'   => get_post_meta( $this->get_author_id(), 'twitter', true ),
				]
			);

			$this->guest_author_set_avatar( 'avatar' );

			$description = get_post_meta( $this->get_author_id(), 'description', true );
			if ( ! empty( $description ) ) {
				$this->append_child(
					( new \WP_Components\HTML() )
						->set_config( 'content', apply_filters( 'the_content', $description ) )
				);
			}
		}

		return $this;
	}
}
