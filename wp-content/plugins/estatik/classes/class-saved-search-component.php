<?php

/**
 * Class Es_Saved_Search_Component.
 */
class Es_Saved_Search_Component extends Es_Object {

	/**
	 * Define component actions.
	 *
	 * @return void
	 */
	public function actions() {

		add_action( 'wp_ajax_es_save_search', array( 'Es_Saved_Search_Component', 'ajax_save_search' ) );
		add_action( 'wp_ajax_es_change_update_method', array( 'Es_Saved_Search_Component', 'ajax_change_update_method' ) );
		add_action( 'wp_ajax_es_saved_search_change_title', array( 'Es_Saved_Search_Component', 'ajax_change_title' ) );
		add_action( 'init', array( 'Es_Saved_Search_Component', 'delete' ) );
	}

	/**
	 * Save search action.
	 *
	 * @return void
	 */
	public static function ajax_save_search() {

		if ( check_ajax_referer( 'es_save_search_nonce', 'nonce' ) ) {
			if ( ! empty( $_POST['es_search'] ) && is_array( $_POST['es_search'] ) ) {
				$property = es_get_property( null );

				foreach ( $_POST['es_search'] as $field => $value ) {
					$field_info = $property::get_field_info( sanitize_key( $field ) );

					if ( ! empty( $field_info['skip_search'] ) || empty( $value ) ) continue;
					if ( is_array( $value ) && isset( $value['min'] ) && empty( $value['min'] ) && empty( $value['max'] ) ) continue;

					if ( is_array( $value ) ) {
						$value = array_map( 'sanitize_text_field', $value );
					} else {
						$value = sanitize_text_field( $value );
					}

					$fields[ $field ] = $value;
				}

				if ( ! empty( $fields ) ) {

					$post_id = wp_insert_post( array(
						'post_type' => Es_Saved_Search::get_post_type_name(),
						'post_status' => 'private',
						'post_author' => get_current_user_id(),
					), true );

					if ( $post_id && ! $post_id instanceof WP_Error ) {
						$search = es_get_saved_search( $post_id );

						$search->save_fields( $fields );

						$search->save_field_value( 'fields', array_keys( $fields ) );
						$search->save_field_value( 'query', $fields );

						$status = 'success';
					} else {
						$status = 'error';
						$message = $post_id->get_error_message();
					}
				} else {
					$status = 'error';
					$message= __( 'Choose search fields', 'es-plugin' );
				}
			} else {
				$status = 'error';
				$message = __( 'Choose search fields', 'es-plugin' );
			}
		} else {
			$status = 'error';
			$message = __( 'Invalid security nonce. Please, refresh the page.', 'es-plugin' );
		}

		$response = array( 'status' => $status );

		if ( ! empty( $message ) ) {
			$response['message'] = sprintf(
				'<p class="es-search__message es-search__message-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> %s</p>',
				$message
			);
		}

		wp_die( json_encode( $response ) );
	}

	/**
	 * Change update method for saved search.
	 *
	 * @return void
	 */
	public function ajax_change_update_method() {

		$response = array( 'status' => 'error', 'message' => __( 'Incorrect security nonce, please refresh the page.', 'es-plugin' ) );

		if ( check_ajax_referer( 'save_search_change_method_nonce', 'nonce' ) ) {

			if ( ! empty( $_POST['id'] ) && ! empty( $_POST['update_method'] ) ) {
				$entity = es_get_saved_search( intval( $_POST['id'] ) );
				$methods = $entity::get_periods();

				$update_method = sanitize_key( $_POST['update_method'] );

				if ( ! empty( $methods[ $update_method ] ) ) {
					$entity->change_update_method( $update_method );
					$response = array( 'status' => 'success', 'message' => __( 'The update method successfully changed.', 'es-plugin' ) );
				} else {
					$response = array( 'status' => 'error', 'message' => __( 'Incorrect update method', 'es-plugin' ) );
				}

			} else {
				$response = array( 'status' => 'error', 'message' => __( 'Incorrect entity', 'es-plugin' ) );
			}
		}

		wp_die( json_encode( $response ) );
	}

	public function ajax_change_title() {

		$response = array( 'status' => 'error', 'message' => __( 'Incorrect security nonce, please refresh the page.', 'es-plugin' ) );

		if ( check_ajax_referer( 'es_saved_search_change_title', 'es_saved_search_change_title' ) ) {
			if ( ! empty( $_POST['title'] ) && ! empty( $_POST['id'] ) ) {
				$post = get_post( intval( $_POST['id'] ) );

				if ( get_current_user_id() == $post->post_author && $post->post_type == Es_Saved_Search::get_post_type_name() ) {
					$post = wp_update_post( array(
						'post_title' => sanitize_title( $_POST['title'] ),
						'ID' => intval( $_POST['id'] ),
					), true );

					if ( $post && ! $post instanceof WP_Error ) {
						$response = array( 'title' => sanitize_title( $_POST['title'] ), 'status' => 'success', 'message' => __( 'The title successfully changed.', 'es-plugin' ) );
					} else {
						$response = array( 'status' => 'error', 'message' => $post->get_error_message() );
					}
				} else {
					$response = array( 'status' => 'error', 'message' => __( 'Incorrect saved search entity.', 'es-plugin' ) );
				}
			} else {
				$response = array( 'status' => 'error', 'message' => __( 'The title field is required.', 'es-plugin' ) );
			}
		}

		wp_die( json_encode( $response ) );
	}

	/**
	 * Delete saved search entity action.
	 *
	 * @return void
	 */
	public static function delete() {

		$security = sanitize_key( filter_input( INPUT_GET, 'security' ) );

		if ( $security && wp_verify_nonce( $security, 'es_delete_search' ) ) {

			$logger = new Es_Messenger( 'es_profile' );

			if ( ! empty( $_GET['id'] ) ) {
				$id = sanitize_key( $_GET['id'] );
				$entity = es_get_saved_search( $id );
				$uid = get_current_user_id();

				if ( ( ! empty( $entity->get_entity()->post_author ) && $entity->get_entity()->post_author == $uid ) || current_user_can( 'delete_others_posts' ) ) {
					$deleted = wp_delete_post( $entity->getID(), true );

					if ( $deleted && ! $deleted instanceof WP_Error ) {
						$logger->set_message( sprintf( __( 'The saved search %s successfully removed.', 'es-plugin' ), $entity->getID() ), 'success' );
						do_action( 'es_after_delete_saved_search', $entity->getID() );
					} else {
						$logger->set_message( $deleted->get_error_message(), 'error' );
					}
				} else {
					$logger->set_message( __( 'You have no permissions for remove this search.', 'es-plugin' ), 'error' );
				}
			} else {
				$logger->set_message( __( 'Incorrect entity', 'es-plugin' ), 'error' );
			}

			wp_safe_redirect( $_SERVER['HTTP_REFERER'] ); die;
		}
	}
}

Es_Saved_Search_Component::init();
