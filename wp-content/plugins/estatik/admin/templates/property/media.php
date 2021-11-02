<?php
/**
 * @var Es_Property $entity
 */
?>

<p><?php _e( 'Here you can add video and upload images. Please enter video code in iframe format, e.g. -', 'es-plugin' ); ?></p>

<p><code>
		<?php echo htmlspecialchars('<iframe width="700" height="394" src="https://www.youtube.com/embed/SPyHzY-KnA4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'); ?>
    </code>
</p>

<div class="es-field__wrapper es-field__wrapper--bordered">
    <div class="es-field__label">
        <label for="video-tour"><?php _e( 'Embed the code', 'es-plugin' ); ?>:</label>
    </div>
    <div class="es-field__input">
        <input type="text" name="property[video]" id="video-tour" value="<?php echo ! empty( $entity->video ) ? $entity->video : ''; ?>">
    </div>
</div>

<table class='form-table'>
    <tr>
        <td>
			<?php if ( get_theme_support( 'post-thumbnails' ) ): ?>

                <a class='gallery-add button es-btn es-btn-orange-bordered' href='#' data-uploader-title='<?php _e( 'Add image(s) to property', 'es-plugin' ); ?>' data-uploader-button-text='<?php _e( 'Add image(s)', 'es-plugin' ); ?>'>
					<?php _e( 'Add image(s)', 'es-plugin' ); ?>
                </a>

                <ul id='es-media-list'>
					<?php $images = ! empty( $entity->gallery ) ? $entity->gallery : array(); ?>
					<?php if ( ! empty( $images ) ): ?>
						<?php foreach ( $images as $key => $value ):
                            if ( is_numeric( $value ) ) :
                            $image = wp_get_attachment_image_src( $value ); ?>
                            <li>
                                <input type='hidden' name='property[gallery][<?php echo $key; ?>]' value='<?php echo $value; ?>'>
                                <div class="image-preview-wrap">
                                    <a class='remove-image' href='#'><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                    <a href="#" class="drag-image"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                                    <img class='image-preview' src='<?php echo $image[0]; ?>'>
                                </div>
                            </li>
						    <?php endif;
						endforeach; ?>
					<?php endif; ?>
                </ul>

			<?php else: ?>
                <p><?php echo sprintf( wp_kses( __( 'Your theme has no <a href="%s" target="_blank">post thumbnail support</a>.', 'es-plugin' ), array(
						'a' => array( 'href' => array(), 'target' => array() ) ) ), 'https://codex.wordpress.org/Function_Reference/get_theme_support' ); ?></p>
			<?php endif; ?>
        </td>
    </tr>
</table>
