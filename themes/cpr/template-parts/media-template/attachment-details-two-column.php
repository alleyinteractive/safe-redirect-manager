<?php
/**
 * CPR Media Template Parts: Attachment Details Two Column
 *
 * Override for the standard attachment-details-two-column template.
 *
 * Since specifying a new template involves replacing the entire template with
 * new content, we have to copy over everything that we want to preserve from
 * the default template and add our own extensions. Therefore, most of what is
 * in this file comes from /wp-includes/media-template.php - search for
 * `tmpl-attachment-details-two-column`. Our extensions to the base template
 * are identified below. The phpcs ignore rules are predicated upon the fact
 * that most of this file was copied out of core and unmodified.
 *
 * @package CPR
 */

?>
<?php /* phpcs:disable WordPress.Security.EscapeOutput.UnsafePrintingFunction,WordPress.WP.I18n.MissingArgDomain,WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
<script type="text/html" id="tmpl-cpr-attachment-details-two-column">
	<div class="attachment-media-view {{ data.orientation }}">
		<div class="thumbnail thumbnail-{{ data.type }}">
			<# if ( data.uploading ) { #>
			<div class="media-progress-bar"><div></div></div>
			<# } else if ( data.sizes && data.sizes.large ) { #>
			<img class="details-image" src="{{ data.sizes.large.url }}" draggable="false" alt="" />
			<# } else if ( data.sizes && data.sizes.full ) { #>
			<img class="details-image" src="{{ data.sizes.full.url }}" draggable="false" alt="" />
			<# } else if ( -1 === jQuery.inArray( data.type, [ 'audio', 'video' ] ) ) { #>
			<img class="details-image icon" src="{{ data.icon }}" draggable="false" alt="" />
			<# } #>

			<# if ( 'audio' === data.type ) { #>
			<div class="wp-media-wrapper">
				<audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
					<source type="{{ data.mime }}" src="{{ data.url }}"/>
				</audio>
			</div>
			<# } else if ( 'video' === data.type ) {
			var w_rule = '';
			if ( data.width ) {
			w_rule = 'width: ' + data.width + 'px;';
			} else if ( wp.media.view.settings.contentWidth ) {
			w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
			}
			#>
			<div style="{{ w_rule }}" class="wp-media-wrapper wp-video">
				<video controls="controls" class="wp-video-shortcode" preload="metadata"
				<# if ( data.width ) { #>width="{{ data.width }}"<# } #>
				<# if ( data.height ) { #>height="{{ data.height }}"<# } #>
				<# if ( data.image && data.image.src !== data.icon ) { #>poster="{{ data.image.src }}"<# } #>>
				<source type="{{ data.mime }}" src="{{ data.url }}"/>
				</video>
			</div>
			<# } #>

			<div class="attachment-actions">
				<# if ( 'image' === data.type && ! data.uploading && data.sizes && data.can.save ) { #>
				<button type="button" class="button edit-attachment"><?php _e( 'Edit Image' ); ?></button>
				<# } else if ( 'pdf' === data.subtype && data.sizes ) { #>
				<?php _e( 'Document Preview' ); ?>
				<# } #>
			</div>
		</div>
	</div>
	<div class="attachment-info">
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved"><?php esc_html_e( 'Saved.' ); ?></span>
			</span>
		<div class="details">
			<div class="filename"><strong><?php _e( 'File name:' ); ?></strong> {{ data.filename }}</div>
			<div class="filename"><strong><?php _e( 'File type:' ); ?></strong> {{ data.mime }}</div>
			<div class="uploaded"><strong><?php _e( 'Uploaded on:' ); ?></strong> {{ data.dateFormatted }}</div>

			<div class="file-size"><strong><?php _e( 'File size:' ); ?></strong> {{ data.filesizeHumanReadable }}</div>
			<# if ( 'image' === data.type && ! data.uploading ) { #>
			<# if ( data.width && data.height ) { #>
			<div class="dimensions"><strong><?php _e( 'Dimensions:' ); ?></strong>
				<?php
				/* translators: 1: a number of pixels wide, 2: a number of pixels tall. */
				printf( __( '%1$s by %2$s pixels' ), '{{ data.width }}', '{{ data.height }}' );
				?>
			</div>
			<# } #>
			<# } #>

			<# if ( data.fileLength && data.fileLengthHumanReadable ) { #>
			<div class="file-length"><strong><?php _e( 'Length:' ); ?></strong>
				<span aria-hidden="true">{{ data.fileLength }}</span>
				<span class="screen-reader-text">{{ data.fileLengthHumanReadable }}</span>
			</div>
			<# } #>

			<# if ( 'audio' === data.type && data.meta.bitrate ) { #>
			<div class="bitrate">
				<strong><?php _e( 'Bitrate:' ); ?></strong> {{ Math.round( data.meta.bitrate / 1000 ) }}kb/s
				<# if ( data.meta.bitrate_mode ) { #>
				{{ ' ' + data.meta.bitrate_mode.toUpperCase() }}
				<# } #>
			</div>
			<# } #>

			<div class="compat-meta">
				<# if ( data.compat && data.compat.meta ) { #>
				{{{ data.compat.meta }}}
				<# } #>
			</div>
		</div>

		<div class="settings">
			<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
			<# if ( 'image' === data.type ) { #>
			<label class="setting" data-setting="alt">
				<span class="name"><?php _e( 'Alternative Text' ); ?></span>
				<input type="text" value="{{ data.alt }}" aria-describedby="alt-text-description" {{ maybeReadOnly }} />
			</label>
			<p class="description" id="alt-text-description"><?php echo $alt_text_description; ?></p>
			<# } #>
			<?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
				<label class="setting" data-setting="title">
					<span class="name"><?php _e( 'Title' ); ?></span>
					<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
				</label>
			<?php endif; ?>
			<# if ( 'audio' === data.type ) { #>
			<?php
			foreach ( array(
				'artist' => __( 'Artist' ),
				'album'  => __( 'Album' ),
			) as $key => $label ) :
				?>
				<label class="setting" data-setting="<?php echo esc_attr( $key ); ?>">
					<span class="name"><?php echo $label; ?></span>
					<input type="text" value="{{ data.<?php echo $key; ?> || data.meta.<?php echo $key; ?> || '' }}" />
				</label>
			<?php endforeach; ?>
			<# } #>
			<label class="setting" data-setting="caption">
				<span class="name"><?php _e( 'Caption' ); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
			</label>
			<label class="setting" data-setting="description">
				<span class="name"><?php _e( 'Description' ); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
			</label>
			<div class="setting">
				<span class="name"><?php _e( 'Uploaded By' ); ?></span>
				<span class="value">{{ data.authorName }}</span>
			</div>
			<# if ( data.uploadedToTitle ) { #>
			<div class="setting">
				<span class="name"><?php _e( 'Uploaded To' ); ?></span>
				<# if ( data.uploadedToLink ) { #>
				<span class="value"><a href="{{ data.uploadedToLink }}">{{ data.uploadedToTitle }}</a></span>
				<# } else { #>
				<span class="value">{{ data.uploadedToTitle }}</span>
				<# } #>
			</div>
			<# } #>
			<label class="setting" data-setting="url">
				<span class="name"><?php _e( 'Copy Link' ); ?></span>
				<input type="text" value="{{ data.url }}" readonly />
			</label>
			<?php /* phpcs:enable */ ?>
			<?php /* Begin CPR customizations. */ ?>
			<# if ( 'audio' === data.type && data.meta.bitrate ) { #>
				<# if ( 2 === data.meta.cpr_transcoding_status ) { #>
					<label class="setting">
						<span class="name"><?php esc_html_e( 'MP3', 'cpr' ); ?></span>
						<span class="value"><?php esc_html_e( '(on-demand audio and podcasts)', 'cpr' ); ?></span>
						<input type="text" value="{{ data.meta.cpr_audio_mp3_url }}" readonly />
					</label>
					<label class="setting">
						<span class="name"><?php esc_html_e( 'Stereo M4A', 'cpr' ); ?></span>
						<span class="value"><?php esc_html_e( '(on-demand audio for supported players)', 'cpr' ); ?></span>
						<input type="text" value="{{ data.meta.cpr_audio_stereo_url }}" readonly />
					</label>
					<label class="setting">
						<span class="name"><?php esc_html_e( 'Mono M4A', 'cpr' ); ?></span>
						<span class="value"><?php esc_html_e( '(NPR One)', 'cpr' ); ?></span>
						<input type="text" value="{{ data.meta.cpr_audio_mono_url }}" readonly />
					</label>
				<# } else { #>
					<div class="setting">
						<span class="name"><?php esc_html_e( 'Encode Audio', 'cpr' ); ?></span>
						<span class="value cpr-encode-audio-container">
							<# if ( 0 === data.meta.cpr_transcoding_status ) { #>
								<div class="cpr-encode-audio-buttons">
									<button role="button" class="cpr-encode-audio hide-if-no-js button" data-cpr-audio-id="{{ data.id }}" data-cpr-audio-type="news-spoken" aria-label="<?php esc_attr_e( 'News/Spoken', 'cpr' ); ?>"><?php esc_attr_e( 'News/Spoken', 'cpr' ); ?></button>
									<button role="button" class="cpr-encode-audio hide-if-no-js button" data-cpr-audio-id="{{ data.id }}" data-cpr-audio-type="music" aria-label="<?php esc_attr_e( 'Music', 'cpr' ); ?>"><?php esc_attr_e( 'Music', 'cpr' ); ?></button>
								</div>
							<# } else if ( 1 === data.meta.cpr_transcoding_status ) { #>
								<div class="cpr-notification-message cpr-notification-message-processing">
									<?php esc_html_e( 'Audio files are being transcoded. Encoded file URLs should be available shortly.', 'cpr' ); ?>
								</div>
							<# } else if ( 3 === data.meta.cpr_transcoding_status ) { #>
								<div class="cpr-notification-message cpr-notification-message-upload-error">
									<?php esc_html_e( 'Upload to the transcoding system failed.', 'cpr' ); ?>
								</div>
							<# } #>
						</span>
					</div>
				<# } #>
			<# } #>
			<?php /* End CPR customizations. */ ?>
			<?php /* phpcs:disable WordPress.Security.EscapeOutput.UnsafePrintingFunction,WordPress.WP.I18n.MissingArgDomain,WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			<div class="attachment-compat"></div>
		</div>

		<div class="actions">
			<a class="view-attachment" href="{{ data.link }}"><?php _e( 'View attachment page' ); ?></a>
			<# if ( data.can.save ) { #> |
			<a href="{{ data.editLink }}"><?php _e( 'Edit more details' ); ?></a>
			<# } #>
			<# if ( ! data.uploading && data.can.remove ) { #> |
			<?php if ( MEDIA_TRASH ) : ?>
				<# if ( 'trash' === data.status ) { #>
				<button type="button" class="button-link untrash-attachment"><?php _e( 'Restore from Trash' ); ?></button>
				<# } else { #>
				<button type="button" class="button-link trash-attachment"><?php _e( 'Move to Trash' ); ?></button>
				<# } #>
			<?php else : ?>
				<button type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently' ); ?></button>
			<?php endif; ?>
			<# } #>
		</div>

	</div>
</script>
<?php /* phpcs:enable */ ?>
