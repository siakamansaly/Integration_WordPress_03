<?php

/**
 * Class Es_Facebook_Authentication
 */
class Es_Facebook_Authentication extends Es_Authentication {

    /**
     * @var string
     */
    protected $_login_url = 'https://www.facebook.com/dialog/oauth';

    /**
     * Es_Facebook_Authentication constructor.
     *
     * @param array $config
     */
    public function __construct( $config = array() ) {
        global $es_settings;
        $config = wp_parse_args( $config, array(
            'client_id' => $es_settings->facebook_app_id,
            'client_secret' => $es_settings->facebook_app_secret,
            'redirect_uri' => '',
            'response_type' => 'code',
            'scope' => 'email',
            'code' => '',
            'error_code' => '',
            'error_message' => '',
            'context' => '',
        ) );
        parent::__construct( $config );
    }

    /**
     * @return string
     */
    public function create_auth_url() {
        return add_query_arg( array(
            'client_id' => $this->_config['client_id'],
            'redirect_uri' => $this->_config['redirect_uri'],
            'response_type' => $this->_config['response_type'],
            'scope' => $this->_config['scope'],
            'auth_nonce' => wp_create_nonce( 'es_auth_action' ),
        ), $this->_login_url );
    }

    /**
     * @param $code
     */
    public function set_code( $code ) {
        $this->_config['code'] = $code;
    }

    /**
     * Generate access token.
     *
     * @return string
     * @throws Exception
     */
    public function get_access_token_response() {
        $this->_user_data = null;

        $params = array(
            'client_id'     => $this->_config['client_id'],
            'redirect_uri'  => $this->_config['redirect_uri'],
            'client_secret' => $this->_config['client_secret'],
            'code'          => $this->_config['code']
        );

        $token_response = wp_safe_remote_get( add_query_arg( $params, 'https://graph.facebook.com/v2.7/oauth/access_token' ) );
        $token_response = json_decode( wp_remote_retrieve_body( $token_response ) );

        if ( ! empty( $token_response->error->message ) ) {
            throw new Exception( $token_response->error->message, $token_response->error->code );
        }

        return $token_response;
    }

    /**
     * Return FB user by access token.
     *
     * @return stdClass|bool
     * @throws Exception
     */
    public function get_user() {
        if ( ! empty( $this->_user_data ) ) {
            return $this->_user_data;
        }

        $token = $this->get_access_token_response();

        if ( isset( $token->access_token ) ) {
            $params = array(
                'access_token' => $token->access_token,
                'fields' => 'id,name,email,picture,link,locale,first_name,last_name',
            );

            $user_response = wp_safe_remote_get('https://graph.facebook.com/v2.7/me' . '?' . urldecode( http_build_query( $params ) ) );
            $this->_user_data = json_decode( wp_remote_retrieve_body( $user_response ) );

            return $this->_user_data;
        }

        return false;
    }

    /**
     * Register new user.
     *
     * @return mixed|void
     * @throws Exception
     */
    public function register() {
        $fb_user = $this->get_user();

        if ( ! empty( $fb_user->email ) ) {
            $role = apply_filters( 'es_social_register_default_role', 'es_buyer', $this );

            $user_data = array(
                'user_login'  =>  $fb_user->email,
                'user_pass'   =>  wp_generate_password(),
                'user_email' => $fb_user->email,
                'first_name' => $fb_user->first_name,
                'last_name' => $fb_user->last_name,
                'role' => $role
            );

            $user_id = wp_insert_user( $user_data );

            if ( ! is_wp_error( $user_id ) ) {
                update_user_meta( $user_id, 'auth_facebook', 1 );

                $entity = es_get_user_entity( $user_id, $role );

                if ( $entity ) {
                    $entity->save_field_value( 'name', $fb_user->first_name . ' ' . $fb_user->last_name );
                    $entity->save_field_value( 'status', Es_User::STATUS_ACTIVE );
                }

	            do_action( 'register_new_user', $user_id );
            } else {
	            throw new Exception( $user_id->get_error_message() );
            }
        } else {
            throw new Exception( __( 'Facebook user email is empty', 'es-plugin' ) );
        }

        return $user_id;
    }

    /**
     * Is valid auth data.
     *
     * @return bool
     * @throws Exception
     */
    public function is_valid() {
        if ( ! empty( $this->_config['error_code'] ) ) {
            throw new Exception( $this->_config['error_message'], $this->_config['error_code'] );
        }
        return ! empty( $this->_config['client_id'] ) && ! empty( $this->_config['client_secret'] );
    }

    /**
     * @return string
     */
    public function get_network_name() {
        return 'facebook';
    }
}
