<?php

/**
 * Class Es_User
 *
 * @property $status integer
 * @property $profile_attachment_id integer
 * @property $name string
 * @property $tel string
 */
abstract class Es_User extends Es_Entity {

	/**
	 * User active status.
	 */
	const STATUS_ACTIVE = 1;
	const STATUS_DISABLED = 0;

	/**
	 * @return array|mixed
	 */
	public static function get_fields() {

		return array(
			'status' => array(),
			'name' => array(
				'label' => __( 'Name' , 'es-plugin' ),
			),
			'profile_attachment_id' => array(),
			'tel' => array(
				'label' => __( 'Tel' , 'es-plugin' ),
				'rets_support' => true,
			),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get_entity()
	{
		return new WP_User( $this->getID() );
	}

	/**
	 * @inheritdoc
	 */
	public function get_field_value( $field, $single = true )
	{
		return get_user_meta( $this->getID(), $this->get_entity_prefix() . $field, $single );
	}

	/**
	 * @inheritdoc
	 */
	public function save_field_value( $field, $value )
	{
		$value = is_string( $value ) ? sanitize_text_field( $value ) : $value;

		update_user_meta( $this->getID(), $this->get_entity_prefix() . $field, $value );
	}

	/**
	 * Remove agent field.
	 *
	 * @param $field
	 */
	public function delete_field_value( $field ) {
		delete_user_meta( $this->getID(), $this->get_entity_prefix() . $field );
	}

	/**
	 * @param $data
	 *
	 * @return int|WP_Error|static
	 */
	public function register( $data ) {

		if ( ! empty( $data['username'] ) && ! empty( $data['email'] ) ) {
			$entity_name = $this->get_entity_name();

			$data = apply_filters( 'es_' . $entity_name . '_register_data', $data );

			$role = static::get_role_name();

			add_action( 'register_new_user', function( $user_id ) {
				$user = get_user_by( 'ID', $user_id );
				$user->set_role( static::get_role_name() );
			}, 8, 1 );

			// Register new user.
			$user_id = register_new_user( $data['username'], $data['email'] );

			if ( ! is_wp_error( $user_id ) ) {
				// Set agent ID.
				$this->_id = $user_id;
				/** @var WP_User $user */
				$user = $this->get_entity();
				// Set User role as Agent.
				$user->set_role( $role );

				// Get all agent fields.
				$fields = static::get_fields();

				if ( ! empty( $_FILES['agent_photo'] ) ) {
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
					}

					if ( $attachment_id = media_handle_sideload( $_FILES['agent_photo'], -1 ) ) {
						if ( ! $attachment_id instanceof WP_Error ) {
							$data['profile_attachment_id'] = $attachment_id;
						}
					}
				}

				// Save custom agent fields.
				foreach ( $data as $field => $value ) {
					if ( isset( $fields[ $field ] ) ) {
						$this->save_field_value( $field, $value );
					}
				}

				// Update user description and URL.
				if ( ! empty( $update ) ) {
					$update['ID'] = $this->_id;
					$update = apply_filters( 'es_' . $entity_name . '_data_update', $update );
					wp_update_user( $update );
				}

				/**
				 * Agent after register action.
				 *
				 * @param Es_User $this
				 */
				do_action( 'es_' . $entity_name . '_after_register', $this );

			} else {
				return $user_id;
			}
		} else {
			$error = new WP_Error();
			$error->add( 'error', __( 'Email or username is empty.', 'es-plugin' ) );

			return $error;
		}

		return $this;
	}

	/**
	 * Return user full name.
	 *
	 * @return null|string
	 */
	public function get_full_name() {

		if ( ! empty( $this->name ) ) {
			return $this->name;
		}

		$entity = $this->get_entity();
		if ( $entity->first_name ) {
			$name[] = $entity->first_name;
		}
		if ( $entity->last_name ) {
			$name[] = $entity->last_name;
		}
		return ! empty( $name ) ? implode( ' ', $name ) : null;
	}

	/**
	 * Change user status (active / disabled).
	 *
	 * @param $status
	 */
	public function change_status( $status )
	{
		$old_status = $this->status;
		$this->save_field_value( 'status', $status );
		do_action( 'es_' . $this->get_entity_name() . '_change_status', $this, $status, $old_status );
	}

	/**
	 * @return null
	 */
	public static function get_role_name() { return null; }

	/**
	 * Return custom fields data.
	 *
	 * @return mixed
	 */
	public function get_custom_data()
	{
		return get_user_meta( $this->_id, 'es_custom_data' );
	}

	/**
	 * Return agent profile image URL.
	 *
	 * @param string $size
	 * @return null
	 */
	public function get_image_url( $size = 'thumbnail' )
	{
		$url = null;

		if ( $this->profile_attachment_id ) {
			$image = wp_get_attachment_image_src( $this->profile_attachment_id, $size, true );
			$url = ! empty( $image[0] ) ? $image[0] : null;
		}

		return apply_filters( 'es_' . $this->get_entity_name() . '_profile_image_url', $url, $this->profile_attachment_id, $this );
	}
}
