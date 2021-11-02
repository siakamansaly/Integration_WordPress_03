<?php

if ( ! defined( 'WPINC' ) ) die;


/**
 * Class Es_Fields_Builder_Page
 */
class Es_Fields_Builder_Page extends Es_Object
{
	/**
	 * Add actions for dashboard page.
	 */
	public function actions()
	{
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_scripts' ) );
		add_action( 'init', array( $this , 'action_save_field' ) );
		add_action( 'init', array( $this , 'action_save_section' ) );
		add_action( 'es_before_property_metabox_tab_content', array( $this , 'add_property_tab_info' ), 10, 1 );
		add_action( 'init', array( $this , 'action_remove_field' ) );
		add_action( 'init', array( $this , 'action_restore_field' ) );
		add_action( 'init', array( $this , 'action_remove_section' ) );
		add_action( 'wp_ajax_es_fbuilder_load_field_options', array( $this , 'action_load_field_options' ) );
		add_action( 'wp_ajax_es_fbuilder_change_fields_order', array( $this , 'action_change_fields_order' ) );
		add_action( 'wp_ajax_es_fbuilder_change_sections_order', array( $this , 'action_change_sections_order' ) );
	}

	/**
	 * Add info text for basic facts property tab.
	 *
	 * @param $id
	 */
	public function add_property_tab_info( $id ) {
		if ( 'es-info' == $id ) {
			echo '<div class="es-fb-info">' . sprintf( wp_kses( __( 'If you lack some fields, please go to <a href="%s">Fields Builder</a> and add your own custom fields.', 'es-plugin' ),
					array(  'a' => array( 'href' => array() ) ) ), esc_url( es_admin_fields_builder_uri() ) ) . '</div>';
		}
	}

	/**
	 * Enqueue styles for dashboard page.
	 *
	 * @return void
	 */
	public function enqueue_styles()
	{
		$custom = 'admin/assets/css/custom/';

		wp_register_style( 'es-fields-builder-style', ES_PLUGIN_URL . $custom . 'fbuilder.css' );
		wp_enqueue_style( 'es-fields-builder-style' );
	}

	/**
	 * Enqueue scripts for the page.
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{
		$custom = 'admin/assets/js/custom/';

		wp_register_script( 'es-fields-builder-script', ES_PLUGIN_URL . $custom . 'fbuilder.js', array(
			'jquery', 'es-cloneya-script', 'es-admin-script',
		) );
		wp_enqueue_script( 'es-fields-builder-script' );
	}

	/**
	 * @inheritdoc
	 */
	public static function render()
	{
		$path = self::get_template_path( 'main' );

		if ( file_exists( $path ) ) {
			load_template( $path );
		}
	}

	/**
	 * @return mixed
	 */
	public static function get_tabs()
	{
		return apply_filters( 'es_fbuilder_get_tabs', array(
			'es-property' => array(
				'label' => __( 'Listing fields', 'es-plugin' ),
				'template' => self::get_template_path( 'tabs/entity-fields-tab' ),
				'entity' => 'property',
			),
			'es-section' => array(
				'label' => __( 'Listing sections', 'es-plugin' ),
				'template' => self::get_template_path( 'tabs/entity-sections-tab' ),
				'entity' => 'property',
			),
		) );
	}

	/**
	 * Return template path by template name.
	 *
	 * @param $template
	 * @return string
	 */
	public static function get_template_path( $template ) {
		$path = es_locate_template( sprintf( 'fields-builder/%s.php', $template ), 'admin' );

		return apply_filters( 'es_fields_buider_get_template_path', $path, $template );
	}

	/**
	 * Save fbuilder field.
	 *
	 * @return void
	 */
	public function action_save_field() {

		$nonce_name = 'es_fbuilder_save_field';

		$nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

		if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) && ! empty( $_POST['fbuilder'] ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			$field = wp_unslash( $_POST['fbuilder'] );

			foreach ( $field as $field_name => $value ) {
				if ( is_array( $value ) ) {
					$field[ $field_name ] = array_map( 'sanitize_text_field', $value );
				} else {
					$field[ $field_name ] = sanitize_text_field( $value );
				}
			}

			$label = $field['label'];

			if ( Es_FBuilder_Helper::save_field( $field ) ) {
				$messenger->set_message( sprintf( __( 'Field %s successfully created.', 'es-plugin' ), $label ), 'success' );
			} else {
				$messenger->set_message( sprintf( __( 'Field %s doesn\'t created.', 'es-plugin' ), $label ), 'error' );
			}
		}
	}

	/**
	 * Save fbuilder field.
	 *
	 * @return void
	 */
	public function action_save_section() {

		$nonce_name = 'es_fbuilder_save_section';
		$nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

		if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			$section = $_POST['fbuilder'];

			foreach ( $section as $field_name => $value ) {
				if ( is_array( $value ) ) {
					$section[ $field_name ] = array_map( 'sanitize_text_field', $value );
				} else {
					$section[ $field_name ] = sanitize_text_field( $value );
				}
			}

			$label = $section['label'];

			if ( Es_FBuilder_Helper::save_section( $section ) ) {
				$messenger->set_message( sprintf( __( 'Section %s successfully created.', 'es-plugin' ), $label ), 'success' );
			} else {
				$messenger->set_message( sprintf( __( 'Section %s doesn\'t created.', 'es-plugin' ), $label ), 'error' );
			}
		}
	}

	/**
	 * Remove field action.
	 *
	 * @return void
	 */
	public function action_restore_field()
	{
		$nonce_name = 'es-fbuilder-restore-field';

		$nonce = sanitize_key( filter_input( INPUT_GET, 'nonce' ) );

		if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) && ! empty( $_GET['id'] ) ) {
			$messenger = new Es_Messenger( 'fbuilder' );

			$id = sanitize_text_field( $_GET['id'] );

			/** @var Es_Settings_Container $es_settings */
			global $es_settings;

			if ( in_array( $id, $es_settings->property_removed_fields ) ) {
				$removed_fields = $es_settings->property_removed_fields;
				$key = array_search( $id, $removed_fields );
				unset( $removed_fields[ $key ] );

				$es_settings->saveOne( 'property_removed_fields', $removed_fields );

				$messenger->set_message( __( 'Field successfully restored.', 'es-plugin' ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Field #%s isn\'t exist.', 'es-plugin' ), $id ), 'error' );

			}
		}
	}

	/**
	 * Remove field action.
	 *
	 * @return void
	 */
	public function action_remove_field()
	{
		$nonce = 'es-fbuilder-remove-field';

		if ( ! empty( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ], $nonce ) && ! empty( $_GET['id'] ) ) {

			$id = sanitize_text_field( $_GET['id'] );

			$field = Es_FBuilder_Helper::get_field( $id );
			$messenger = new Es_Messenger( 'fbuilder' );

			$is_base_field = sanitize_key( filter_input( INPUT_GET, 'base_field' ) );

			if ( $field || $is_base_field ) {
				global $es_settings;

				if ( ! empty( $is_base_field ) ) {
					$removed_fields = $es_settings->property_removed_fields;
					$removed_fields = $removed_fields ? $removed_fields : array();
					$removed_fields = array_merge( $removed_fields, array( $id ) );
					$es_settings->saveOne( 'property_removed_fields', $removed_fields );
				} else {
					Es_FBuilder_Helper::remove_field( $id );
				}
				$messenger->set_message( sprintf( __( 'Field %s successfully removed.', 'es-plugin' ), $field['label'] ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Field #%s isn\'t exist.', 'es-plugin' ), $id ), 'error' );
			}
		}
	}

	/**
	 * Remove section action.
	 *
	 * @return void
	 */
	public function action_remove_section()
	{
		$nonce_name = 'es-fbuilder-remove-section';
		$nonce = sanitize_key( filter_input( INPUT_GET, 'nonce' ) );

		if ( ! empty( $_GET[ 'nonce' ] ) && wp_verify_nonce( $nonce, $nonce_name ) && ! empty( $_GET['id'] ) ) {
			$id = sanitize_key( $_GET['id'] );
			$field = Es_FBuilder_Helper::get_section( $id );
			$messenger = new Es_Messenger( 'fbuilder' );

			if ( $field ) {
				Es_FBuilder_Helper::remove_section( $id );
				$messenger->set_message( sprintf( __( 'Section %s successfully removed.', 'es-plugin' ), $field['label'] ), 'success' );
				wp_redirect( 'admin.php?page=es_fbuilder#es-es-section-tab' ); die;
			} else {
				$messenger->set_message( sprintf( __( 'Section #%s isn\'t exist.', 'es-plugin' ), $id ), 'error' );
			}
		}
	}

	/**
	 * Ajax action. Get field additional options fields.
	 *
	 * @return void
	 */
	public function action_load_field_options()
	{
		if ( current_user_can( 'es_manage_fb' ) && check_ajax_referer( 'es_admin_nonce', 'nonce' ) ) {

			$type = sanitize_key( filter_input( INPUT_POST, 'type' ) );

			if ( ( $type ) && ( $template = Es_FBuilder_Helper::get_field_options_template( $type ) ) ) {
				$path = apply_filters( 'es_fbuilder_field_options_path', self::get_template_path( 'partials/options/' . $template ) );
				if ( $template && file_exists( $path ) ) {
					include $path;
				}
			}
		}

		wp_die();
	}

	/**
	 * Ajax action. Set fields order.
	 *
	 * @return void
	 */
	function action_change_fields_order()
	{
		if ( current_user_can( 'es_manage_fb' ) && check_ajax_referer( 'es_admin_nonce', 'nonce' ) ) {

			$section = filter_input(INPUT_POST, 'section');
			$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( $section && is_array( $order ) && ! empty( $order ) ) {
				Es_FBuilder_Helper::set_section_fields_order( $section, $order );

				$response = ! empty( $response ) ? $response : array( 'status' => true );

			} else {
				$response = array( 'status' => false );
			}

			wp_die( apply_filters( 'es_fbuilder_action_change_fields_order', $response ) );
		}
	}

	/**
	 * Ajax action. Set sections order.
	 *
	 * @return void
	 */
	public function action_change_sections_order()
	{
		if ( current_user_can( 'es_manage_fb' ) && check_ajax_referer( 'es_admin_nonce', 'nonce' ) ) {
			$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $order ) && ! empty( $order ) ) {
				Es_FBuilder_Helper::set_sections_order( $order );
				$response = ! empty( $response ) ? $response : array( 'status' => true );

			} else {
				$response = array( 'status' => false );
			}

			wp_die( apply_filters( 'es_fbuilder_action_change_sections_order', $response ) );
		}
	}
}
