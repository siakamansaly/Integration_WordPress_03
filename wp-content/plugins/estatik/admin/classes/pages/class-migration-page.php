<?php

/**
 * Class Es_Migration_Page
 */
class Es_Migration_Page extends Es_Object
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_es_migration', array( $this, 'migration_handler' ) );
        add_action( 'upgrader_process_complete', array( $this, 'upgrade_plugin' ), 10, 2 );
    }

    /**
     * @inheritdoc
     */
    public function filters()
    {
        add_filter( 'es_global_js_variables', array( $this, 'global_js_variables' ), 10, 1 );
    }

    /**
     * Add new js variable.
     *
     * @param $data
     * @return mixed
     */
    public function global_js_variables( $data )
    {
        $data['settings']['listingsLink'] = admin_url( es_admin_property_list_uri() );
        return $data;
    }

    /**
	 * Calls when plugin is updated.
	 *
	 * @param WP_Upgrader $upgrader
	 * @param $options
	 */
	public function upgrade_plugin( $upgrader, $options )
	{
		$current_plugin_path_name = plugin_basename( __FILE__ );

		if ( ! empty( $options['packages'] ) && 'update' == $options['action'] && 'plugin' == $options['type'] && es_need_migrate() ){
			foreach( $options['packages'] as $each_plugin ){
				if ( $each_plugin == $current_plugin_path_name ) {
					wp_redirect( admin_url( 'admin.php?page=es_migration' ), 301 );
					die;
					break;
				}
			}
		}
	}

    /**
     * Render migration page.
     *
     * @return void
     */
    public static function render()
    {
        $template_path = apply_filters( 'es_migration_page_template_path', ES_ADMIN_TEMPLATES . 'migration/migration.php' );

        $storage = new Es_Session_Storage( 'migration' );

        $storage->clear_all();
        Es_Property_Migration::get_logger()->clean_container();

        include( $template_path );

        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue scripts for migration page.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        $custom = 'admin/assets/js/custom/';

        wp_register_script( 'es-migration-script', ES_PLUGIN_URL . $custom . 'migration.js', array (
            'jquery',
            'es-progress-script',
            'jquery-ui-sortable',
            'es-admin-scroll-script'
        ) );

        wp_enqueue_script( 'es-migration-script' );

        // Share plugin JS variables for locations script.
        wp_localize_script( 'es-migration-script', 'Estatik', Estatik::register_js_variables() );

        $vendor = 'admin/assets/css/vendor/';

        wp_register_style( 'es-scroll-style', ES_PLUGIN_URL . $vendor . 'jquery.mCustomScrollbar.css' );
        wp_enqueue_style( 'es-scroll-style' );
    }

    /**
     * @return void
     */
    public function migration_handler()
    {
    	if ( check_ajax_referer( 'es_migration', 'es_migration_arg' ) ) {
		    // Disable time limit for long-term operations.
		    set_time_limit(0);
		    ini_set('max_execution_time', 0);

		    // Initialize session storage.
		    $storage = new Es_Session_Storage( 'migration' );

		    // Set ajax action for response.
		    $response['action'] = 'es_migration';
		    $response['es_migration_arg'] = $_POST['es_migration_arg'];

		    Es_Property_Migration::get_logger()->clean_container();

		    if ( ! $storage->get( 'properties' ) ) {
			    $props = Es_Property_Migration::get_prop_ids();
			    $storage->set( 'properties', $props );
		    }

		    $diff = array_diff( $storage->get( 'properties', array() ), $storage->get( 'imported', array() ) );

		    if ( ! $storage->get( 'settings_migrated' ) ) {
			    Es_Property_Migration::migrate_settings();
			    Es_Property_Migration::get_logger()->set_message( __( 'Estatik settings successfully migrated', 'es-plugin' ), 'success' );
			    $storage->set( 'settings_migrated', 1 );
		    }

		    if ( $diff ) {
			    $prop_id = array_shift( array_values( $diff ) );
			    Es_Property_Migration::migrate_property( $prop_id );
			    $imported = $storage->get( 'imported', array() );
			    $imported[] = $prop_id;
			    $storage->set( 'imported', $imported );

			    $count_all = count( $storage->get( 'properties', array() ) );
			    $count_imported = count( $imported );

			    $response['progress'] = ceil( ( $count_imported * 100 / $count_all ) );
		    } else {

			    if ( ! $storage->get( 'properties' ) ) {
				    Es_Property_Migration::get_logger()->set_message( 'Nothing to migrate.', 'warning' );
			    }

			    $response['progress'] = 100;
			    $response['done'] = 1;

			    es_migration_set_executed();
		    }

		    $response['messages'] = Es_Property_Migration::get_logger()->get_messages();

		    $response = apply_filters( 'es_migration_response', $response );

		    wp_die( json_encode( $response ) );
	    }
    }
}
