<?php
/**
 * Thrive Global Single.
 *
 * @package CPR
 */

namespace Cpr\Template;

/**
 * Single routing and components for WP Irving.
 */
class Single {
	/**
	 * Post object for this single.
	 *
	 * @var null|\WP_Post
	 */
	public $post = null;

	/**
	 * WP_Query object for this request.
	 *
	 * @var null|\WP_Query
	 */
	public $wp_query = null;

	/**
	 * Return Irving components for Single templates.
	 *
	 * @param  array     $data     Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array Update response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {
		$this->wp_query = $wp_query;

		// Get and validate the post object.
		$this->post = $wp_query->get_queried_object();
		if ( ! $this->post instanceof \WP_Post || 'publish' !== $this->post->post_status ) {
			return ( new \Cpr\Template\Error() )->get_irving_components( $data, $wp_query );
		}

		// Modify the head.
		add_action( 'wp_irving_head', [ $this, 'modify_head' ] );

		$data['page'][] = $this->get_body();

		return $data;
	}

	/**
	 * Add article-specific head modifications=.
	 *
	 * @param \WP_Irving\Component\Head $head Head object.
	 */
	public function modify_head( $head ) {
		// Add AMP link.
		$head->add_link( 'amphtml', get_permalink( $this->post->ID ) . 'amp/' );
	}

	/**
	 * Get the body component(s).
	 *
	 * @return \Cpr\Component\Body
	 */
	public function get_body() {

		// Initialize new Body component.
		$body = ( new \Cpr\Component\Body() )
			->set_config( 'classes', [ 'single' ] );

		// Add post content.
		$body->set_children(
			[
				( new \WP_Irving\Component\Content() )
					->set_post( $this->post )
					->set_config( 'published_date', $this->get_publish_date( 'F j, Y', $this->post ) )
					->set_children( \WP_Irving\Component\Term::get_term_components( (array) $tags ), true )
					->set_name( 'article-body' ),
			],
			true
		);

		// Add Comments and Related Posts grid.
		if ( comments_open( $this->post->ID ) ) {
			$body->set_children(
				[
					( new \WP_Irving\Component\Disqus() )
						->set_config_from_post( $this->post ),
				],
				true
			);
		}

		return $body;
	}
}
