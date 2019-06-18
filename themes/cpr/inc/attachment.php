<?php
/**
 * Attachment.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Add attachment fields.
 *
 * @return array Added fields.
 */
function add_attachment_fields() : array {
	return [
		'credit'  => [
			'label' => __( 'Credit', 'cpr' ),
			'input' => 'text',
			'helps' => __( 'Image Credit.', 'cpr' ),
		],
		'caption' => [
			'label' => __( 'Caption', 'cpr' ),
			'input' => 'text',
			'helps' => __( 'Override image caption.', 'cpr' ),
		],
	];
}
add_filter( 'alleypack_get_media_fields', __NAMESPACE__ . '\add_attachment_fields' );

/**
 * Saves the credit from the image's EXIF/IPTC metadata as attachment meta.
 *
 * @param array $metadata      An array of attachment metadata.
 * @param int   $attachment_id Current attachment ID.
 * @return array Metadata for attachment.
 */
function add_credit_from_image_metadata( $metadata, $attachment_id ) {
	$credit = get_post_meta( $attachment_id, 'credit', true );
	if ( ! empty( $metadata['image_meta']['credit'] ) && empty( $credit ) ) {
		update_post_meta( $attachment_id, 'credit', $metadata['image_meta']['credit'] );
	}

	$caption = get_post_meta( $attachment_id, 'caption', true );
	if ( ! empty( $metadata['image_meta']['caption'] ) && empty( $caption ) ) {
		update_post_meta( $attachment_id, 'caption', $metadata['image_meta']['caption'] );
	}
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', __NAMESPACE__ . '\add_credit_from_image_metadata', 10, 2 );
