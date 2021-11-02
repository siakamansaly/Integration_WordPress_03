<?php

/**
 * Class Es_Authentication
 */
abstract class Es_Authentication {

    protected $_user_data;

    /**
     * @var array
     */
    protected $_config = array();

    /**
     * Es_Authentication constructor.
     *
     * @param array $config
     */
    public function __construct( $config = array() ) {
        $this->_config = $config;

        if ( empty( $this->_config['redirect_uri'] ) ) {
            $this->_config['redirect_uri'] = $this->get_redirect_url();
        }
    }

    /**
     * Return network auth config.
     *
     * @return array
     */
    public function get_config() {
        return $this->_config;
    }

    /**
     * @return string
     */
    public function get_redirect_url() {
        return add_query_arg( array(
            'auth_network' => $this->get_network_name(),
        ), site_url( '/' ) );
    }

    /**
     * Login or register user via social network.
     *
     * @return bool
     * @throws Exception
     */
    public function auth() {
        $email = $this->get_user_email();

        if ( is_email( $email ) ) {
            $user_id = email_exists( $email );

            if ( $user_id ) {
                wp_set_auth_cookie( $user_id, true, is_ssl() );

                return true;
            } else {
                $user_id = $this->register();

                if ( is_wp_error( $user_id ) ) {
                    throw new Exception( $user_id );
                } else {
                    wp_set_auth_cookie( $user_id, true, is_ssl() );

                    return true;
                }
            }
        } else {
            throw new Exception( __( 'Invalid user email', 'es-plugin' ) );
        }
    }

    /**
     * Return user email.
     *
     * @return bool|mixed
     * @throws Exception
     */
    public function get_user_email() {
        $user = $this->get_user();

        return ! empty( $user->email ) ? $user->email : false;
    }

    /**
     * Return login url.
     *
     * @return string
     */
    abstract public function create_auth_url();

    /**
     * @return bool
     */
    abstract public function is_valid();

    /**
     * @return string
     */
    abstract public function get_network_name();

    /**
     * Return social network authenticated user.
     *
     * @return array
     */
    abstract public function get_user();

    /**
     * Register new user.
     *
     * @return mixed
     */
    abstract public function register();
}
