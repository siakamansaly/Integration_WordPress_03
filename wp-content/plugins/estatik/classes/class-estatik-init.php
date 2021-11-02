<?php

/**
 * Class Estatik
 */
class Estatik
{
    /**
     * Plugin instance.
     *
     * @var Estatik
     */
    protected static $_instance;
    /**
     * Plugin version.
     *
     * @var string
     */
    protected static $_version = '3.11.4';

    /**
     * Estatik constructor.
     */
    protected function __construct()
    {
        $this->actions();
        $this->init();
        $this->filters();
    }

    /**
     * Return plugin version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return static::$_version;
    }

    /**
     * Return plugin instance.
     *
     * @return Estatik
     */
    protected static function getInstance()
    {
        return is_null( static::$_instance ) ? new Estatik() : static::$_instance;
    }

    /**
     * Initialize plugin.
     *
     * @return void
     */
    public static function run()
    {
        self::class_loader();
        static::$_instance = static::getInstance();
    }

    /**
     * Return registered image size.
     *
     * @return array
     */
    public static function get_image_sizes()
    {
        return array(
            'es-image-size-archive' => array( 536, 370, true ),
            'es-agent-size' => array( 190, 250, true ),
            'es-pdf-featured' => array( 385, 335, true ),
            'es-pdf-thumbnail' => array( 160, 105, true ),
        );
    }

    /**
     * Execute on plugin activate action.
     *
     * @return void
     */
    public static function activation() {
        $instance = static::getInstance();

        $instance::install();

        $instance->register_post_types();
        flush_rewrite_rules();
    }

    /**
     * Execute on plugin deactivate action.
     *
     * @return void
     */
    public static function deactivation() {}

    /**
     * Add plugin actions.
     *
     * @return void
     */
    public function actions()
    {
        add_action( 'wp', array( $this, 'set_global_vars' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, '_migration' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'admin_menu', array( $this, 'register_admin_pages' ) );
        add_action( 'es_after_content', array( $this, 'powered' ) );
        add_action( 'es_shortcode_list_after', array( $this, 'powered' ) );
        add_action( 'es_shortcode_after', array( $this, 'powered' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

	    add_action( 'post_edit_form_tag' , function() {
		    echo ' enctype="multipart/form-data"';
	    } );

	    add_action( 'init', array( $this, 'login' ) );
	    add_action( 'wp_login', array( $this, 'login_redirect' ), 7, 2 );

        add_action( 'save_post', array( 'Es_Property', 'save' ), 10, 2 );
        add_action( 'before_delete_post', array( 'Es_Property', 'delete' ) );
        add_action( 'init', array( 'Es_Settings_Page', 'save' ) );
        add_action( 'activated_plugin', array( $this, 'redirect' ) );
        add_action( 'register_post_type_args', array( 'Estatik', 'property_slug' ), 10, 2 );
        add_action( 'update_option_es_property_slug', array( 'Estatik', 'set_flush_rewrite_rules' ) );
        add_action( 'admin_notices', array( 'Estatik', 'coupon_notice' ) );
    }

    /**
     * Render admin coupon notice.
     */
    public static function coupon_notice() {
    	return false;
        $curtime = time();
        $starttime = strtotime( '2021-04-01 00:00:00' );
        $endtime = strtotime( '2021-04-08 23:59:59' );

        if ( $starttime <= $curtime && $endtime >= $curtime ) {
            $diff = $endtime - $curtime;
            $days = floor( $diff / 60 / 60 / 24 );
            $hours = floor( $diff / 60 / 60 );
            $minutes = floor( $diff / 60 );
            $banner = 'banner1.php';
        } else {
//            $time = get_option( 'es_trial_coupon' );
            $time = get_option( 'es_trial_instagram_bundle' );
            if ( ! $time ) {
                $time = time();
                update_option( 'es_trial_instagram_bundle', $time );
            }
            $endtime = $time + ( 4320 * 60 );
            $diff = $endtime - $curtime;
            $days = floor( $diff / 60 / 60 / 24 );
            $hours = floor( $diff / 60 / 60 );
            $minutes = floor( $diff / 60 );
            $banner = 'simple.php';
        }

        if ( $minutes > 0 ) :
            include ES_PLUGIN_PATH . '/admin/templates/banner/' . $banner;
        endif;
    }

    /**
     * Fix 404 for custom post types.
     */
    public static function set_flush_rewrite_rules() {
        update_option( 'es_need_flush', 1 );
    }

    /**
     * @param $args
     * @param $post_type
     * @return mixed
     */
    public static function property_slug( $args, $post_type ) {

        if ( 'properties' === $post_type ) {
            global $es_settings;
            $args['rewrite']['slug'] = $es_settings->property_slug;
        }

        return $args;
    }

    /**
     * @param $plugin
     */
    public function redirect( $plugin ) {
	    $page = filter_input( INPUT_GET, 'page' );
	    if ( $plugin == ES_PLUGIN_BASENAME && $page != 'tgmpa-install-plugins' ) {
	        $checked = ! empty( $_POST['checked'] ) ? $_POST['checked'] : array();
            if ( ! es_demo_executed() && count( $checked ) <=1 ) {
                exit ( wp_redirect( 'admin.php?page=es_demo&step=start' ) );
            }
        }
    }

    /**
     * Add filters to the wp functionality.
     *
     * @return void
     */
    public function filters() {

        if ( ! is_admin() ) {
            add_filter( 'body_class', array( $this, 'body_class' ) );
        }

        add_filter( 'image_resize_dimensions', array( $this, 'thumbnail_upscale' ), 10, 6 );
	    add_filter( 'authenticate', array( $this, 'authenticate' ), 30, 1 );
	    add_filter( 'lostpassword_url', array( $this, 'lostpassword_url'), 10, 1 );
    }

	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	public function lostpassword_url( $url )
	{
		global $es_settings, $pagenow;

		$restore_pwd_page_id = $es_settings->reset_password_page_id;

		if ( $pagenow != 'wp-login.php' && $restore_pwd_page_id && ( $post = get_post( $restore_pwd_page_id ) ) ) {
			$url = get_permalink( $post );
		}

		return $url;
	}

    /**
     * Add class to the body.
     *
     * @param $classes
     * @return array
     */
    public function body_class( $classes )
    {
        global $es_settings;

        return array_merge( $classes, array( 'es-theme-' . $es_settings->theme_style ) );
    }

    /**
     * Crop image fix.
     *
     * @param $default
     * @param $orig_w
     * @param $orig_h
     * @param $new_w
     * @param $new_h
     * @param $crop
     * @return array|null
     */
    public function thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
        if ( !$crop ) return null; // let the wordpress default function handle this

        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);

        $s_x = floor( ($orig_w - $crop_w) / 2 );
        $s_y = floor( ($orig_h - $crop_h) / 2 );

        return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
    }

    /**
     * Initialize entity classes using specific conditions.
     *
     * @return void
     */
    public function init()
    {
        global $es_settings;

        $es_settings = new Es_Settings_Container();

        // Initialize classes for admin panel.
        if ( is_admin() ) {

        	$page = sanitize_key( filter_input( INPUT_GET, 'page' ) );
        	$post_type = sanitize_key( filter_input( INPUT_GET, 'post_type' ) );

            // Initialize admin properties list page.
            if ( $post_type == 'properties' ) {
                Es_Property_List_Page::init();
            }

            // Initialize update pro page.
            if ( $page == 'es_pro' ) {
                Es_Upgrade_Pro_Page::init();
            }

	        if ( $page == 'es_agent' || $page == 'es_buyer' ) {
		        Es_User_Profile::init();
	        }

			// Initialize dashboard page.
			if ( $page == 'es_dashboard' ) {
				Es_Dashboard_Page::init();
			}

            Es_Migration_Page::init();
            Es_Property_Metabox::init();
	        Es_User_List_Page::init();
            Es_Data_Manager_Page::init();
            Es_Fields_Builder_Page::init();

        } else {
            // Initialize template loader class.
            Es_Template_Loader::init();
            Es_Archive_Sorting::init();
            Es_Property_Archive_Page::init();
            $GLOBALS['es_single_page_instance'] = Es_Property_Single_Page::init();
            Es_Inline_Assets::init();
        }

        Es_Demo_Setup::init();
        Es_Shortcodes::init();
	    Es_Manage_Users::init();
        Es_FBuilder::init();
    }

    /**
     * Install action. Triggering on plugin activation.
     *
     * @return void
     */
    public static function install() {
        if ( ! get_option( 'es_trial_instagram_bundle' ) ) {
            update_option( 'es_trial_instagram_bundle', time() );
        }

        global $wpdb;

        $table_name = $wpdb->prefix . 'address_components';
        $charset_collate = '';

        if ( ! empty ( $wpdb->charset ) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

        if ( ! empty ( $wpdb->collate ) )
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
            id int(11) NOT NULL AUTO_INCREMENT,
            long_name VARCHAR(255),
            short_name VARCHAR(255),
            `type` VARCHAR(255) NOT NULL,
            `locale` VARCHAR(10),
            PRIMARY KEY (id)
        ); ' . $charset_collate . ';';

        register_taxonomy( 'es_labels', 'properties' );

        $labels = es_get_standard_label_names();

        if ( taxonomy_exists( 'es_labels' ) && ! empty( $labels ) ) {
            foreach ( $labels as $color => $label ) {
                if ( ! term_exists( $label ) ) {
                    $term = wp_insert_term( $label, 'es_labels' );
                    if ( ! empty( $term['term_id'] ) ) {
                        update_term_meta( $term['term_id'], 'es_color', $color );
                    }
                }
            }
        }

        update_option( 'es_thumbnail_attachment_id', '' );

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        if ( ! get_role( 'es_buyer' ) ) {
	        $role = add_role( 'es_buyer', __( 'Estatik Buyer', 'es-plugin' ) );

	        $role->add_cap( 'manage_saved_search' );
        }

        $role = get_role( 'administrator' );

	    $caps = array(
		    'es_manage_dashboard',
		    'es_manage_listings_page',
		    'es_manage_data_manager',
		    'es_manage_buyers',
		    'es_manage_settings',
		    'es_manage_upgrade',
		    'es_manage_demo',
		    'es_manage_migration',
		    'manage_saved_search'
	    );

	    foreach ( $caps as $cap ) {
		    if ( ! $role->has_cap( $cap ) ) {
			    $role->add_cap( $cap );
		    }
	    }

        do_action( 'es_plugin_after_install' );
    }

    /**
     * Load plugin files.
     *
     * @return void
     */
    public static function class_loader()
    {
        $files = apply_filters( 'es_class_loader', array(
            ES_PLUGIN_PATH . '/classes/class-object.php',
            ES_PLUGIN_PATH . '/classes/class-elementor.php',
            ES_PLUGIN_PATH . '/classes/class-divi-builder.php',
            ES_PLUGIN_PATH . '/classes/class-inline-assets.php',
            ES_PLUGIN_PATH . '/admin/classes/class-search-location.php',
            ES_PLUGIN_PATH . '/admin/classes/class-property-metabox.php',
            ES_PLUGIN_PATH . '/admin/classes/class-demo-setup.php',
            ES_PLUGIN_PATH . '/classes/class-post-duplicate.php',
            ES_PLUGIN_PATH . '/classes/class-archive-sorting.php',
            ES_PLUGIN_PATH . '/classes/class-repository.php',

            ES_PLUGIN_PATH . '/classes/interfaces/class-wishlist-interface.php',
            ES_PLUGIN_PATH . '/classes/helpers/class-wishlist-cookie.php',
            ES_PLUGIN_PATH . '/classes/helpers/class-wishlist-user.php',

            ES_PLUGIN_PATH . '/classes/pages/class-property-single-page.php',
            ES_PLUGIN_PATH . '/classes/pages/class-property-archive-page.php',
	        ES_PLUGIN_PATH . '/classes/class-manage-users.php',

	        ES_PLUGIN_PATH . '/classes/auth/class-authentication.php',
	        ES_PLUGIN_PATH . '/classes/auth/class-facebook-authentication.php',
	        ES_PLUGIN_PATH . '/classes/auth/class-google-authentication.php',

            ES_PLUGIN_PATH . '/classes/shortcodes/class-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-my-listing-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-featured-props-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-latest-props-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-cheapest-props-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-expensive-props-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-category-shortcode.php',
	        ES_PLUGIN_PATH . '/classes/shortcodes/class-agent-register-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-single-shortcode.php',
	        ES_PLUGIN_PATH . '/classes/shortcodes/class-login-shortcode.php',
	        ES_PLUGIN_PATH . '/classes/shortcodes/class-restore-password-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-search-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-search-form-shortcode.php',
            ES_PLUGIN_PATH . '/classes/shortcodes/class-profile-shortcode.php',
	        ES_PLUGIN_PATH . '/classes/shortcodes/class-property-slideshow-shortcode.php',
            ES_PLUGIN_PATH . '/classes/class-shortcodes.php',
            ES_PLUGIN_PATH . '/admin/interfaces/es-messenger-interface.php',
            ES_PLUGIN_PATH . '/admin/classes/widgets/class-widget.php',
            ES_PLUGIN_PATH . '/admin/classes/widgets/class-search-widget.php',
            ES_PLUGIN_PATH . '/admin/classes/widgets/class-request-widget.php',
	        ES_PLUGIN_PATH . '/admin/classes/widgets/class-property-slideshow-widget.php',
            ES_PLUGIN_PATH . '/admin/classes/class-data-manager-item.php',
            ES_PLUGIN_PATH . '/admin/classes/class-taxonomy.php',
            ES_PLUGIN_PATH . '/admin/classes/class-data-manager-term-item.php',
            ES_PLUGIN_PATH . '/admin/classes/class-data-manager-address-item.php',
            ES_PLUGIN_PATH . '/admin/classes/class-data-manager-currency-item.php',
            ES_PLUGIN_PATH . '/admin/classes/class-data-manager-label-item.php',
            ES_PLUGIN_PATH . '/admin/classes/class-estatik-upgrade.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-dashboard-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-settings-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-data-manager-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-fields-builder-page.php',
            ES_PLUGIN_PATH . '/admin/classes/migration/class-property-migration.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-migration-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-property-list-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-upgrade-pro-page.php',
            ES_PLUGIN_PATH . '/admin/classes/pages/class-user-profile-page.php',

	        ES_PLUGIN_PATH . '/admin/classes/pages/class-user-list.php',

            ES_PLUGIN_PATH . '/admin/classes/pages/class-demo-content-page.php',
            ES_PLUGIN_PATH . '/admin/classes/class-messenger.php',
            ES_PLUGIN_PATH . '/admin/classes/class-fbuilder-helper.php',
            ES_PLUGIN_PATH . '/admin/classes/class-fbuilder.php',
            ES_PLUGIN_PATH . '/classes/class-settings-container.php',
            ES_PLUGIN_PATH . '/classes/class-html-helper.php',
            ES_PLUGIN_PATH . '/classes/class-entity.php',
            ES_PLUGIN_PATH . '/classes/class-post.php',
            ES_PLUGIN_PATH . '/classes/class-user.php',
            ES_PLUGIN_PATH . '/classes/class-buyer.php',
            ES_PLUGIN_PATH . '/classes/class-saved-search.php',
            ES_PLUGIN_PATH . '/classes/class-property.php',
            ES_PLUGIN_PATH . '/classes/class-address-components.php',
            ES_PLUGIN_PATH . '/classes/class-template-loader.php',
            ES_PLUGIN_PATH . '/classes/class-session-storage.php',
            ES_PLUGIN_PATH . '/classes/class-saved-search-component.php',
            ES_PLUGIN_PATH . '/functions.php',
        ) );

        foreach ( $files as $file ) {
            if ( file_exists( $file ) ) {
                include $file;
            }
        }
    }

    /**
     * Enqueue scripts for frontend part.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        $custom = 'assets/js/custom/';
        $adminVendor = 'admin/assets/js/vendor/';
        $vendor = 'assets/js/vendor/';

        global $es_settings;

        $language = es_get_gmap_locale();

        wp_register_script( 'es-select2-script', ES_PLUGIN_URL . $adminVendor . 'select2.min.js', array ( 'jquery' ) );

        if ( $es_settings->google_api_key ) {
            // Google map API.
            wp_register_script(
                'es-admin-googlemap-api',
                'https://maps.googleapis.com/maps/api/js?key=' . $es_settings->google_api_key . '&libraries=places&language=' . $language,
                array(),
                false
            );

            // Google map wrapper.
            wp_register_script(
                'es-admin-map-script',
                ES_PLUGIN_URL . $custom . 'map.min.js',
                array( 'es-admin-googlemap-api' )
            );
        }

        wp_register_script( 'es-magnific-script', ES_PLUGIN_URL . $vendor . 'jquery.magnific-popup.min.js', array ( 'jquery' ) );

	    wp_register_script( 'es-slick-script', ES_PLUGIN_URL . $vendor . 'slick.min.js', array ( 'jquery' ) );

	    // Base front script.
	    wp_enqueue_script(
		    'es-front-script',
		    ES_PLUGIN_URL . $custom . 'front.min.js',
		    array ( 'jquery', 'es-select2-script', 'es-slick-script', 'es-magnific-script' )
	    );

	    wp_localize_script( 'es-front-script', 'Estatik', Estatik::register_js_variables() );

	    $deps = array( 'jquery', 'es-front-script', 'es-magnific-script' );

	    if ( ! empty( $es_settings->google_api_key ) ) {
		    $deps[] = 'es-admin-map-script';
	    }

	    wp_enqueue_script( 'es-front-archive-script', ES_PLUGIN_URL . $custom . 'front-archive.min.js', $deps );


	    if ( class_exists('FLBuilderModel') && FLBuilderModel::is_builder_active() ) {
		    $this->admin_enqueue_scripts();
		    $this->admin_enqueue_styles();
	    }
    }

    /**
     * Enqueue styles for frontend part.
     *
     * @return void
     */
    public function enqueue_styles()
    {
        $vendor = 'assets/css/vendor/';
        $custom = 'assets/css/custom/';
        $adminVendor = 'admin/assets/css/vendor/';

        wp_register_style( 'es-select2-style', ES_PLUGIN_URL . $adminVendor . 'select2.min.css' );
        wp_enqueue_style( 'es-select2-style' );

	    // Register base styles for the plugin.
	    wp_register_style( 'es-magnific-style', ES_PLUGIN_URL . $vendor . 'magnific-popup.min.css' );

        // Register base styles for the plugin.
        wp_register_style( 'es-front-style', ES_PLUGIN_URL . $custom . 'front.min.css' , array( 'es-magnific-style' ) );
        wp_enqueue_style( 'es-front-style' );

	    wp_register_style( 'es-front-archive-style', ES_PLUGIN_URL . $custom . 'front-archive.min.css', array( 'es-magnific-style' ) );
	    wp_enqueue_style( 'es-front-archive-style' );

        // Register font awesome.
        wp_register_style( 'es-font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        wp_enqueue_style( 'es-font-awesome' );

	    wp_register_style( 'es-slick-style', ES_PLUGIN_URL . $vendor . 'slick.min.css' );
    }

    /**
     * Enqueue admin scripts.
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        global $es_settings;

        $custom = 'admin/assets/js/custom/';
        $vendor = 'admin/assets/js/vendor/';
        $admin_vendor = 'assets/js/vendor/';
        $custom_main = 'assets/js/custom/';

        $language = es_get_gmap_locale();

        wp_register_script(
            'es-admin-scroll-script', ES_PLUGIN_URL . $vendor . 'jquery.mCustomScrollbar.concat.min.js',
            array( 'jquery' ),
            false,
            $in_footer = false
        );

	    wp_register_script( 'es-magnific-script', ES_PLUGIN_URL . $admin_vendor . 'jquery.magnific-popup.min.js', array ( 'jquery' ) );

        wp_register_script(
            'es-data-manager-script', ES_PLUGIN_URL . $custom . 'jquery-data-manager.js',
            array ( 'jquery', 'es-popup-script' )
        );

        wp_register_script(
            'es-cloneya-script', ES_PLUGIN_URL . $vendor . 'jquery-cloneya.min.js',
            array ( 'jquery' )
        );

        wp_register_script(
            'es-datetime-picker', ES_PLUGIN_URL . $vendor . 'jquery.datetimepicker.min.js',
            array ( 'jquery' )
        );

        $deps = array (
            'jquery', 'es-popup-script', 'es-data-manager-script', 'es-slick-admin-script', 'jquery-ui-tabs',
            'es-admin-scroll-script', 'es-datetime-picker'
        );

        if ( is_admin() ) {
        	$deps[] = 'wp-color-picker';
        }

        if ( $es_settings->google_api_key ) {
            wp_register_script(
                'es-admin-map-script', ES_PLUGIN_URL . $custom_main . 'map.min.js',
                array( 'es-admin-googlemap-api' ),
                false
            );

            wp_register_script(
                'es-admin-googlemap-api',
                'https://maps.googleapis.com/maps/api/js?key=' . $es_settings->google_api_key . '&libraries=places&language='.$language,
                array(),
                false
            );

            $deps[] = 'es-admin-map-script';
        }

        wp_register_script( 'es-admin-script', ES_PLUGIN_URL . $custom . 'admin.js', $deps );

        wp_enqueue_script( 'es-admin-script' );

        wp_register_script( 'es-select2-script', ES_PLUGIN_URL . $vendor . 'select2.min.js', array ( 'jquery' ) );

        wp_register_script( 'es-checkbox-script', ES_PLUGIN_URL . $custom . 'es-checkboxes.js', array ( 'jquery' ) );
        wp_enqueue_script( 'es-checkbox-script' );

        wp_register_script( 'es-tooltipster-script', ES_PLUGIN_URL . $vendor . 'tooltipster.bundle.min.js', array ( 'jquery' ) );
        wp_enqueue_script( 'es-tooltipster-script' );

        wp_register_script( 'es-popup-script', ES_PLUGIN_URL . $custom . 'es-popup.js', array ( 'jquery' ) );

        wp_register_script( 'es-progress-script', ES_PLUGIN_URL . $vendor . 'jquery.progress.js', array ( 'jquery' ) );

        // Register slider for dashboard page.
        wp_register_script( 'es-slick-admin-script', ES_PLUGIN_URL . $admin_vendor . 'slick.min.js', array ( 'jquery' ) );

	    wp_register_script( 'es-slick-script', ES_PLUGIN_URL . $admin_vendor . 'slick.min.js', array (
		    'jquery',
	    ) );

        wp_localize_script( 'es-admin-script', 'Estatik', static::register_js_variables() );
    }

    /**
     * Enqueue admin styles.
     *
     * @return void
     */
    public function admin_enqueue_styles()
    {
        $vendor = 'admin/assets/css/vendor/';
        $vendor_main = 'assets/css/vendor/';
        $custom = 'admin/assets/css/custom/';

	    wp_register_style( 'es-magnific-style', ES_PLUGIN_URL . $vendor_main . 'magnific-popup.min.css' );

        wp_register_style( 'es-tooltipster-style', ES_PLUGIN_URL . $vendor . 'tooltipster.bundle.min.css' );
        wp_enqueue_style( 'es-tooltipster-style' );

        wp_register_style( 'es-tooltipster-theme-style', ES_PLUGIN_URL . $vendor . 'tooltipster-sideTip-borderless.min.css' );
        wp_enqueue_style( 'es-tooltipster-theme-style' );

        wp_register_style( 'es-select2-style', ES_PLUGIN_URL . $vendor . 'select2.min.css' );
        wp_enqueue_style( 'es-select2-style' );

        wp_register_style( 'es-font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        wp_enqueue_style( 'es-font-awesome' );

        wp_register_style( 'es-datetime-picker-css', ES_PLUGIN_URL . $vendor . 'jquery.datetimepicker.css' );
        wp_enqueue_style( 'es-datetime-picker-css' );

        wp_register_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );

        wp_register_style( 'es-admin-style', ES_PLUGIN_URL . $custom . 'admin.css' );
        wp_enqueue_style( 'es-admin-style' );

        wp_register_style( 'es-checkboxes-style', ES_PLUGIN_URL . $custom . 'es-checkboxes.css' );
        wp_enqueue_style( 'es-checkboxes-style' );

        wp_register_style( 'es-popup-style', ES_PLUGIN_URL . $custom . 'es-popup.css' );
        wp_enqueue_style( 'es-popup-style' );

	    wp_register_style( 'es-slick-style', ES_PLUGIN_URL . $vendor_main . 'slick.min.css' );
    }

    /**
     * Register global javascript
     *
     * @return array
     */
    public static function register_js_variables()
    {
        global $es_settings;

	    if ( empty( $es_settings ) ) {
		    $es_settings = new Es_Settings_Container();
	    }

        return apply_filters( 'es_global_js_variables', array(
            'tr' => array(
            	'system_error' => __( 'Something was wrong. Please, contact the support.', 'es-plugin' ),
                'remove_image' => __( 'Remove image', 'es-plugin' ),
                'remove' => __( 'Remove', 'es-plugin' ),
                'yes' => __( 'Yes', 'es-plugin' ),
                'btn_generating' => __( 'Generating', 'es-plugin' ),
                'no' => __( 'No', 'es-plugin' ),
                'saved' => __( 'Saved', 'es-plugin' ),
                'loading_shortcode_params' => __( 'Loading Shortcode Attributes...', 'es-plugin' ),
                'saving' => __( 'Saving', 'es-plugin' ),
                'error' => __( 'Error', 'es-plugin' ),
                'ok' => __( 'Ok', 'es-plugin' ),
                'select_location' => __( 'Select location', 'es-plugin' ),
                'sorting' => __( 'Sort by', 'es-plugin' ),
                'multipleInput' => __( 'Enable if you need to use multiselect feature for this drop-down field.', 'es-plugin' ),
                'confirmDeleting' => __( 'Are you sure you want to delete this item?', 'es-plugin' ),
                'dragndropAvailable' => sprintf( __( 'The drag & drop feature <br>is available in %s or <br> %s versions.', 'es-plugin' ),
                    '<a target="_blank" href="https://estatik.net/product/estatik-professional/">' . __( 'Estatik PRO', 'es-plugin' ) . '</a>',
                    '<a target="_blank" href="https://estatik.net/product/estatik-premium-rets/">' . __( 'Premium', 'es-plugin' ) . '</a>' ),
                'retsAvailable' => sprintf( __( 'The RETS integration feature <br>is available in %s or <br> %s versions.', 'es-plugin' ),
                    '<a target="_blank" href="https://estatik.net/product/estatik-professional/">' . __( 'Estatik PRO', 'es-plugin' ) . '</a>',
                    '<a target="_blank" href="https://estatik.net/product/estatik-premium-rets/">' . __( 'Premium', 'es-plugin' ) . '</a>' ),
                'searchAvailable' => sprintf( __( 'The Search integration feature <br>is available in %s or <br> %s versions.', 'es-plugin' ),
                    '<a target="_blank" href="https://estatik.net/product/estatik-professional/">' . __( 'Estatik PRO', 'es-plugin' ) . '</a>',
                    '<a target="_blank" href="https://estatik.net/product/estatik-premium-rets/">' . __( 'Premium', 'es-plugin' ) . '</a>' ),
            ),
            'settings' => array(
            	'wishlist_nonce' => wp_create_nonce( 'es_wishlist_nonce' ),
            	'save_search_nonce' => wp_create_nonce( 'es_save_search_nonce' ),
            	'save_search_change_method_nonce' => wp_create_nonce( 'save_search_change_method_nonce' ),
                'admin_nonce' => wp_create_nonce( 'es_admin_nonce' ),
                'front_nonce' => wp_create_nonce( 'es_front_nonce' ),
                'isRTL' => is_rtl(),
                'pluginUrl' => ES_PLUGIN_URL,
                'layout' => $es_settings->listing_layout,
                'dateFormat' => $es_settings->date_format,
                'demoFinished' => es_get_demo_finish_url(),
                'dateTimeFormat' => $es_settings->date_format . ' H:i',
                'recaptcha_version' => $es_settings->recaptcha_version,
                'disable_sticky_property_top_bar' => $es_settings->disable_sticky_property_top_bar,
                'responsive' => array(
                    'es-layout-list' => array( 'min' => 660, 'max' =>  999999 ),
                    'es-layout-2_col' => array( 'max' => 640, 'min' =>  0 ),
                    'es-layout-3_col' => array( 'min' => 620, 'max' => 999999 ),
                ),
	            'is_lightbox_disabled' => $es_settings->is_lightbox_disabled
            ),
            'widgets' => array(
                'search' => array(
                    'initPriority' => array(
                        'country' => array(
                            'state',
                            'province',
                            'city'
                        ),
                        'state' => array(
                            'city',
                            'province',
                        ),
                        'province' => array(
                            'city',
                            'street',
                        ),
                        'city' => array(
                            'street',
                            'neighborhood',
                        ),
                        'street' => array(
                            'neighborhood'
                        ),
                        'neighborhood' => array(),
                    ),
                )
            ),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ) );
    }

	/**
	 * Check if user is active.
	 *
	 * @param $user
	 * @return WP_Error
	 */
	public function authenticate( $user )
	{
		if ( $user && ! is_wp_error( $user ) && ( $user_entity = es_get_user_entity( $user->ID ) ) ) {

			if ( $user_entity->status != $user_entity::STATUS_ACTIVE ) {
				$user = new WP_Error( 'es_deactivated_user', __( 'Your account is not approved, please contact admin of this website to get approved.', 'es-plugin' ) );
			}
		}

		return $user;
	}

	/**
	 * Login redirect action.
	 *
	 * @param $login string
	 * @param $user WP_User
	 */
	public function login_redirect( $login, $user ) {

		$nonce_name = 'es-login';
		$nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

		if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) ) {
			$user_entity = es_get_user_entity( $user->ID );

			if ( $user_entity ) {
				if ( $user_entity::STATUS_DISABLED == $user_entity->status ) {
					wp_logout();
					$messenger = new Es_Messenger( 'login' );
					$messenger->clean_container();
					$messenger->set_message( __( 'Account disabled.', 'es-plugin' ), 'error' );
					wp_redirect( esc_url( $_POST['redirect'] ) );
					die;
				} else {
					wp_redirect( ! empty( $_POST['redirect_to'] ) && $user->has_cap( 'delete_es_properties' )
						? esc_url( $_POST['redirect_to'] ) : home_url() );
					die;
				}
			} else {
				wp_redirect( ! empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : esc_url( $_POST['_wp_http_referer'] ) );
				die;
			}
		}
	}

	/**
	 * @return void
	 */
	public function login()
	{
		$nonce_name = 'es-login';
		$nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

		if ( $nonce && wp_verify_nonce( $nonce, $nonce_name ) ) {
			global $user;
			$messenger = new Es_Messenger( 'login' );
			$creds = array();
			$creds['user_login'] = sanitize_text_field( $_POST['log'] );
			$creds['user_password'] =  sanitize_text_field( $_POST['pwd'] );
			$creds['remember'] = true;
			$user = wp_signon( $creds, is_ssl() );

			if ( is_wp_error( $user ) ) {
				$messenger->set_message( $user->get_error_message(), 'error' );
				wp_redirect( ! empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : esc_url( $_POST['_wp_http_referer'] ) ); die;
			}
		}
	}

    /**
     * Register admin pages.
     */
    public function register_admin_pages()
    {
    	global $es_settings;
        $imagesPath = 'admin/assets/images/';

        add_menu_page(
            __( 'Estatik', 'es-plugin' ),
            __( 'Estatik', 'es-plugin' ),
            'es_manage_dashboard',
            'es_dashboard',
            array( 'Es_Dashboard_Page', 'render' ),
            ES_PLUGIN_URL . $imagesPath .'es_menu_icon.png',
            '20.7'
        );

        add_submenu_page(
            'es_dashboard',
            __( 'Dashboard', 'es-plugin' ),
            __( 'Dashboard', 'es-plugin' ),
            'es_manage_dashboard',
            'es_dashboard',
            array( 'Es_Dashboard_Page', 'render' )
        );

        add_submenu_page(
            'es_dashboard',
            __( 'My listings', 'es-plugin' ),
            __( 'My listings', 'es-plugin' ),
            'es_manage_listings_page',
            es_admin_property_list_uri()
        );

        add_submenu_page(
            'es_dashboard',
            __( 'Add new property', 'es-plugin' ),
            __( 'Add new property', 'es-plugin' ),
            'es_manage_listings_page',
            es_admin_property_add_uri()
        );

        add_submenu_page(
            'es_dashboard',
            __( 'Data Manager', 'es-plugin' ),
            __( 'Data Manager', 'es-plugin' ),
            'es_manage_data_manager',
            'es_data_manager',
            array( 'Es_Data_Manager_Page', 'render' )
        );

	    if ( ! empty( $es_settings->is_tags_enabled ) ) {
		    add_submenu_page(
			    'es_dashboard',
			    __( 'Tags', 'es-plugin' ),
			    __( 'Tags', 'es-plugin' ),
			    'manage_options',
			    'edit-tags.php?taxonomy=es_tags'
		    );
	    }

        if ( $es_settings->buyers_enabled ) {
	        add_submenu_page(
		        'es_dashboard',
		        __( 'Buyers', 'es-plugin' ),
		        __( 'Buyers', 'es-plugin' ),
		        'es_manage_buyers',
		        es_admin_buyers_uri() );

	        add_submenu_page(
		        'es_dashboard',
		        __( 'Add New Buyer', 'es-plugin' ),
		        __( 'Add New Buyer', 'es-plugin' ),
		        'es_manage_buyers',
		        'es_buyer',
		        array( 'Es_User_Profile', 'render' )
	        );
        }

        add_submenu_page(
            'es_dashboard',
            __( 'Fields builder', 'es-plugin' ),
            __( 'Fields builder', 'es-plugin' ),
            'es_manage_fb',
            'es_fbuilder',
            array( 'Es_Fields_Builder_Page', 'render' )
        );

        add_submenu_page(
            'es_dashboard',
            __( 'Settings', 'es-plugin' ),
            __( 'Settings', 'es-plugin' ),
            'es_manage_settings',
            'es_settings',
            array( 'Es_Settings_Page', 'render' )
        );

        add_submenu_page(
            'es_dashboard',
            __( 'Estatik Pro', 'es-plugin' ),
            __( 'Estatik Pro', 'es-plugin' ),
            'es_manage_upgrade',
            'es_pro',
            array( 'Es_Upgrade_Pro_Page', 'render' )
        );

        if ( ! es_demo_executed() ) {
		    add_submenu_page(
			    'es_dashboard',
			    __( 'Demo content', 'es-plugin' ),
			    __( 'Demo content', 'es-plugin' ),
			    'es_manage_demo',
			    'es_demo',
			    array( 'Es_Demo_Content_Page', 'render' )
		    );
        }

        if ( ! es_migration_already_executed() && es_need_migrate() ) {
            add_submenu_page(
                'es_dashboard',
                __( 'Migration', 'es-plugin' ),
                __( 'Migration', 'es-plugin' ),
                'es_manage_migration',
                'es_migration',
                array( 'Es_Migration_Page', 'render' )
            );
        }
    }

	/**
	 * Display powered by phrase.
     *
     * @return void
	 */
	public function powered() {
		global $es_settings;

		if ( $es_settings->powered_by_link ) {
            es_load_template( 'powered.php', 'front', 'es_powered_template' );
		}
	}

    /**
     * Initialize plugin global vars.
     *
     * @return void
     */
    public function set_global_vars()
    {
        global $post, $es_settings, $es_property;

        // Set global plugin settings array.
        $es_settings = new Es_Settings_Container();

        // Set global property object.
        if ( ! empty( $post->post_type ) && 'properties' == $post->post_type && is_single() ) {
            $es_property = es_get_property( $post->ID );
        }
    }

    /**
     * Register plugin post types.
     *
     * @return void
     */
    public function register_post_types()
    {
    	global $es_settings;

    	$args = array(
            'label' => __( 'Property', 'es-plugin' ),
            'labels' => array(
                'name' => __( $es_settings->property_name, 'es-plugin' ),
            ),
            'public' => true,
            'show_in_menu' => false,
            'has_archive' => true,
            'supports' => array( 'title', 'author', 'excerpt', 'editor' ),
            'rewrite' => array(
                'slug' => 'property',
                'with_front' => false,
                'pages'      => true,
                'feeds'      => true,
                'ep_mask'    => EP_PERMALINK,
            ),
        );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'properties';
        }

        register_post_type( 'properties', $args );

	    $args = array(
		    'label' => __( 'Saved Search', 'es-plugin' ),
		    'labels' => array(
			    'name' => __( 'Saved Search', 'es-plugin' ),
		    ),
		    'public' => false,
		    'show_in_menu' => false,
		    'has_archive' => false,
		    'supports' => array( 'title' ),
		    'map_meta_cap' => true,
	    );

	    register_post_type( 'es_saved_search', $args );
    }

    /**
     * Register plugin taxonomies.
     *
     * @return void
     */
    public function register_taxonomies()
    {
        global $es_settings;

        $args = apply_filters( 'es_category_taxonomy_args', array(
            'labels' => array(
                'name' => __( 'Category', 'es-plugin' ),
                'singular_name' => __( 'Category', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_categories';
        }

        register_taxonomy( 'es_category', 'properties', $args );

        $args = apply_filters( 'es_status_taxonomy_args', array(
            'labels' => array(
                'name' => __( 'Status', 'es-plugin' ),
                'singular_name' => __( 'Status', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_statuses';
        }

        register_taxonomy( 'es_status', 'properties', $args );

        $args = apply_filters( 'es_type_taxonomy_args', array(
            'labels' => array(
                'name' => __( 'Type', 'es-plugin' ),
                'singular_name' => __( 'Type', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_types';
        }

        register_taxonomy( 'es_type', 'properties', $args );

        $args = apply_filters( 'es_feature_taxonomy_args', array(
            'labels' => array(
                'name' => __( 'Features', 'es-plugin' ),
                'singular_name' => __( 'Feature', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_features';
        }

        register_taxonomy( 'es_feature', 'properties', $args );

        $args = apply_filters( 'es_rent_period_args', array(
            'labels' => array(
                'name' => __( 'Rent period', 'es-plugin' ),
                'singular_name' => __( 'Rent period', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_rent_periods';
        }

        register_taxonomy( 'es_rent_period', 'properties', $args );

        $args = apply_filters( 'es_amenities_taxonomy_args', array(
            'labels' => array(
                'name' => __( 'Amenities', 'es-plugin' ),
                'singular_name' => __( 'Amenities', 'es-plugin' ),
            ),
        ) );

        if ( $es_settings->is_rest_support_enabled ) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = 'es_amenities';
        }

        register_taxonomy( 'es_amenities', 'properties', $args );

        register_taxonomy( 'es_labels', 'properties', apply_filters( 'es_labels_args', array(
            'labels' => array(
                'name' => __( 'Labels', 'es-plugin' ),
                'singular_name' => __( 'Label', 'es-plugin' ),
            ),
            'show_ui' => false,
        ) ) );

		global $es_settings;

		if ( ! empty( $es_settings->is_tags_enabled ) ) {
		    $args = apply_filters( 'es_tags_args', array(
                'labels' => array(
                    'name' => __( 'Tags', 'es-plugin' ),
                    'singular_name' => __( 'Tag', 'es-plugin' ),
                ),
                'public' => true,
                'rewrite'       => array(
                    'slug' => 'properties/tag',
                    'with_front' => false
                ),
            ) );

            if ( $es_settings->is_rest_support_enabled ) {
                $args['show_in_rest'] = true;
                $args['rest_base'] = 'es_tags';
            }

			register_taxonomy( 'es_tags', 'properties', $args );
		}
    }

    /**
     * Migrate new settings for estatik plugin.
     *
     * @return void
     */
    public function _migration()
    {
        global $wpdb;

	    $charset_collate = '';

	    if ( $wpdb->has_cap( 'collation' ) ) {
		    $charset_collate = $wpdb->get_charset_collate();
	    }

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

        /**
         * START MIGRATIONS FLOW.
         */

        if ( ! get_option( 'es_migration_0' ) ) {
	        // An array of Field names
	        $existing_columns = $wpdb->get_col("DESC {$wpdb->prefix}address_components", 0);

			if ( ! empty( $existing_columns ) ) {
				if ( ! in_array( 'locale', $existing_columns ) ) {
					$wpdb->query( "ALTER TABLE {$wpdb->prefix}address_components ADD locale VARCHAR(10)" );
				}

				update_option( 'es_migration_0', true );
			}
        }

        if ( ! get_option( 'es_migration_1' ) ) {
            $wpdb->query( "UPDATE {$wpdb->prefix}address_components SET locale='" . es_get_locale() . "'" );
            update_option( 'es_migration_1', true );
        }

        if ( ! get_option( 'es_migration_2' ) ) {

            $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'fbuilder_fields (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `label` VARCHAR(255) NOT NULL,
                `machine_name` VARCHAR(255) NOT NULL,
                `options` TEXT,
                `type` VARCHAR(20) NOT NULL,
                `tab` VARCHAR(40) NOT NULL,
                `entity` VARCHAR(255) NOT NULL,
                `formatter` VARCHAR(255),
                `values` TEXT,
                `section` TEXT,
                `rets_support` INT(1),
                `search_support` INT(1),
                PRIMARY KEY (id)
            ); ' . $charset_collate . ';';

            dbDelta( $sql );

            update_option( 'es_migration_2', true );
        }

        if ( ! get_option( 'es_migration_fix_tab_length' ) ) {
            $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields MODIFY tab VARCHAR(255) NOT NULL" );
            update_option( 'es_migration_fix_tab_length', 1 );
        }

	    if ( ! get_option( 'es_migration_3' ) ) {

		    $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'fbuilder_fields_order (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `section_machine_name` VARCHAR(255) NOT NULL,
                `field_machine_name` VARCHAR(255) NOT NULL,
                `order` INT(11),
                PRIMARY KEY (id)
            ); ' . $charset_collate . ';';

		    dbDelta( $sql );

		    $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'fbuilder_sections (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `label` VARCHAR(255) NOT NULL,
                `machine_name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
            ); ' . $charset_collate . ';';

		    dbDelta( $sql );

		    $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'fbuilder_sections_order (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `section_machine_name` VARCHAR(255) NOT NULL,
                `order` INT(11),
                PRIMARY KEY (id)
            ); ' . $charset_collate . ';';

		    dbDelta( $sql );

		    update_option( 'es_migration_3', true );
	    }

	    if ( ! get_option( 'es_migration_6' ) ) {
		    $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields ADD search_range_mode INT(1)" );
		    $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields ADD show_thumbnail INT(1)" );
		    $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_sections ADD show_tab INT(1)" );
		    update_option( 'es_migration_6', true );
	    }

	    /**
	     * Field builder fix.
	     */
	    if ( ! get_option( 'es_migration_fb_fix_1' ) ) {
		    $fields = Es_FBuilder_Helper::get_entity_fields( 'property', null, true );

		    if ( $fields ) {
			    $fields = array_filter( $fields, 'es_check_section' );

			    $i = 1;
			    foreach ( $fields as $id => $field ) {
				    if ( ! empty( $field ) ) {
					    $check_field = $wpdb->get_var( "SELECT id FROM " . $wpdb->prefix . "fbuilder_fields_order WHERE field_machine_name = '{$id}'" );

					    if ( ! $check_field ) {
						    $wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
							    'section_machine_name' => 'es-info',
							    'field_machine_name' => $id,
							    'order' => $i,
						    ) );

						    $i++;
					    }
				    }
			    }
		    }

		    update_option( 'es_migration_fb_fix_1', true );
	    }

	    if ( ! get_option( 'es_migration_fb_fields_tabs_id' ) ) {

		    $fields = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "fbuilder_fields WHERE entity='property'", ARRAY_A );

		    if ( $fields ) {
			    foreach ( $fields as $field ) {
				    $wpdb->update( $wpdb->prefix  . 'fbuilder_fields', array( 'tab' => $field['section'] ), array( 'id' => $field['id'] ) );
			    }
		    }

		    update_option( 'es_migration_fb_fields_tabs_id', true );
	    }

	    if ( ! get_option( 'es_migration_425634525209' ) ) {
		    // An array of Field names
		    $existing_columns = $wpdb->get_col("DESC {$wpdb->prefix}fbuilder_fields", 0);

		    if ( ! empty( $existing_columns ) ) {
			    if ( ! in_array( 'import_support', $existing_columns ) ) {
				    $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields ADD import_support INT(1)" );
			    }

			    update_option( 'es_migration_425634525209', true );
		    }
	    }

	    if ( ! get_option( 'es_fb_private_field_perms' ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields ADD visible_permission VARCHAR(100)" );
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_sections ADD visible_permission VARCHAR(100)" );

			update_option( 'es_fb_private_field_perms', true );
		}

	    if ( ! get_option( 'es_fb_private_field_permission' ) ) {
		    if ( $role = get_role( 'administrator' ) ) {
		    	$role->add_cap( 'es_fb_admins_field_visible' );
		    }
		    update_option( 'es_fb_private_field_permission', 1 );
	    }

	    if ( ! get_option( 'es_fb_field_range' ) ) {
		    $wpdb->query( "ALTER TABLE {$wpdb->prefix}fbuilder_fields ADD range_mode INT(1)" );

		    update_option( 'es_fb_field_range', true );
	    }

	    if ( ! get_option( 'es_role_permissions' ) ) {
	    	$role = get_role( 'administrator' );

	    	$caps = array(
	    		'es_view_calculated_units',
			    'es_delete_dm_item',
			    'es_save_dm_item',
			    'es_manage_fb',
			    'es_clone_posts'
		    );

	    	if ( $role ) {
                foreach ( $caps as $cap ) {
                    if ( ! $role->has_cap( $cap ) ) {
                        $role->add_cap( $cap );
                    }
                }

                update_option( 'es_role_permissions', 1 );
            }
	    }

	    if ( ! get_option( 'es_migration_call_for_price_fix' ) ) {

	    	$wpdb->query( "UPDATE $wpdb->postmeta SET meta_value = '0' WHERE meta_key = 'es_property_call_for_price' AND (meta_value != '1' OR meta_value IS NULL)" );

	    	update_option( 'es_migration_call_for_price_fix', 1 );
	    }

	    if ( ! get_option( 'es_role_admin_new_permissions' ) ) {
		    $role = get_role( 'administrator' );

		    $caps = array(
			    'es_manage_dashboard',
			    'es_manage_listings_page',
			    'es_manage_data_manager',
			    'es_manage_buyers',
			    'es_manage_settings',
			    'es_manage_upgrade',
			    'es_manage_demo',
			    'es_manage_migration',
		    );

		    if ( $role ) {
                foreach ( $caps as $cap ) {
                    if ( ! $role->has_cap( $cap ) ) {
                        $role->add_cap( $cap );
                    }
                }

                update_option( 'es_role_admin_new_permissions', 1 );
            }
	    }

	    if ( ! get_option( 'es_featured_properties_fix' ) ) {
	    	$ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type='properties' AND NOT EXISTS(SELECT post_id FROM {$wpdb->postmeta} WHERE post_id=ID and meta_key='es_property_featured') LIMIT 200" );

	    	if ( ! empty( $ids ) ) {
	    		foreach ( $ids as $id ) {
	    			update_post_meta( $id, 'es_property_featured', '' );
			    }
		    }

		    if ( ! $ids ) {
			    update_option( 'es_featured_properties_fix', 1 );
		    }
	    }

	    if ( ! get_option( 'es_description_temp_meta_set' ) ) {
	        global $wpdb;
	        $limit = 50;
	        $offset = get_option( 'es_description_temp_meta_set_offset', 0 );

	        $data = $wpdb->get_results( "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content<>'' AND post_type='properties' LIMIT {$offset}, {$limit}" );

	        if ( $data ) {
                foreach ( $data as $item ) {
                    update_post_meta( $item->ID, 'es_property_alternative_description', $item->post_content );
                    update_post_meta( $item->ID, 'es_post_content_copied', 1 );
                }

                update_option( 'es_description_temp_meta_set_offset', $offset + $limit );
            } else {
	            delete_option( 'es_description_temp_meta_set_offset' );
                update_option( 'es_description_temp_meta_set', 1 );
            }
        }

	    if ( ! get_option( 'es_description_copy_back' ) ) {
	        global $wpdb;

	        $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->postmeta} 
                WHERE meta_key='es_property_alternative_description' 
                AND meta_value<>''" );

	        if ( ! empty( $results ) ) {
	            foreach ( $results as $row ) {
	                if ( ! empty( $row->meta_value ) && get_post_meta( $row->post_id, '_elementor_edit_mode', true ) != 'builder' ) {
                        $wpdb->update( $wpdb->posts, array( 'post_content' => $row->meta_value ), array( 'ID' => $row->post_id ) );
                    }
                }
            }

            update_option( 'es_description_copy_back', 1 );
        }
    }
}
