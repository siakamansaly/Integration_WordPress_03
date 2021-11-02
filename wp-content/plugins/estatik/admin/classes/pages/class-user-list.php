<?php

/**
 * Class Es_User_List_Page.
 */
class Es_User_List_Page {

	/**
	 * @var int
	 */
	static $counter = 0;

	/**
	 * @return void
	 * @static
	 */
	public static function init() {
		$_ = new self();

		if ( self::is_user_list_page() ) {
			$_->filters();
			$_->actions();
		}
	}

	/**
	 * @return void
	 */
	public function filters() {
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );
		add_filter( 'user_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_filter( 'views_users', array( $this, 'views_users' ), 10, 2 );
		add_action('restrict_manage_users', array( $this, 'users_filter' ) );
		// Global js variables.
		add_filter( 'es_global_js_variables', array( $this, 'add_js_variables' ), 10, 1 );
		add_filter( 'pre_get_users', array( $this, 'filter_handler' ) );
	}

	/**
	 * @return void
	 */
	public function actions() {
		add_action( 'manage_users_columns', array( $this, 'add_columns'), 10, 2 );
		// Enqueue styles for our page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		// Enqueue scripts for our page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Add action for render custom columns values.
		add_filter( 'manage_users_custom_column' , array( $this, 'add_columns_values' ), 10, 3 );
		add_action( 'init', array( $this, 'agent_actions' ) );
	}

	/**
	 * Do actions from agents list page.
	 *
	 * @return void
	 */
	public function agent_actions() {
		$action = sanitize_key( filter_input( INPUT_GET, 'es-action' ) );
		$users = ! empty( $_GET['users'] ) ? $_GET['users'] : null;

		if ( ! $users ) return ;

		if ( ! function_exists( 'wp_delete_user' ) ) {
			// Include helper wordpress functions for saving agent.
			include ( ABSPATH . 'wp-admin/includes/user.php' );
		}

		if ( $action && is_array( $users ) && ! empty( $users ) ) {

			$users = array_map( 'intval', $users );

			switch ( $action ) {
				case 'delete':
					if ( current_user_can( 'delete_users' ) ) {
						foreach ( $users as $user_id ) wp_delete_user( $user_id );
					}
					break;

				case 'activate':
					foreach ( $users as $user_id ) {
						$agent = es_get_user_entity( $user_id );
						$agent->change_status( $agent::STATUS_ACTIVE );
					}
					break;

				case 'deactivate':
					foreach ( $users as $user_id ) {
						$agent = es_get_user_entity( $user_id );
						$agent->change_status( $agent::STATUS_DISABLED );
					}
					break;
			}

			wp_redirect( apply_filters( 'es_agent_actions_redirect', $_SERVER['HTTP_REFERER'], $action ) ); exit;
		}
	}

	/**
	 * Create handler for post filtering.
	 *
	 * @param WP_User_Query $query
	 */
	public function filter_handler( $query ) {
		// Get filter data.
		$filter = filter_input( INPUT_GET, 'es_user_filter', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( ! empty( $filter ) ) {
			$meta_query = array();

			if ( ! empty( $filter['name'] ) ) {
				$query->set( 'search', '*' . sanitize_text_field( $filter['name'] ) . '*' );
				$query->set( 'search_columns', array( 'user_login', 'user_email', 'user_nicename', 'display_name', 'first_name', 'last_name' ) );
//				$meta_query[] = array( 'key' => 'es_agent_name', 'value' => $filter['name'] );
			}

			if ( $meta_query ) {
				$query->set( 'meta_query', $meta_query );
			}
		}
	}

	/**
	 * Add user filter to the users list page.
	 *
	 * @param string $position
	 */
	public function users_filter( $position ) {
		if ( $position == 'top' ) {
			es_load_template( 'buyers/filter.php', 'admin', 'es_admin_buyer_list_filter_path' );
		}
	}

	/**
	 * Add column values for agents table.
	 *
	 * @param $output
	 * @param $column
	 * @param $user_id
	 * @return int|string
	 */
	public function add_columns_values( $output, $column, $user_id ) {
		$user = es_get_user_entity( $user_id );

		switch ( $column ) {
			case 'id':
				return $user_id;

            case 'full_name':
                return $user->get_full_name();

			case 'status':
				if ( $user::STATUS_ACTIVE == $user->status ) {
					return '<span style="color: green">' . __( 'Active', 'es-plugin' ) . '</span>';
				} else {
					return '<span style="color: red">' . __( 'Disabled', 'es-plugin' ) . '</span>';
				}

			default:
				return $output;
		}
	}

	/**
	 * Register and enqueue scripts for our page.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$role = sanitize_key( filter_input( INPUT_GET, 'role' ) );

		if ( $role == Es_Buyer::get_role_name() ) {
			$url = 'admin.php?page=es_buyer';
		}

		wp_register_script( 'es-admin-agents-list-style', ES_ADMIN_CUSTOM_SCRIPTS_URL . 'agent-list.js', array ( 'jquery', 'es-select2-script' ) );
		wp_localize_script( 'es-admin-agents-list-style', 'EstatikUserList', array(
			'add_user_url' => $url
		) );
		wp_enqueue_script( 'es-admin-agents-list-style' );
	}

	/**
	 * Add JS variables for this page.
	 *
	 * @param $data
	 * @return mixed
	 */
	public function add_js_variables( $data ) {
		if ( current_user_can( 'activate_plugins' ) ) {
			$data['html']['logo'] = es_get_logo();
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public function views_users() {
		return array();
	}

	/**
	 * Customize users table row actions.
	 *
	 * @param $actions
	 * @param $user
	 * @return mixed
	 */
	public function row_actions( $actions, $user ) {
		$actions = array();

		// Customize edit link.
		$edit_link = apply_filters( 'es_agent_edit_link', 'admin.php?page=es_buyer&id=' . $user->ID, $user );

		$redirect_uri = es_admin_buyers_uri();

		$actions['edit'] = '<a href="' . $edit_link . '">
            <i class="fa fa-pencil" aria-hidden="true"></i></a>';

		// Customize remove link.
		$actions['delete'] = "<a href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user->ID&wp_http_referer=" . $redirect_uri, 'bulk-users' ) . "'><i class='fa fa-trash' aria-hidden='true'></i></a>";

		return $actions;
	}

	/**
	 * @param $columns
	 * @return array
	 */
	public function add_columns( $columns ) {
		unset( $columns['role'], $columns['posts'], $columns['name'] );
		// Add user ID column.
		$columns = static::push_column( array( 'id' => __( 'ID', 'es-plugin' ) ),  $columns, 1 );
        $columns = static::push_column( array( 'full_name' => __( 'Name', 'es-plugin' ) ),  $columns, 3 );
		$columns = static::push_column( array( 'status' => __( 'Status', 'es-plugin' ) ),  $columns, 8 );

		return $columns;
	}

	/**
	 * Add custom body class for users agents page.
	 *
	 * @param array $classes
	 * @return string
	 */
	public function add_body_class( $classes ) {
		return $classes . ' es-agent-list-page ';
	}

	/**
	 * Check if current page is agents list.
	 *
	 * @return bool
	 */
	public static function is_user_list_page() {
		$role = sanitize_key( filter_input( INPUT_GET, 'role' ) );
		return is_admin() && $role && in_array( $role, es_get_plugin_user_roles() );
	}

	/**
	 * Register and enqueue styles for our page.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( 'es-admin-user-list-style', ES_ADMIN_CUSTOM_STYLES_URL . 'agent-list.css' );
		wp_enqueue_style( 'es-admin-user-list-style' );
		wp_enqueue_style( 'jquery-ui' );
	}

	/**
	 * Helper function for pushing new columns.
	 *
	 * @param array $column
	 *    name => label array column.
	 * @param array &$list
	 *    Current array of all columns.
	 * @param $index
	 *    Index of pushed element.
	 *
	 * @return array
	 *    Array with pushed column by index.
	 */
	protected static function push_column( $column, $list, $index ) {
		return array_merge( array_slice( $list, 0, $index ), $column, array_slice( $list,$index ) );
	}
}
