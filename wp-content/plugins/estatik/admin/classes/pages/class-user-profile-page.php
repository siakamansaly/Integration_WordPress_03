<?php

/**
 * Class Es_Agent_Profile
 */
class Es_User_Profile
{
	/**
	 * Initialize agent profile page.
	 *
	 * @static
	 * @return void
	 */
	public static function init()
	{
		$_ = new self();
		$_->actions();
	}

	/**
	 * Initialize scripts and styles of agent profile page.
	 *
	 * @return void
	 */
	public function actions()
	{
		add_action( 'init', array( $this, 'save' ) );
		add_action( 'init', array( $this, 'remove_user' ) );
	}

	/**
	 * Remove user action.
	 *
	 * @return void
	 */
	public function remove_user()
	{
		$delete_user_nonce = sanitize_key( filter_input( INPUT_GET, 'es-delete-user' ) );

		if ( $delete_user_nonce && wp_verify_nonce( $delete_user_nonce, 'es-delete-user' ) && ! empty( $_REQUEST['delete'] ) ) {
			$id = intval( $_REQUEST['delete'] );

			if ( ! function_exists( 'wp_delete_user' ) ) {
				// Include helper wordpress functions for saving agent.
				include ( ABSPATH . 'wp-admin/includes/user.php' );
			}

			wp_delete_user( $id );

			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}
	}

	/**
	 * Render agent profile page.
	 *
	 * @return void
	 */
	public static function render()
	{
		$page = sanitize_key( filter_input( INPUT_GET, 'page' ) );
		$data = array();

		if ( ! empty( $_POST['es_user'] ) ) {
            $data = array_merge( $_POST['es_user'], $_POST );
            $data = es_clean( $data );
        }

		$data = wp_parse_args( $data, array(
		    'name' => '',
		    'user_login' => '',
		    'email' => '',
		    'tel' => '',
		    'description' => '',
        ) );

		if ( $page == 'es_buyer' ) {
			$user_entity = es_get_buyer();
			$title = __( 'Buyer Info', 'es-plugin' );
			$template = es_locate_template( 'buyers/profile.php', 'admin', 'es_buyer_profile_template_path' );
		}

		$id = sanitize_key( filter_input( INPUT_GET, 'id' ) );

		if ( $id ) {
			$user_entity = es_get_user_entity( $id );
		}

		if ( $user_entity ) {
			$user = $user_entity->get_entity();

			include $template;
		}
	}

	/**
	 * Save Agent info.
	 *
	 * @return void
	 */
	public function save()
	{
		$save_user_nonce = sanitize_key( filter_input( INPUT_POST, 'es_save_user' ) );

		if ( $save_user_nonce && wp_verify_nonce( $save_user_nonce, 'es_save_user' ) ) {

			// Include helper wordpress functions for saving agent.
			if ( ! function_exists( 'edit_user' ) ) {
				include ( ABSPATH . 'wp-admin/includes/user.php' );
			}

			// Set confirmed password variable.
			$_POST['pass2'] = sanitize_text_field( $_POST['pass1'] );
			$_POST['nickname'] = sanitize_text_field( $_POST['user_login'] );

			// Get user ID.
			$id = sanitize_key( filter_input( INPUT_POST, 'user_id' ) );

			do_action( 'es_before_save_user', $id );

			// Save user.
			$id = edit_user( $id );

			if ( ! empty( $id ) && ! $id instanceof WP_Error ) {
				$user = get_user_by( 'ID', $id );
				$user->set_role( sanitize_text_field( $_POST['role'] ) );
				$agent = es_get_user_entity( $id );
				$post_data = filter_input( INPUT_POST, 'es_user', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				$fields = $agent::get_fields();

				foreach ( $fields as $key => $field ) {
					$value = isset( $post_data[$key] ) ? $post_data[$key] : $agent->{$key};
					$agent->save_field_value( $key, $value );
				}

				$agent->change_status( $agent::STATUS_ACTIVE );

				$custom_data_key = filter_input( INPUT_POST, 'es_custom_key', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				$custom_data_value = filter_input( INPUT_POST, 'es_custom_value', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

				delete_user_meta( $id, 'es_custom_data' );

				if ( ! empty( $custom_data_key ) ) {
					foreach ( $custom_data_key as $index => $key ) {
						$value = ! empty( $custom_data_value[ $index ] ) ? $custom_data_value[ $index ] : '';
						$value = sanitize_text_field( $value );

						if ( ! empty( $key ) && ! empty( $value ) ) {
							add_user_meta( $id, 'es_custom_data', array( $key => $value ), false );
						}
					}
				}

				if ( empty( $post_data['profile_attachment_id'] ) ) {
					$agent->delete_field_value( 'profile_attachment_id' );
				}

				$msg = new Es_Messenger( 'es_message' );

				$msg->set_message( __( 'User successfully saved', 'es-plugin' ), 'success' );

				do_action( 'es_after_save_user', $id );

				$url = add_query_arg( 'id', $id, $_SERVER['HTTP_REFERER'] );
				wp_redirect( apply_filters( 'es_redirect_after_save_user', $url, $id ) );

				exit;

			} else {
				if  ( ! empty( $id->errors ) ) {
					$logger = new Es_Messenger( 'es_message' );

					foreach ( $id->errors as $error_list ) {
						if ( ! $error_list ) continue;

						foreach ( $error_list as $error ) {
							$logger->set_message( $error, 'error' );
						}
					}
				} else {
					$msg = new Es_Messenger( 'es_message' );
					$msg->set_message( __( 'User doesn\'t save', 'es-plugin' ), 'error' );
				}
			}
		}
	}
}
