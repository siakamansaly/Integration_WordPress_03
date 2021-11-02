<?php

/**
 * @var $args array
 * @var $instance array
 */

global $post, $es_settings;



$terms_of_use = __( 'Terms of Use' ,'es-plugin' );
$privacy_policy = __( 'Privacy Policy' ,'es-plugin' );

$terms_of_use = $es_settings->term_of_use_page_id && get_permalink( $es_settings->term_of_use_page_id ) ?
    "<a href='" . get_permalink( $es_settings->term_of_use_page_id ) . "' target='_blank'>{$terms_of_use}</a>" : $terms_of_use;

$privacy_policy = $es_settings->privacy_policy_page_id && get_permalink( $es_settings->privacy_policy_page_id ) ?
    "<a href='" . get_permalink( $es_settings->privacy_policy_page_id ) . "' target='_blank'>{$privacy_policy}</a>" : $privacy_policy;

$post_id = ! empty( $post ) ? $post->ID : null;

$message = ! empty( $instance['message'] ) ? $instance['message'] : null;
$send_to = ! empty( $instance['send_to'] ) ? $instance['send_to'] : $this::SEND_ADMIN;
$subject = ! empty( $instance['subject'] ) ? $instance['subject'] : __( 'Estatik Request Info from', 'es-plugin' );
$disable_tel = ! empty( $instance['disable_tel'] ) ? $instance['disable_tel'] : false;
$disable_name = ! empty( $instance['disable_name'] ) ? $instance['disable_name'] : false;
$send_to_emails = ! empty( $instance['custom_email'] ) ? $instance['custom_email'] : false;
$from_name = ! empty( $instance['from_name'] ) ? $instance['from_name'] : false;
$from_email = ! empty( $instance['from_email'] ) ? $instance['from_email'] : false;

if ( ! empty( $args['before_widget'] ) ) {
    echo $args['before_widget'];
} ?>

    <div class="es-request-widget-wrap">

        <form action="" method="POST">

            <?php if ( $instance['title'] ) : ?>
                <h3><?php echo $instance['title']; ?></h3>
            <?php endif; ?>

            <?php if ( ! $disable_name ) : ?>
                <label>
                    <input type="text" name="name" placeholder="<?php _e( 'Your name', 'es-plugin' ); ?>" required>
                </label>
            <?php endif; ?>

            <label>
                <input type="email" name="email" placeholder="<?php _e( 'Your email', 'es-plugin' ); ?>" required>
            </label>

            <?php if ( ! $disable_tel ) : ?>
                <label>
                    <input type="tel" name="tel" placeholder="<?php _e( 'Phone number', 'es-plugin' ); ?>" required>
                </label>
            <?php endif; ?>

            <label>
                <textarea name="message" required><?php echo $message; ?></textarea>
            </label>

            <?php if ( $es_settings->privacy_policy_checkbox == 'required' ) : ?>
                <label class="es-privacy-check">
                    <input type="checkbox" name="agree_terms" value="1" required/>
                    <?php printf( __( 'I agree to the %s and %s', 'es-plugin' ), $terms_of_use, $privacy_policy ); ?>
                </label>
            <?php endif; ?>

            <?php wp_nonce_field( 'es_request_send', 'es_request_send' ); ?>

            <div class="es-captcha">
                <?php do_action( 'es_recaptcha', 'contact' ); ?>
            </div>

            <input type="hidden" name="action" value="es_request_send"/>
            <input type="hidden" name="send_to" value="<?php echo esc_attr( $send_to ); ?>"/>
            <?php if ( $send_to_emails ) : ?>
                <input type="hidden" name="send_to_emails" value="<?php echo esc_attr( $send_to_emails ); ?>"/>
            <?php endif; ?>
            <input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>"/>
            <input type="hidden" name="subject" value="<?php echo esc_attr( $subject ); ?>"/>
            <input type="hidden" name="from_name" value="<?php echo esc_attr( $from_name ); ?>"/>
            <input type="hidden" name="from_email" value="<?php echo esc_attr( $from_email ); ?>"/>
            <input type="submit" class="es-button es-button-orange-corner" value="<?php _e( 'Send', 'es-plugin' ); ?>"/>

        </form>

        <div class="es-response-block"></div>

    </div>

<?php if ( ! empty( $args['after_widget'] ) ) {
    echo $args['after_widget'];
}
