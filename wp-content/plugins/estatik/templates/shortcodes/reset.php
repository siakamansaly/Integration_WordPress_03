<?php

global $es_settings;

$messenger = new Es_Messenger( 'login' );
$key = sanitize_text_field( filter_input( INPUT_GET, '_key' ) );
$login = sanitize_text_field( filter_input( INPUT_GET, '_login' ) );

$redirect = ! empty( $es_settings->login_page_id ) && get_post( $es_settings->login_page_id ) ?
	get_permalink( $es_settings->login_page_id ) : get_the_permalink(); ?>

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="es-login__wrap">
		<h2><?php _e( 'Restore password', 'es-plugin' ); ?></h2>
		<?php $messenger->render_messages(); ?>
		<form action="" method="post">
			<div class="es-field__wrap es-field-icon">
				<label for="user_login">
					<i class="fa fa-lock" aria-hidden="true"></i>
					<?php if ( $key && $login ) : ?>
						<input type="password" required name="pwd" placeholder="<?php _e( 'Enter new password', 'es-plugin' ); ?>">
					<?php else: ?>
						<input type="text" name="user_login" placeholder="<?php _e( 'Username or email address', 'es-plugin' ); ?>">
					<?php endif; ?>
				</label>
			</div>

			<?php if ( $key && $login ) : ?>
				<input type="hidden" name="_login" value="<?php echo esc_attr( $login ); ?>"/>
				<input type="hidden" name="_key" value="<?php echo esc_attr( $key ); ?>"/>
			<?php endif; ?>

			<div class="es-submit__wrap">
				<input type="submit" class="es-btn es-btn-orange" value="<?php _e( 'Get new password', 'es-plugin' ); ?>">
			</div>

			<?php wp_nonce_field( 'es-restore-pwd', 'es-restore-pwd' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

			<?php if ( $key && $login ) : ?>
				<input type="hidden" name="action" value="fill_password"/>
			<?php endif; ?>
		</form>
	</div>
<?php else: ?>
	<div class="es-agent-register__logged">
		<?php _e( 'You are already logged in.', 'es-plugin' ); ?><br>
		<a href="<?php echo wp_logout_url( get_the_permalink() ); ?>" class="es-agent__logout es-btn es-btn-orange-bordered"><?php _e( 'Logout', 'es-plugin' ); ?></a>
	</div>
<?php endif;
