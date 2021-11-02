<?php

/**
 * @var $this Es_Request_Widget
 * @var $instance array
 */

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Learn more about this property', 'es-plugin' );

$message = ! empty( $instance['message'] ) ? $instance['message'] :
    __( 'Hi, I`m interested in the property. Please send me more information about it. Thank you!', 'es-plugin' );

$send_to = ! empty( $instance['send_to'] ) ? $instance['send_to'] : $this::SEND_ADMIN;
$disable_tel = ! empty( $instance['disable_tel'] ) ? $instance['disable_tel'] : false;
$disable_name = ! empty( $instance['disable_name'] ) ? $instance['disable_name'] : false;
$another_emails = ! empty( $instance['custom_email'] ) ? $instance['custom_email'] : false;
$from_name = ! empty( $instance['from_name'] ) ? $instance['from_name'] : false;
$from_email = ! empty( $instance['from_email'] ) ? $instance['from_email'] : false;

$subject = ! empty( $instance['subject'] ) ? $instance['subject'] : __( 'Estatik Request Info from', 'es-plugin' ); ?>

<div class="es-widget-wrap">
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Request title', 'es-plugin' ); ?>:</label>
        <input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'message' ) ); ?>"><?php _e( 'Request Message', 'es-plugin' ); ?>:</label>
        <textarea class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'message' ) ); ?>"><?php echo $message; ?></textarea>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'disable_name' ) ); ?>"><?php _e( 'Disable Name field', 'es-plugin' ); ?>:</label>
        <input type="checkbox" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'disable_name' ) ); ?>" value="1" <?php checked( $disable_name, 1 ); ?>/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'disable_tel' ) ); ?>"><?php _e( 'Disable Phone number field', 'es-plugin' ); ?>:</label>
        <input type="checkbox" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'disable_tel' ) ); ?>" value="1" <?php checked( $disable_tel, 1 ); ?>/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'from_name' ) ); ?>"><?php _e( 'From name', 'es-plugin' ); ?>:</label>
        <input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'from_name' ) ); ?>" value="<?php echo esc_attr( $from_name ); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'from_email' ) ); ?>"><?php _e( 'From email', 'es-plugin' ); ?>:</label>
        <input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'from_email' ) ); ?>" value="<?php echo esc_attr( $from_email ); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'send_to' ) ); ?>"><?php _e( 'Send Message To', 'es-plugin' ); ?>:</label>
        <select class="widefat js-es-send-to" name="<?php echo esc_attr( $this->get_field_name( 'send_to' ) ); ?>">
            <?php if ( $list = $this::get_send_to_list() ) : ?>
                <?php foreach ( $list as $key => $label ) : ?>
                    <option value="<?php echo $key; ?>" <?php selected( $key, $send_to ); ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </p>

    <p class="js-es-send-to-field <?php echo $send_to != -1 ? 'hidden' : ''; ?>">
        <label for="<?php echo esc_attr( $this->get_field_id( 'custom_email' ) ); ?>"><?php _e( 'Another email', 'es-plugin' ); ?>:</label>
        <input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'custom_email' ) ); ?>" value="<?php echo esc_attr( $another_emails ); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'subject' ) ); ?>"><?php _e( 'Email subject', 'es-plugin' ); ?>:</label>
        <input type="text" name="<?php echo $this->get_field_name( 'subject' ); ?>" value="<?php echo esc_attr( $subject ); ?>">
    </p>
</div>
