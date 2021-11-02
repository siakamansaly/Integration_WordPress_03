<?php $logger = new Es_Messenger( 'es_user_register' ); global $es_settings;

$terms_of_use = __( 'Terms of Use' ,'es-plugin' );
$privacy_policy = __( 'Privacy Policy' ,'es-plugin' );

$terms_of_use = $es_settings->term_of_use_page_id && get_permalink( $es_settings->term_of_use_page_id ) ?
	"<a href='" . get_permalink( $es_settings->term_of_use_page_id ) . "' target='_blank'>{$terms_of_use}</a>" : $terms_of_use;

$privacy_policy = $es_settings->privacy_policy_page_id && get_permalink( $es_settings->privacy_policy_page_id ) ?
	"<a href='" . get_permalink( $es_settings->privacy_policy_page_id ) . "' target='_blank'>{$privacy_policy}</a>" : $privacy_policy; ?>

<div class="es-agent-register__wrap">
	<h2><?php _e( 'Register', 'es-plugin' ); ?></h2>

	<?php if ( ! get_current_user_id() ) : ?>

		<?php $logger->render_messages(); ?>

		<form action="" method="post" enctype="multipart/form-data">

			<div class="es-field">
				<div class="es-field__label">
					<label for="es-agent-name"><?php _e( 'Name', 'es-plugin' ); ?>:</label>
				</div>
				<div class="es-field__content">
					<input type="text" value="<?php echo $data['name']; ?>" id="es-agent-name" name="es_user[name]"/>
				</div>
			</div>

			<div class="es-field">
				<div class="es-field__label">
					<label for="es-agent-username"><?php _e( 'User name', 'es-plugin' ); ?>:</label>
				</div>
				<div class="es-field__content">
					<input type="text" id="es-agent-username" value="<?php echo $data['username']; ?>" data-required name="es_user[username]"/>
				</div>
			</div>

			<div class="es-field">
				<div class="es-field__label">
					<label for="es-agent-email"><?php _e( 'Email', 'es-plugin' ); ?>:</label>
				</div>
				<div class="es-field__content">
					<input type="email" id="es-agent-email" value="<?php echo $data['email']; ?>" data-required name="es_user[email]"/>
				</div>
			</div>

			<div class="es-field es-field__photo">
				<div class="es-field__label">
					<label for="es-agent-about"><?php _e( 'Photo/Logo', 'es-plugin' ); ?>:</label>
				</div>
				<div class="es-field__content">
					<div class="js-es-image"></div>

					<a href="#" class="es-upload-link js-trigger-upload" data-selector="#es-file-input">
						<i class="fa fa-upload" aria-hidden="true"></i>
						<span><?php _e( 'Upload photo', 'es-plugin' ); ?></span>
					</a>
					<input type="file" name="agent_photo" id="es-file-input" class="js-es-input-image"/>
				</div>
			</div>

			<?php if ( $es_settings->privacy_policy_checkbox == 'required' ) : ?>
				<div class="es-field">
					<div class="es-field__label"></div>
					<div class="es-field__content1">
						<label>
							<input type="checkbox" name="agree_terms" value="1" required/>
							<?php printf( __( 'I agree to the %s and %s', 'es-plugin' ), $terms_of_use, $privacy_policy ); ?>
						</label>
					</div>
				</div>
			<?php endif; ?>

			<div class="es-field">
				<div class="es-field__label"></div>
				<div class="es-field__content">
					<?php do_action( 'es_recaptcha', 'register' ); ?>
				</div>
			</div>

			<div class="es-field">
				<div class="es-field__label"></div>
				<div class="es-field__content">
					<input type="submit" class="es-btn es-btn-orange" value="<?php _e( 'Register', 'es-plugin' ); ?>">
				</div>
			</div>

			<input type="hidden" name="_redirect" value="<?php the_permalink(); ?>"/>

			<?php wp_nonce_field( 'es_user_registration', 'es_user_registration' ); ?>
		</form>
	<?php else : ?>
		<div class="es-agent-register__logged">
			<?php _e( 'You are already logged in.', 'es-plugin' ); ?><br>
			<a href="<?php echo wp_logout_url( get_the_permalink() ); ?>" class="es-agent__logout es-btn es-btn-orange-bordered"><?php _e( 'Logout', 'es-plugin' ); ?></a>
		</div>
	<?php endif; ?>
</div>
