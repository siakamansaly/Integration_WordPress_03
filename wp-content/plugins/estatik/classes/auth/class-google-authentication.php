<?php

/**
 * Class Es_Google_Authentication
 */
class Es_Google_Authentication extends Es_Authentication {

    /**
     * Es_Facebook_Authentication constructor.
     *
     * @param array $config
     */
    public function __construct( $config = array() ) {
        global $es_settings;
        $config = wp_parse_args( $config, array(
            'client_id' => $es_settings->google_client_key,
            'client_secret' => $es_settings->google_client_secret,
            'redirect_uri' => '',
            'context' => '',
            'error' => '',
            'error_description' => '',
        ) );
        parent::__construct( $config );
    }

    /**
     * Return google auth login url.
     *
     * @return string|void
     */
    public function create_auth_url() {
        return add_query_arg( array(
            'redirect_uri' => $this->get_redirect_url(),
            'response_type' => 'code',
            'client_id' => $this->_config['client_id'],
            'access_type' => 'online',
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
        ), 'https://accounts.google.com/o/oauth2/v2/auth' );
    }

    /**
     * @return bool
     */
    public function is_valid() {
        if ( ! empty( $this->_config['error'] ) ) {
            return false;
        }
        return ! empty( $this->_config['client_id'] ) && ! empty( $this->_config['client_secret'] );
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
            'code'          => $this->_config['code'],
            'grant_type'    => 'authorization_code',
        );

        $token_response = wp_safe_remote_post( 'https://www.googleapis.com/oauth2/v4/token', array(
            'method'      => 'POST',
            'body'        => $params,
        ) );

        $token_response = json_decode( wp_remote_retrieve_body( $token_response ) );

        if ( ! empty( $token_response->error_description ) ) {
            throw new Exception( $token_response->error_description );
        }

        return $token_response;
    }

    /**
     * @return string
     */
    public function get_network_name() {
        return 'google';
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
            $user_response = wp_safe_remote_get( 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,picture', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token->access_token,
                ),
            ) );
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
        $g_user = $this->get_user();

        if ( ! empty( $g_user->email ) ) {
            if ( ! empty( $g_user->name ) ) {
                $name = explode( ' ', $g_user->name );

                if ( ! empty( $name[0] ) ) {
                    $g_user->first_name = $name[0];
                }

                if ( ! empty( $name[1] ) ) {
                    $g_user->last_name = $name[1];
                }
            }

            $role = apply_filters( 'es_social_register_default_role', 'es_buyer', $this );

            $user_data = array(
                'user_login'  =>  $g_user->email,
                'user_pass'   =>  wp_generate_password(),
                'user_email' => $g_user->email,
                'first_name' => $g_user->first_name,
                'last_name' => $g_user->last_name,
                'role' => $role
            );

            $user_id = wp_insert_user( $user_data );

            if ( ! is_wp_error( $user_id ) ) {
                update_user_meta( $user_id, 'auth_google', 1 );
                $entity = es_get_user_entity( $user_id, $role );

                if ( $entity ) {
                    $entity->save_field_value( 'name', $g_user->first_name . ' ' . $g_user->last_name );
                    $entity->save_field_value( 'status', Es_User::STATUS_ACTIVE );
                }

	            do_action( 'register_new_user', $user_id );
            } else {
	            throw new Exception( $user_id->get_error_message() );
            }
        } else {
            throw new Exception( __( 'Google user email is empty', 'es-plugin' ) );
        }

        return $user_id;
    }
}
