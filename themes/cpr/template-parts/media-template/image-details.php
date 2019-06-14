<script type="text/html" id="tmpl-cpr-attachement-details">
<h2>
		<?php esc_html_e( 'Attachment Details' ); ?>
	</h2>
	<div class="attachment-info">
		<div class="thumbnail thumbnail-{{ data.type }}">
			<# if ( data.uploading ) { #>
				<div class="media-progress-bar"><div></div></div>
			<# } else if ( 'image' === data.type && data.sizes.large ) { #>
				<img src="{{ data.sizes.large.url }}" draggable="false" alt="" />
			<# } else if ( 'image' === data.type && data.sizes ) { #>
				<img src="{{ data.size.url }}" draggable="false" alt="" />
			<# } else { #>
				<img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
			<# } #>
		</div>
		<div class="details">
			<div class="filename">{{ data.filename }}</div>
			<div class="attachment-id"><?php esc_html_e( 'ID:', 'people' ); ?> {{ data.id }}</div>
			<div class="uploaded">{{ data.dateFormatted }}</div>

			<div class="file-size">{{ data.filesizeHumanReadable }}</div>
			<# if ( 'image' === data.type && ! data.uploading ) { #>
				<# if ( data.width && data.height ) { #>
					<div class="dimensions">{{ data.width }} &times; {{ data.height }}</div>
				<# } #>

				<# if ( data.can.save && data.sizes ) { #>
					<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php esc_html_e( 'Edit Image', 'people' ); ?></a>
				<# } #>
			<# } #>

			<# if ( data.fileLength ) { #>
				<div class="file-length"><?php esc_html_e( 'Length:', 'people' ); ?> {{ data.fileLength }}</div>
			<# } #>

			<# if ( ! data.uploading && data.can.remove ) { #>
				<?php if ( MEDIA_TRASH ) : ?>
				<# if ( 'trash' === data.status ) { #>
					<button type="button" class="button-link untrash-attachment"><?php esc_html_e( 'Untrash', 'people' ); ?></button>
				<# } else { #>
					<button type="button" class="button-link trash-attachment"><?php echo esc_html_x( 'Trash', 'verb', 'people' ); ?></button>
				<# } #>
				<?php else : ?>
					<button type="button" class="button-link delete-attachment"><?php esc_html_e( 'Delete Permanently', 'people' ); ?></button>
				<?php endif; ?>
			<# } #>

			<div class="compat-meta">
				<# if ( data.compat && data.compat.meta ) { #>
					{{{ data.compat.meta }}}
				<# } #>
			</div>
		</div>
	</div>
	<div>
		<span class="people-settings-save-status settings-save-status">
			<span class="spinner"></span>
			<span class="saved"><?php esc_html_e( 'Saved.', 'people' ); ?></span>
		</span>
		<span class="settings-save-status">
			<button type="button" class="button people-force-save-attachment-details"><?php esc_html_e( 'Save Changes', 'people' ); ?></button>
		</span>
	</div>
	<?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
	<label class="setting" data-setting="slide_title">
		<span class="name"><?php esc_html_e( 'Title', 'people' ); ?></span>
		<textarea {{ maybeReadOnly }} id="rte-title">{{ data.slide_title }}</textarea>
	</label>
	<?php endif; ?>
	<label class="setting" data-setting="custom_caption">
		<span class="name"><?php esc_html_e( 'Custom Caption', 'people' ); ?></span>
		<textarea {{ maybeReadOnly }} id="rte-cust">{{ data.custom_caption }}</textarea>
	</label>
	<label class="setting" data-setting="description">
		<span class="name"><?php esc_html_e( 'Credit', 'people' ); ?></span>
		<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
	</label>
	<# if ( 'image' === data.type ) { #>
		<label class="setting" data-setting="alt">
			<span class="name"><?php esc_html_e( 'Alt Text', 'people' ); ?></span>
			<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
			<span class="description alt-text-description"><?php esc_html_e( 'Name of picture/subject', 'People' ); ?></span>
		</label>
	<# } #>

	<label class="setting" data-setting="url">
		<span class="name"><?php esc_html_e( 'URL', 'people' ); ?></span>
		<input type="text" value="{{ data.url }}" readonly />
	</label>
	<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
	<label class="setting" data-setting="internal_title">
		<span class="name"><?php esc_html_e( 'Internal Title', 'people' ); ?></span>
		<textarea {{ maybeReadOnly }}>{{ data.internal_title }}</textarea>
	</label>
	<label class="setting" data-setting="caption">
		<span class="name"><?php esc_html_e( 'Metadata Notes', 'people' ); ?></span>
		<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
	</label>
	<# if ( 'audio' === data.type ) { #>
	<?php foreach ( array(
		'artist' => __( 'Artist', 'people' ),
		'album' => __( 'Album', 'people' ),
	) as $key => $label ) : ?>
	<label class="setting" data-setting="<?php echo esc_attr( $key ); ?>">
		<span class="name"><?php esc_html_e( $label ); ?></span>
		<input type="text" value="{{ data.<?php esc_html_e( $key ); ?> || data.meta.<?php esc_html_e( $key ); ?> || '' }}" />
	</label>
	<?php endforeach; ?>
	<# } #>
</script>
