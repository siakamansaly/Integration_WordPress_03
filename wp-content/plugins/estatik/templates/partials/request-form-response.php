<?php if ( $response['status'] == 'success' ) : ?>
    <h3><i class="fa fa-check" aria-hidden="true"></i><?php _e( 'Your message was sent', 'es-plugin' ); ?></h3>
    <p><?php echo $response['message']; ?></p>
    <button class="js-es-request-form-show"><?php _e( 'Back', 'es-plugin' ); ?></button>
<?php else : ?>
    <h3><i class="fa fa-times" aria-hidden="true"></i><?php _e( 'Your message was\'nt sent', 'es-plugin' ); ?></h3>
    <p><?php echo $response['message']; ?></p>
    <button class="js-es-request-form-show"><?php _e( 'Try again', 'es-plugin' ); ?></button>
<?php endif;
