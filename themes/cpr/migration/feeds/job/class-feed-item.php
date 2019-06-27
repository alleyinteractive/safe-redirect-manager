<?php
/**
 * Class for parsing a job item.
 *
 * @package CPR
 */

namespace CPR\Migration\Job;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'job';

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
		$this->object['post_content'] = $this->get_job_description();
		$this->object['post_status']  = 'publish';
		$this->object['post_title']   = esc_html( $this->source['title'] );
	}

	/**
	 * Get the fully-formed job description.
	 *
	 * @return string Job description for post content.
	 */
	public function get_job_description() {

		// Assemble full body content.
		$job_description_parts = [
			sprintf(
				'<p><a href="%1$s">View Full Description (PDF)</a></p>',
				esc_url( \CPR\Migration\Migration::get_url_from_uri( $this->source['field_file']['und'][0]['uri'] ?? '' ) ?? '' )
			),
			$this->source['body']['und'][0]['value'] ?? '',
		];

		// Append education and experience.
		if ( ! empty( $this->source['field_education_experience_req']['und'][0]['value'] ) ) {
			$job_description_parts[] = '<h3>Education & Experience Requirements</h3>';
			$job_description_parts[] = $this->source['field_education_experience_req']['und'][0]['value'];
		}

		// Append application requirements.
		if ( ! empty( $this->source['field_application_requirements']['und'][0]['value'] ) ) {
			$job_description_parts[] = '<h3>Application Requirements</h3>';
			$job_description_parts[] = $this->source['field_application_requirements']['und'][0]['value'];
		}

		$job_description = implode( '', array_filter( $job_description_parts ) );

		return ( new \Alleypack\Block\Converter( $job_description ) )->convert_to_block();
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() : bool {
		$this->migrate_job_pdf();
		return true;
	}

	/**
	 * Migrate the job PDF and replace all urls.
	 */
	public function migrate_job_pdf() {

		// Get the pdf description url and create it.
		$legacy_pdf_url = \CPR\Migration\Migration::get_url_from_uri( $this->source['field_file']['und'][0]['uri'] ?? '' );
		$new_pdf_url    = \Alleypack\create_or_get_attachment_from_url( $legacy_pdf_url ?? '' );

		// If it went well, replace the original with the new one.
		if ( ! empty( $new_pdf_url ) ) {
			$this->object['post_content'] = str_replace( $legacy_pdf_url, $new_pdf_url, $this->object['post_content'] );
			wp_update_post( $this->object );
		}
	}
}
