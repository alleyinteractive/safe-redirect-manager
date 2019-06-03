<?php
/**
 * Class for parsing a guest-author item.
 *
 * @package CPR
 */

namespace CPR\Migration\Guest_Author;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Guest_Author_Feed_Item {

	use \CPR\Migration\Traits\Story;

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {
		return true;
	}

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	public function get_unique_id() {
		return $this->source['nid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {
		$this->object['display_name'] = $this->source['title'];
		$this->object['user_login']   = sanitize_title( $this->source['title'] );
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() : bool {

		update_post_meta( $this->get_object_id(), 'cap-first_name', $this->source['field_first_name']['und'][0]['value'] ?? '' );
		update_post_meta( $this->get_object_id(), 'cap-last_name', $this->source['field_last_name']['und'][0]['value'] ?? '' );

		// Twitter + sanitization.
		update_post_meta(
			$this->get_object_id(),
			'twitter',
			str_replace( '@', '', ( $this->source['field_twitter']['und'][0]['title'] ?? '' ) )
		);

		// Description/bio.
		$description = $this->source['body']['und'][0]['value'] ?? '';
		$q_a         = $this->source['field_q_a_section']['und'][0]['value'] ?? '';
		if ( ! empty( $q_a ) ) {
			$description .= '<strong>Q & A</strong></br>' . $q_a;
		}
		$description = str_replace( '&nbsp;', '', $description );
		$description = wp_kses( $description, [ 'strong' => [] ] );
		update_post_meta( $this->get_object_id(), 'description', $description );
		update_post_meta( $this->get_object_id(), 'short_bio', esc_html( $this->source['body']['und'][0]['summary'] ?? '' ) );

		$this->set_section();
		$this->migrate_avatar();
		$this->migrate_title();

		return true;
	}

	/**
	 * Download and set the avatar as the featured image.
	 */
	public function migrate_avatar() {

		// Avatar has already been migrated, or doesn't exist.
		if (
			has_post_thumbnail( $this->get_object_id() )
			|| empty( $this->source['field_photo']['und'][0]['filename'] ?? '' )
		) {
			return;
		}

		$attachment_id = \Alleypack\create_attachment_from_url( $this->get_avatar_url() );
		if ( ! $attachment_id instanceof \WP_Error ) {
			update_post_meta( $this->get_object_id(), '_thumbnail_id', $attachment_id );
		}
	}

	/**
	 * Return the avatar url for this underwriter.
	 *
	 * @return string
	 */
	public function get_avatar_url() {

		// Get filename.
		$filename = $this->source['field_photo']['und'][0]['filename'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		return 'https://www.cpr.org/sites/default/files/styles/medium/public/' . $filename;
	}

	/**
	 * Migrate the job title from another source.
	 */
	public function migrate_title() {
		$title = \CPR\Migration\Migration::instance()->get_source_data_by_id(
			'job_titles',
			absint( $this->source['field_person_type']['und'][0]['value'] ?? 0 )
		);
		update_post_meta( $this->get_object_id(), 'title', $title['field_job_title_value'] ?? '' );
	}
}
