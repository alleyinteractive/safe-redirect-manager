<?php
require_once get_template_directory() . '/inc/byline_class.php';

// For Largo Custom Bylines
class Onsomething_Custom_Byline extends Largo_Custom_Byline {

    protected $exclude_author = false;

    function __construct($args) {
        $this->exclude_author = !empty($args['exclude_author']) ? $args['exclude_author'] : false;
        parent::__construct($args);
    }

    /**
     * differs from Largo_Byline in following ways:
     * - no avatar
     * - no job title
     * - no twitter
     */
    function generate_byline() {
        ob_start();
        if ($this->exclude_author === false) {
            $this->author_link();
        }
        $this->maybe_published_date();
        $this->edit_link();

        $this->output = ob_get_clean();
    }

    /**
     * A wrapper around largo_time to determine when the post was published
     */
    function published_date() {
        echo sprintf(
            '<time class="entry-date updated dtstamp pubdate" datetime="%1$s">%2$s</time>',
            esc_attr( get_the_date( 'c', $this->post_id ) ),
            largo_time( false, $this->post_id )
        );
    }
}
