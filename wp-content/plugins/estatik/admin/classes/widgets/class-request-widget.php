<?php

/**
 * Class Es_Request_Widget
 */
class Es_Request_Widget extends Es_Widget
{
    /**
     * @var int
     */
    const SEND_ADMIN = 1;
    const SEND_OTHER = -1;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct( 'es_request_widget' , __( 'Estatik Request Form', 'es-plugin' ) );
    }

    /**
     * Return list of "send_to" selectbox field.
     *
     * @return mixed
     */
    public static function get_send_to_list()
    {
        return apply_filters( 'es_request_widget_get_send_to_list', array(
            self::SEND_ADMIN       => __( 'Admin', 'es-plugin' ),
            self::SEND_OTHER       => __( 'Other email', 'es-plugin' ),
        ) );
    }

    /**
     * Overridden render checker.
     *
     * @param $instance
     * @return bool
     */
    public static function can_render( $instance )
    {
        return apply_filters( 'es_request_widget_can_render', is_singular( 'properties' ), $instance );
    }

    /**
     * Function for register widget.
     *
     * @return void
     */
    public static function register()
    {
        register_widget( 'Es_Request_Widget' );
    }

    /**
     * @return string
     */
    public static function wp_mail_from() {
        $email = sanitize_email( filter_input( INPUT_POST, 'from_email' ) );
        return $email ? $email : sanitize_email( filter_input( INPUT_POST, 'email' ) );
    }

    /**
     * @return string
     */
    public static function wp_mail_from_name() {
        $name = sanitize_text_field( filter_input( INPUT_POST, 'from_name' ) );
        return $name ? $name : sanitize_text_field( filter_input( INPUT_POST, 'name' ) );
    }

    /**
     * Submit request widget handler.
     *
     * @return void
     */
    public static function submit_form()
    {
        $response = array();
        $nonce = 'es_request_send';

        if ( check_ajax_referer( $nonce, $nonce ) ) {
        	$post_id = sanitize_key( filter_input( INPUT_POST, 'post_id' ) );
            $post = ! empty( $post_id ) ? get_post( $post_id ) : null;
            $check = es_verify_recaptcha();

            if ( ! $check ) {
                $response = array( 'status' => 'error', 'message' => __( 'Invalid captcha. Please refresh the page.', 'es-plugin' ) );
            }

            if ( ! empty( $post->post_type ) && $post->post_type == Es_Property::get_post_type_name() && $check ) {
                if ( $emails = static::get_emails( sanitize_text_field( $_POST['send_to'] ), $post ) ) {
                    $property = es_get_property( $post->ID );
                    $email = sanitize_email( $_POST['email'] );
                    $name = sanitize_text_field( filter_input( INPUT_POST, 'name' ) );
                    $tel = sanitize_text_field( filter_input( INPUT_POST, 'tel' ) );
                    $message = sanitize_textarea_field( filter_input( INPUT_POST, 'message' ) );

                    $msg = ! empty( $name ) ?  "<p><b>" . __( 'Name', 'es-plugin' ) . "</b>: " . $name . "</p>" : null;
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Email', 'es-plugin' ) . "</b>: " . $email . "\n";
                    $msg .= ! empty( $tel ) ?  "<p><b>" . __( 'Phone', 'es-plugin' ) . "</b>: " . $tel . "</p>" : null;
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property ID', 'es-plugin' ) . "</b>: " . $post->ID . "\n";
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property Link', 'es-plugin' ) . "</b>: " . get_permalink( $post->ID ) . "</p>";
                    $msg .= "<p style='font-weight: 300;'><b>" . __( 'Property Address', 'es-plugin' ) . "</b>: " . $property->address . "</p>";
                    $msg .= "<p style='font-weight: 300; line-height: 1.6;'><b>" . __( 'Request', 'es-plugin' ) . "</b>: " . $message . "\n";

                    $subject = apply_filters( 'es_request_form_email_subject', sanitize_title( $_POST['subject'] ) );
                    $message = apply_filters( 'es_request_form_email_message', $msg );

	                $message = es_email_content( 'emails/property-request.php', array(
	                	'message' => $message,
		                'title' => sprintf( __( 'Request about property #%s', 'es-plugin' ), $post->ID ),
	                ) );

                    add_filter( 'wp_mail_from', array( 'Es_Request_Widget', 'wp_mail_from' ) );
                    add_filter( 'wp_mail_from_name', array( 'Es_Request_Widget', 'wp_mail_from_name' ) );

                    if ( wp_mail( $emails, $subject, $message ) ) {
                        $response = array( 'status' => 'success', 'message' => __( 'Thank you for your message! We will contact you as soon as we can.', 'es-plugin' ) );
                    } else {
                        $response = array( 'status' => 'error', 'message' => __( 'Your message wasn\'t sent. Please, contact support.', 'es-plugin' ) );
                    }
                }
            } else {
                $response = ! empty( $response ) ? $response : array( 'status' => 'error', 'message' => __( 'Incorrect post.', 'es-plugin' ) );
            }

            $response = apply_filters( 'es_request_form_response', $response );

	        $template = es_locate_template( 'partials/request-form-response.php', 'front', 'es_request_form_response_template_path' );

            ob_start();
            include ( $template );

            wp_die( json_encode( ob_get_clean() ) );
        }
    }

    /**
     * Return emails for sending.
     *
     * @param $type
     * @param $post
     * @return mixed
     */
    protected static function get_emails( $type, WP_Post $post )
    {
        $emails = array();

        if ( static::SEND_OTHER == $type && ( $another_emails = filter_input( INPUT_POST, 'send_to_emails' ) ) ) {
	        $another_emails = explode( ',', $another_emails );

            if ( $another_emails ) {
                foreach ( $another_emails as $email ) {
			        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				        $emails[] = $email;
			        }
		        }
	        }
        }

        if ( static::SEND_ADMIN == $type ) {
	        $emails = get_users( array(
	        	'role' => 'administrator',
		        'fields' => array( 'user_email' )
	        ) );

	        $emails = $emails ? wp_list_pluck( $emails, 'user_email' ) : array();
	        $emails[] = get_option( 'admin_email' );
        }

        $emails = $emails ? array_unique( $emails ) : array();

        return apply_filters( 'es_request_form_get_emails', $emails, $type, $post );
    }

    /**
     * @inheritdoc
     */
    protected function get_widget_template_path()
    {
	    return es_locate_template( 'widgets/es-request-widget.php', 'admin' );
    }

    /**
     * @return string
     */
    protected function get_widget_form_template_path()
    {
	    return es_locate_template( 'widgets/es-request-widget-form.php', 'admin' );
    }
}

add_action( 'widgets_init', array( 'Es_Request_Widget', 'register' ) );
add_action( 'wp_ajax_es_request_send', array( 'Es_Request_Widget', 'submit_form' ) );
add_action( 'wp_ajax_nopriv_es_request_send', array( 'Es_Request_Widget', 'submit_form' ) );
