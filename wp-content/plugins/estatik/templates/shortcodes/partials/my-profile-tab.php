<?php

/**
 * @var $options array
 */

?>

<form method="POST" action="" enctype="multipart/form-data">
<h2 class="es-profile__tab-title"><?php echo $options['label']; ?></h2>

<?php wp_nonce_field( 'es_save_profile', 'es_save_profile' ); ?>

<?php if ( is_user_logged_in() ) : ?>
    <?php if ( $entity = es_get_user_entity( get_current_user_id() ) ) :
		$image_url = $entity->get_image_url( 'es-agent-size' );
		$image_url = $image_url ? $image_url : ES_PLUGIN_URL . '/assets/images/agent.png';
		$user = $entity->get_entity(); ?>

        <div class="es-profile__inner-profile">
            <div class="es-profile__image-wrap">
                <div class="es-profile__image-wrap-inner">
                    <div class="js-es-image">
                        <img src="<?php echo $image_url; ?>" alt="<?php echo __( 'Profile image', 'es-plugin' ); ?>">
                    </div>
                    <?php if ( $entity->profile_attachment_id ) : ?>
                        <input type="hidden" name="es_profile[profile_attachment_id]" value="<?php echo $entity->profile_attachment_id; ?>"/>
                    <?php endif; ?>
                    <a href="#" class="js-trigger-upload es-upload-photo-btn" data-selector="#profile-image-file">
                        <i class="fa fa-camera" aria-hidden="true"></i><?php _e( 'Change Photo', 'es-plugin' ); ?>
                    </a>
                    <input type="file" name="agent_photo" id="profile-image-file" class="js-es-input-image" style="display: none;">
                </div>
            </div>
            <div class="es-profile__info-wrap">

                <div class="es-fields__row">
                    <div class="es-field">
                        <label for="es-field-name" class="es-field__label"><?php _e( 'Name', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" id="es-field-name" name="es_profile[name]" value="<?php echo $entity->get_full_name(); ?>"/>
                        </div>
                    </div>
                    <div class="es-field">
                        <label for="es-field-username" class="es-field__label"><?php _e( 'Username', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" value="<?php echo $user->user_login; ?>" readonly id="es-field-username" name="user_login"/>
                        </div>
                    </div>
                </div>
                <div class="es-fields__row">
                    <div class="es-field">
                        <label for="es-field-email" class="es-field__label"><?php _e( 'E-mail', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" id="es-field-email" readonly name="email" value="<?php echo $user->user_email; ?>"/>
                        </div>
                    </div>
                    <div class="es-field">
                        <label for="es-field-telephone" class="es-field__label"><?php _e( 'Telephone', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" id="es-field-telephone" name="es_profile[tel]" value="<?php echo $entity->tel; ?>"/>
                        </div>
                    </div>
                </div>

                <div class="es-fields__row">
                    <div class="es-field">
                        <label for="es-field-password" class="es-field__label"><?php _e( 'Password', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" id="es-field-password" name="pass1" value=""/>
                        </div>
                    </div>
                    <div class="es-field">
                        <label for="es-field-password2" class="es-field__label"><?php _e( 'Confirm Password', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <input type="text" id="es-field-password2" name="pass2" value=""/>
                        </div>
                    </div>
                </div>

                <div class="es-fields__row">
                    <div class="es-field">
                        <label for="es-field-description" class="es-field__label"><?php _e( 'Description', 'es-plugin' ); ?></label>
                        <div class="es-field__content">
                            <textarea  id="es-field-description" name="description"><?php echo esc_textarea( $user->description ); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="right">
                    <input type="submit" class="es-btn-primary es-save-profile" value="<?php _e( 'Save', 'es-plugin' ); ?>">
                </div>

            </div>
        </div>
    <?php endif;
endif; ?>
</form>
