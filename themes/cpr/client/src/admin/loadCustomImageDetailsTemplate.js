/**
 * Load our custom Image Details template to override attributes like the caption
 * and add other image attributes like the title.  This template is shown when
 * viewing a gallery within the tinymce editor.
 */
/* eslint-disable */
export function loadCustomImageDetailsTemplate() {
  wp.media.view.Attachment.Details.prototype.template = wp.template( 'cpr-attachment-details' );
}
/* eslint-enable */
