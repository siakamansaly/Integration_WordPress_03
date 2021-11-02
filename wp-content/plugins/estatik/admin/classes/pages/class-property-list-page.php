<?php

/**
 * Class Es_Property_List_Page
 *
 * Customize properties post type admin list page.
 */
class Es_Property_List_Page extends Es_Object
{
    /**
     * Return property post type name.
     *
     * @return string
     */
    public function get_post_type()
    {
        $property = es_get_property( null );
        return $property::get_post_type_name();
    }

    /**
     * Initialize actions.
     *
     * @return void
     */
    public function actions()
    {
        // Add action for render custom columns values.
        add_action( 'manage_' . $this->get_post_type() . '_posts_custom_column' , array( $this, 'add_columns_values' ), 10, 2 );
        // Enqueue styles for our page.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        // Enqueue scripts for our page.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        // Add filter block to the properties list page.
        add_action( 'manage_posts_extra_tablenav', array( $this, 'add_list_filter' ) );
        // Submit listings actions form.
        add_action( 'init', array( $this, 'manage_form_submit' ) );
    }

    /**
     * Initialize filters.
     *
     * @return void
     */
    public function filters()
    {
        // Add custom columns to the list table.
        add_filter( 'manage_' . $this->get_post_type() . '_posts_columns', array( $this, 'add_columns' ) );
        // Remove standard filters from our content type.
        add_filter( 'views_edit-' . $this->get_post_type(), array( $this, 'remove_views' ) );
        // Remove bulk actions from our content type.
        add_filter( 'bulk_actions-edit-' . $this->get_post_type(), array( $this, 'remove_views' ) );
        // Parse post query for customize properties filter.
        add_filter( 'parse_query', array( $this, 'parse_query' ), 10, 1 );
        // Global js variables.
        add_filter( 'es_global_js_variables', array( $this, 'add_js_variables' ), 10, 1 );

        add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
        add_filter( 'get_the_excerpt', array( $this, 'remove_excerpt' ), 10, 1 );
    }

    /**
     * @param $exerpt
     * @return string
     */
    public function remove_excerpt( $exerpt )
    {
        global $post_type;

        if ( $post_type == $this->get_post_type() && is_archive() ) {
            return '';
        }

        return $exerpt;
    }

    /**
     * Add JS variables for this page.
     *
     * @param $data
     * @return mixed
     */
    public function add_js_variables( $data ) {
        $data['html']['logo'] = es_get_logo();

        return $data;
    }

    /**
     * @param $actions
     * @param $post
     * @return array
     */
    public function post_row_actions( $actions, $post ) {
        if ( $post->post_type == $this->get_post_type() ) {
            // Customize edit link.
            ob_start();
            edit_post_link( '<i class="fa fa-pencil" aria-hidden="true"></i>' );
            $actions['edit'] = ob_get_clean();

            // Customize remove link.
            $actions['trash'] = '<a href="' . get_delete_post_link( $post->ID, '', true ) . '">
                <i class="fa fa-trash" aria-hidden="true"></i></a>';

            // Customize view link.
            $actions['view'] = '<a href="' . get_the_permalink( $post->ID ) . '" target="_blank">
                <i class="fa fa-eye" aria-hidden="true"></i></a>';

            unset( $actions['inline hide-if-no-js'] );
        }
        return $actions;
    }

    /**
     * Render properties list filter.
     *
     * @return void.
     */
    public function add_list_filter( $which ) {
        if ( $which == 'top' ) {
            $filter = filter_input( INPUT_GET, 'es_filter', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
            include es_locate_template( 'property/filter.php', 'admin', 'es_admin_list_filter_path' );
        }
    }

    /**
     * Add custom columns for property post type.
     *
     * @param $columns
     *    Array of name => label columns.
     * @return mixed|array
     *    Return customized columns array.
     */
    public function add_columns( $columns ) {
        // Unset left columns.
        unset($columns['author'], $columns['date']);

        // Add post ID column.
        $columns = static::push_column( array( 'post_id' => __( 'Property ID', 'es-plugin' ) ),  $columns, 1 );
        // Add image column.
        $columns = static::push_column( array( 'thumbnail' => __( 'Image', 'es-plugin' ) ),      $columns, 2 );
        // Add date column on new position with new label.
        $columns = static::push_column( array( 'date'      => __( 'Date added', 'es-plugin' ) ), $columns, 4 );
        // Add address column.
        $columns = static::push_column( array( 'address'   => __( 'Address', 'es-plugin' ) ),    $columns, 5 );
        // Add category column.
        $columns = static::push_column( array( 'category'  => __( 'Category', 'es-plugin' ) ),   $columns, 6 );
        // Add type column.
        $columns = static::push_column( array( 'type'      => __( 'Type', 'es-plugin' ) ),       $columns, 7 );
        // Status column.
        $columns = static::push_column( array( 'status'    => __( 'Status', 'es-plugin' ) ),     $columns, 8 );

        return $columns;
    }

    /**
     * Render value for specific column.
     *
     * @param $column
     *    Column name.
     * @param $post_id
     *    WP_Post ID.
     *
     * @return void
     */
    public function add_columns_values( $column, $post_id )
    {
    	$property = es_get_property( $post_id );

        if ( 'thumbnail' == $column ) {
	        echo "<div class='es-property__image-wrap'>";
	        es_the_post_thumbnail( static::table_image_size(), true );

            if ( ! empty($property->gallery[0]) && ! is_wp_error($property->gallery[0]) && count( $property->gallery ) ) {
                echo "<div class='es-image__count-wrap'>" . count( $property->gallery ) . "</div>";
            }
	        echo "</div>";
        }

        if ( 'type' == $column )
            es_the_types();

        if ( 'status' == $column )
            es_the_status_list();

        if ( 'category' == $column )
            es_the_categories();

        if ( 'address' == $column ) {
            $property = es_get_property( $post_id );
            echo $property->address;
        }

        if ( 'post_id' == $column )
            echo $post_id;
    }

    /**
     * Customize admin properties list filter.
     *
     * @param WP_Query $wp_query
     * @return void
     */
    public function parse_query( $wp_query ) {
        $property = es_get_property( null );

        // Get filter data.
        $filter = filter_input( INPUT_GET, 'es_filter', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

        // Flag for reset wp query.
        $flag = false;

        // Filter properties using post ID.
        if ( ! empty( $filter['property_id'] ) ) {
            $wp_query->set( 'post__in', array( $filter['property_id'] ) );

            return;
        } elseif ( ! empty( $filter['address'] ) ) {
            global $wpdb;
            $ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='es_property_address' AND meta_value='%s'", $filter['address'] ) );
            $ids = $ids ? $ids : array();

            // Create array from address string using delimiters.
            if ( empty( $ids ) && $output = preg_split( "/[,\s]/", $filter['address'] ) ) {

                foreach ( $output as $key => $address_part ) {
                    if ( empty( $address_part ) ) continue;
                    $ids = array_merge( $ids, $property::find_by_address( $address_part ) );
                }
            }

            if ( ! empty( $ids ) ) {
                $wp_query->set( 'post__in', $ids );
            } else {
                $flag = true;
            }
        }

        // Filter properties by taxonomies.
        if ( ! empty( $filter['tax'] ) ) {
            foreach ( $filter['tax'] as $tax => $term_id ) {
                if ( empty( $term_id ) ) continue;

                if ( taxonomy_exists( $tax ) ) {
                    $taxFilter[] = array(
                        'taxonomy' => $tax,
                        'terms' => $term_id,
                    );
                }
            }
            if ( isset( $taxFilter ) ) {
                $wp_query->set( 'tax_query', $taxFilter );
                $flag = false;
            }
        }

	    if ( ! empty( $filter['date_added'] ) ) {
		    $wp_query->set( 'date_query', array(
			    array(
				    'year'  => date('Y', strtotime( $filter['date_added'] ) ),
				    'month' => date('m', strtotime( $filter['date_added'] ) ),
				    'day'   => date('d', strtotime( $filter['date_added'] )),
			    ),
		    ) );
	    }

        // Reset wp query.
        if ( $flag ) {
            $wp_query->set( 'post__in', array( -1 ) );
        }
    }

    /**
     * Manage properties buttons handler.
     *
     * @return void
     */
    public function manage_form_submit()
    {
    	$nonce_name = 'es_manage_properties_form';
    	$nonce = sanitize_key( filter_input( INPUT_GET, 'es_manage_properties_form' ) );

        // If valid request.
        if ( ! empty( $_GET['es-action'] ) && ! empty( $_GET['post'] ) && wp_verify_nonce( $nonce, $nonce_name ) ) {

	        $posts_ids = array_map( 'intval', $_GET['post'] );

	        $action = sanitize_text_field( $_GET['es-action'] );

            // Check for correct action.
            switch( $action ) {
                // Copy selected posts.
                case 'copy':

                	if ( current_user_can( 'es_clone_posts' ) ) {
		                array_walk( $posts_ids, function( $post_id ) {
			                $clone = new Es_Post_Duplicate( $post_id );
			                $clone->makeClone();
		                } );
	                }
                    break;

                // Delete selected posts.
                case 'delete':
                    array_walk( $posts_ids, function( $post_id ) {
                    	if ( current_user_can( 'delete_post', $post_id ) ) {
		                    wp_delete_post( $post_id, true );
	                    }
                    } );
                    break;

                // Publish selected posts.
                case 'publish':
                    array_walk( $posts_ids, function( $post_id ) {
                    	if ( current_user_can( 'publish_post', $post_id ) ) {
		                    wp_update_post( array( 'ID' => $post_id, 'post_status' => 'publish' ) );
	                    }
                    } );
                    break;

                // Unpublish selected posts.
                case 'unpublish':
	                array_walk( $posts_ids, function( $post_id ) {
		                if ( current_user_can( 'edit_post', $post_id ) ) {
			                wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
		                }
	                } );
                    break;
            }

            wp_redirect( es_admin_property_list_uri() );
            exit;
        }
    }

    /**
     * Register and enqueue styles for our page.
     *
     * @return void
     */
    public function enqueue_styles()
    {
        wp_register_style( 'es-admin-property-list-style', ES_ADMIN_CUSTOM_STYLES_URL . 'property-list.css' );
        wp_enqueue_style( 'es-admin-property-list-style' );
        wp_enqueue_style( 'jquery-ui' );
    }

    /**
     * Register and enqueue scripts for our page.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_register_script( 'es-admin-property-list-style', ES_ADMIN_CUSTOM_SCRIPTS_URL . 'property-list.js', array ( 'jquery', 'es-select2-script', 'es-popup-script' ) );
        wp_enqueue_script( 'es-admin-property-list-style' );

        wp_enqueue_script( 'jquery-ui-datepicker' );
    }

    /**
     * Remove standard filters from our custom post type list page.
     *
     * @return array
     *    Empty array.
     */
    public function remove_views()
    {
        return array();
    }

    /**
     * Return image size for thumbnail column.
     *
     * @see add_image_size()
     *
     * @return mixed|string
     */
    protected static function table_image_size()
    {
        return apply_filters( get_post_type() . '_list_table_image_size', 'thumbnail' );
    }
}
