<?php

/**
 * @var $user WP_User
 * @var $user_entity Es_User
 */

$username = ! empty( $user->user_login ) ? $user->user_login : $data['user_login']; ?>

<div class='wrap es-wrap es-settings-wrap'>
	<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
		<?php echo es_get_logo(); ?>
	<?php endif; ?>

	<h1><?php echo $title; ?></h1>

	<form action='' method="POST">

		<div class="es-header-button">
			<span><?php _e( 'Please fill up your user information below and click save to finish.', 'es-plugin' ); ?></span>
			<input type="submit" value="<?php _e( 'Save', 'es-plugin' ); ?>">
		</div>

		<?php $msg = new Es_Messenger( 'es_message' ); $msg->render_messages(); ?>

		<div class="es-box es-agent-profile">

            <?php do_action( 'es_admin_before_buyer_profile_fields' ); ?>

			<div class="es-field">
				<label class="es-field__label" for="es-field-name"><?php _e( 'Name', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<input id="es-field-name" type="text" value="<?php echo ! empty( $user_entity->get_full_name() ) ? $user_entity->get_full_name() : $data['name']; ?>" name="es_user[name]">
				</div>
			</div>

			<div class="es-field">
				<label class="es-field__label" for="es-field-username"><?php _e( 'User name', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<input <?php echo $username ? 'readonly' : ''; ?> id="es-field-username" type="text" value="<?php echo $username; ?>" required name="user_login">
				</div>
			</div>

			<div class="es-field">
				<label class="es-field__label" for="es-field-email"><?php _e( 'Email', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<input type="text" id="es-field-email" value="<?php echo ! empty( $user->user_email ) ? $user->user_email : $data['email']; ?>" required name="email">
				</div>
			</div>

			<div class="es-field">
				<label class="es-field__label" for="es-field-tel"><?php _e( 'Telephone', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<input type="text" id="es-field-tel" value="<?php echo ! empty( $user_entity->tel ) ? $user_entity->tel: $data['tel']; ?>" name="es_user[tel]">
				</div>
			</div>

			<div class="es-field">
				<label class="es-field__label" for="es-field-pass"><span class="es-settings-label"><?php _e( 'Password', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<input type="password" name="pass1" id="es-field-pass"/>
				</div>
			</div>

			<input type="hidden" name="role" value="<?php echo $user_entity::get_role_name(); ?>"/>

			<div class="es-field">
				<label class="es-field__label" for="es-field-about"><?php _e( 'About', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
					<textarea name="description" id="es-field-about" cols="40" rows="4"><?php echo ! empty( $user->description ) ? $user->description : $data['description']; ?></textarea>
				</div>
			</div>

			<div class="es-field es-profile-pic">
				<label class="es-field__label"><?php _e( 'Profile photo', 'es-plugin' ); ?>:</label>
				<div class="es-field__content">
                    <div class="es-profile-photo-wrap">
                        <a class='gallery-image-add button' href='#' data-uploader-title='<?php _e( 'Add image(s) to user', 'es-plugin' ); ?>' data-uploader-button-text='<?php _e( 'Add image(s)', 'es-plugin' ); ?>'>
                            <?php _e( 'Add image(s)', 'es-plugin' ); ?>
                        </a>
                        <ul id="es-media-list">
                            <?php if ( ! empty( $user_entity ) && $user_entity->get_image_url()  ) : ?>
                                <li><input type='hidden' name='es_user[profile_attachment_id]' class="es-agent__img--input" value='<?php echo $user_entity->profile_attachment_id; ?>'>
                                    <div class='image-preview-wrap'>
                                        <a href='#' class='remove-image'><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                        <img src='<?php echo $user_entity->get_image_url(); ?>' class='image-preview'>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
			</div>

			<div class="es-fields-custom__wrap">
				<?php $custom_data = $user_entity->get_custom_data();

                if ( ! empty( $user_entity ) && $custom_data ): ?>
					<?php foreach ( $custom_data as $item ): ?>
                        <div class="es-field es-field-custom">
                            <label class="es-field__label"><?php echo key( $item ); ?></label>
                            <div class="es-field__content">
                                <input type="text" name="es_custom_value[]" value="<?php echo $item[ key( $item ) ]; ?>"/>
                                <input type="hidden" name="es_custom_key[]" value="<?php echo key( $item ); ?>"/>
                                <a href="#" class="js-es-remove-custom"><span class="es-sprite es-sprite-close"></span></a>
                            </div>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="es-property-custom-wrap">
				<input type="text" placeholder="<?php _e( 'text/number', 'es-plugin' ); ?>" name="es-custom-field">
				<a href="#" class="es-button-add-item es-button-add-custom js-es-add-custom"><?php _e( 'Add new field', 'es-plugin' ); ?></a>
			</div>

            <?php do_action( 'es_admin_after_buyer_profile_fields' ); ?>
		</div>

		<input type="hidden" name="user_id" value="<?php echo ! empty( $user->ID ) ? $user->ID : ''; ?>">

		<?php wp_nonce_field( 'es_save_user', 'es_save_user' ); ?>
	</form>
</div>
