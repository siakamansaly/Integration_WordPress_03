<div class="es-field es-profile-pic">
	<input type='hidden' name='es_settings[email_logo_attachment_id]' value=''>

	<div class="es-field__label"><?php _e( 'Email Logo Image', 'es-plugin' ); ?>:</div>
	<div class="es-field__content">
		<div class="es-profile-photo-wrap">
			<a class='gallery-image-add button' href='#' data-uploader-title='<?php _e( 'Add image(s) to agent', 'es-plugin' ); ?>' data-uploader-button-text='<?php _e( 'Add image(s)', 'es-plugin' ); ?>'>
				<?php _e( 'Add image(s)', 'es-plugin' ); ?>
			</a>
			<ul id="es-media-list" class="es-attachment-logo" data-name="es_settings[email_logo_attachment_id]">
				<?php if ( $es_settings->email_logo_attachment_id && $image = wp_get_attachment_image_url( $es_settings->email_logo_attachment_id ) ) : ?>
					<li><input type='hidden' name='es_settings[email_logo_attachment_id]' value='<?php echo $es_settings->email_logo_attachment_id; ?>'>
						<div class='image-preview-wrap'>
							<a href='#' class='remove-image'><i class="fa fa-times-circle" aria-hidden="true"></i></a>
							<img src='<?php echo $image; ?>' class='image-preview'>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
