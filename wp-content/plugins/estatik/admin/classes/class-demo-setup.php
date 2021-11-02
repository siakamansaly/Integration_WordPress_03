<?php

/**
 * Class Es_Demo_Setup
 */
class Es_Demo_Setup extends Es_Object {

    /**
     * @inheritdoc
     */
    public function actions() {
        if ( ! defined( 'DOING_AJAX' ) ) {
            add_action( 'init', array( 'Es_Demo_Setup', 'setup_demo' ) );
        }

        add_action( 'wp_ajax_es_demo_setup', array( 'Es_Demo_Setup', 'ajax_handler' ) );
        parent::actions();
    }

    /**
     * @return void
     */
    public static function setup_demo() {
        $nonce_name = 'es_demo_setup';
        $nonce = sanitize_key( filter_input( INPUT_POST, $nonce_name ) );

        if ( $nonce &&  wp_verify_nonce( $nonce, $nonce_name ) ) {
            global $es_settings;

            $es_settings = $es_settings ? $es_settings : new Es_Settings_Container();

            $data = $_POST;

            if ( ! empty( $data['es_demo']['keys'] ) ) {
                global $es_settings;

                $es_settings = $es_settings ? $es_settings : new Es_Settings_Container();

                foreach ( $data['es_demo']['keys'] as $key => $value ) {
                    $es_settings->saveOne( sanitize_key( $key ), sanitize_text_field( $value ) );
                }
            }

            if ( ! empty( $data['es_demo']['page'] ) ) {
                $demo_pages = self::get_pages_list();

                foreach ( $data['es_demo']['page'] as $key ) {
                    $postarr = array(
                        'post_title' => $demo_pages[$key]['title'],
                        'post_content' => $demo_pages[$key]['content'],
                        'post_name' => sanitize_title( $demo_pages[$key]['title'] ),
                        'post_status' => 'publish',
                        'post_type' => 'page',
                    );

                    $post_id = wp_insert_post( $postarr, true );

                    if ( ! is_wp_error( $post_id ) && $post_id ) {
                        $es_settings->saveOne( $key, $post_id );
                    }
                }
            }

            wp_redirect( es_get_demo_finish_url() ); die;
        }
    }

    /**
     * @return void
     */
    public static function ajax_handler() {

    	$action = sanitize_key( filter_input( INPUT_POST, 'action' ) );

        if ( $action == 'es_demo_setup' ) {
            if ( ! empty( $_POST['es_demo'] ) ) {

            	$messages = array();

                $data = wp_parse_args( $_POST, array(
                	'messages' => '',
	                'start_setup' => '',
	                'es_demo' => '',
	                'pages_imported' => '',
	                'progress' => '',
	                'exclude' => array(),
	                'featured_listing' => '',
	                'done' => '',
                ) );

                global $es_settings;

                $es_settings = $es_settings ? $es_settings : new Es_Settings_Container();

                unset( $data['messages'] );

                if ( empty( $data['start_setup'] ) ) {
                    $messages['success'][] = __( 'Start setup data.', 'es-plugin' );
                    $data['start_setup'] = true;
                    $data['messages'] = $messages;

                    wp_die( json_encode( $data ) );
                }

                if ( ! empty( $data['es_demo']['keys'] ) ) {
                    global $es_settings;

                    $es_settings = $es_settings ? $es_settings : new Es_Settings_Container();

                    foreach ( $data['es_demo']['keys'] as $key => $value ) {
                        $es_settings->saveOne( $key, $value );
                    }
                }

                if ( ! empty( $data['es_demo']['page'] ) && empty( $data['pages_imported'] ) ) {
                    $demo_pages = self::get_pages_list();

                    foreach ( $data['es_demo']['page'] as $key ) {
                        $postarr = array(
                            'post_title' => sanitize_title( $demo_pages[$key]['title'] ),
                            'post_content' => $demo_pages[$key]['content'],
                            'post_name' => sanitize_title( $demo_pages[$key]['title'] ),
                            'post_status' => 'publish',
                            'post_type' => 'page',
                        );

                        $post_id = wp_insert_post( $postarr, true );

                        if ( $post_id instanceof WP_Error ) {
                            $messages['error'][] = $post_id->get_error_message();
                        } else {
                            if ( ! $post_id instanceof WP_Error ) {
                                $es_settings->saveOne( $key, $post_id );
                            }
                            $messages['success'][] = sprintf( __( 'Created %s page.', 'es-plugin' ), $demo_pages[$key]['title'] );
                        }
                    }

                    $data['pages_imported'] = true;
                    $data['messages'] = $messages;
                    $data['progress'] = 40;

                    wp_die( json_encode( $data ) );
                }

                $exclude = ! empty( $data['exclude'] ) ? $data['exclude'] : array();
                $listings = self::get_listings_data();

                if ( ! empty( $data['es_demo']['demo'] ) && count( $exclude ) != count( $listings ) ) {

                    add_filter( 'intermediate_image_sizes', 'es_demo_filter_image_sizes' );

                    $featured_listing = $es_settings->single_featured_listing;

                    foreach ( $listings as $id => $item ) {

                        if ( in_array( $id, $exclude ) ) continue;

                        $data['exclude'][] = $id;

                        $post_id = wp_insert_post( $item['system'], true );

                        if ( $post_id instanceof WP_Error ) {
                            $messages['error'][] = $post_id->get_error_message();
                        } else {

                            if ( $featured_listing && empty( $data['featured_listing'] ) ) {
                                wp_update_post( array(
                                    'ID' => $featured_listing,
                                    'post_content' => '[es_single ID="' . $post_id . '"]'
                                ) );
                                $data['featured_listing'] = true;
                            }

                            $property = es_get_property( $post_id );
                            $gallery = array();

                            if ( ! empty( $item['gallery'] ) ) {
                                foreach ( $item['gallery'] as $image ) {
                                    $file = array();
                                    $image_path = $image;

                                    // If image exists.
                                    if ( ! empty( $image ) ) {
                                        $file['name'] = basename( $image_path );
                                        $file['tmp_name'] = download_url( $image_path );

                                        if ( ! is_wp_error( $file['tmp_name'] ) ) {
                                            $attachment_id = media_handle_sideload( $file, $post_id );

                                            if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                                                $gallery[] = $attachment_id;
                                            } else {
                                                $messages['error'][] = $attachment_id->get_error_message();
                                            }
                                        } else {
                                            $messages['error'][] = $file['tmp_name']->get_error_message();
                                        }
                                    }
                                }
                                $item['meta']['gallery'] = $gallery;
                            }

                            if ( ! empty( $item['meta']['address'] ) ) {
                                $address_components = ES_Address_Components::get_google_components( $item['meta']['address'] );
                                if ( ! empty( $address_components->results[0]->address_components ) ) {
                                    $item['meta']['address_components'] = json_encode( $address_components->results[0]->address_components );
                                    $item['meta']['longitude'] = $address_components->results[0]->geometry->location->lng;
                                    $item['meta']['latitude'] = $address_components->results[0]->geometry->location->lat;
                                }
                            }

                            $property->save_fields( $item['meta'] );
                            $property->save_terms( $item['taxonomies'] );

                            $messages['success'][] = sprintf( __( 'Property %s successfully imported.', 'es-plugin' ), $item['system']['post_title'] );
                        }
                        break;
                    }

                    if ( count( $exclude ) == count( $listings ) ) {
                        $data['done'] = 1;
                        $data['progress'] = 100;
                        $messages['success'][] = __( 'Setup finished.', 'es-plugin' );
                        $data['messages'] = $messages;

                        wp_die( json_encode ( $data ) );
                    } else {
                        $progress = ! empty( $data['progress'] ) ? $data['progress'] : 0;
                        $data['progress'] = $progress + ( ( 100 - $progress ) / count( $listings ) );
                        $data['messages'] = ! empty( $messages ) ? $messages : array();
                        wp_die( json_encode ( $data ) );
                    }
                }

                $data['done'] = 1;
                $data['progress'] = 100;
                $messages['success'][] = __( 'Setup finished.', 'es-plugin' );
                $data['messages'] = $messages;

                wp_die( json_encode( $data ) );
            }
        }
    }

    /**
     * @return array
     */
    public static function get_listings_data() {
        return array(
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.',
                    'post_title' => '1511 Ridge Ave',
                    'post_excerpt' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
                    'post_name' => '1511-ridge-ave',
                ),
                'meta' => array(
                    'price' => 350,
                    'price_note' => 'Available now',
                    'featured' => '',
                    'foreclosure' => '',
                    'hot' => '',
                    'open-house' => 1,
                    'bedrooms' => 3,
                    'bathrooms' => 1,
                    'floors' => 1,
                    'area' => 80,
                    'area_unit' => 'sq_m',
                    'lot_size' => 100,
                    'lot_size_unit' => 'sq_m',
                    'year_built' => 2003,
                    'address' => '1511 Ridge Ave, Evanston, IL 60201, USA',
                    'alternative_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.'
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For rent',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'House',
                    ),
                    'es_rent_period' => array(
                        'Per month'
                    ),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/12.jpeg',
                    ES_PLUGIN_URL . 'demo/11.jpeg',
                    ES_PLUGIN_URL . 'demo/15.jpg',
                )
            ),
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.',
                    'post_title' => '1052 Alder Ln',
                    'post_name' => sanitize_title( '1052 Alder Ln' ),
                ),
                'meta' => array(
                    'price' => 300,
                    'price_note' => 'Available now',
                    'featured' => '',
                    'foreclosure' => '',
                    'hot' => 1,
                    'open-house' => '',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'floors' => 1,
                    'area' => 0.5,
                    'area_unit' => 'sq_ft',
                    'lot_size' => 1,
                    'lot_size_unit' => 'sq_ft',
                    'year_built' => 2018,
                    'address' => '1052 Alder Ln, Naperville, IL 60540, USA',
                    'alternative_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.',
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For rent',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'House',
                    ),
                    'es_rent_period' => array(
                        'Per month'
                    ),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/15.jpg',
                    ES_PLUGIN_URL . 'demo/12.jpeg',
                    ES_PLUGIN_URL . 'demo/11.jpeg',
                )
            ),
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'post_title' => '310 S Federal St, IL 60604, USA',
                    'post_name' => sanitize_title( '310 S Federal St, IL 60604, USA' ),
                ),
                'meta' => array(
                    'price' => 300000,
                    'price_note' => 'Est. Mortgage $52,754/mo',
                    'featured' => 1,
                    'foreclosure' => '',
                    'hot' => 1,
                    'open-house' => '',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'floors' => 1,
                    'area' => 1000,
                    'area_unit' => 'sq_ft',
                    'lot_size' => 5200,
                    'lot_size_unit' => 'sq_ft',
                    'year_built' => 1998,
                    'address' => '310 S Federal St, Chicago, IL 60604, USA',
                    'alternative_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For rent',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'Apartment',
                    ),
                    'es_rent_period' => array(),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/48963413.jpg',
                    ES_PLUGIN_URL . 'demo/Gorki-Apartment.jpg',
                )
            ),
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'post_title' => '235 W State St',
                    'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'post_name' => sanitize_title( '235 W State St' ),
                ),
                'meta' => array(
                    'price' => 120000,
                    'price_note' => 'Est. Mortgage $52,754/mo',
                    'featured' => 1,
                    'foreclosure' => '',
                    'hot' => 1,
                    'open-house' => '',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'floors' => 1,
                    'area' => 1000,
                    'area_unit' => 'sq_ft',
                    'lot_size' => 5200,
                    'lot_size_unit' => 'sq_ft',
                    'year_built' => 1998,
                    'address' => '235 W State St, Sycamore, IL 60178, USA',
                    'alternative_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For sale',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'Apartment',
                    ),
                    'es_rent_period' => array(),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/Gorki-Apartment.jpg',
                    ES_PLUGIN_URL . 'demo/48963413.jpg',
                )
            ),
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'post_title' => 'Prairie Trail, IL, USA',
                    'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'post_name' => sanitize_title( '235 W State St' ),
                ),
                'meta' => array(
                    'price' => 120000,
                    'price_note' => 'Est. Mortgage $52,754/mo',
                    'featured' => 1,
                    'foreclosure' => '',
                    'hot' => 1,
                    'open-house' => '',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'floors' => 1,
                    'area' => 1000,
                    'area_unit' => 'sq_ft',
                    'lot_size' => 5200,
                    'lot_size_unit' => 'sq_ft',
                    'year_built' => 1998,
                    'address' => 'Prairie Trail, Crystal Lake, IL 60014, USA',
                    'alternative_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For sale',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'Apartment',
                    ),
                    'es_rent_period' => array(),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/apto1-2.jpg',
                    ES_PLUGIN_URL . 'demo/Gorki-Apartment.jpg',
                    ES_PLUGIN_URL . 'demo/48963413.jpg',
                )
            ),
            array(
                'system' => array(
                    'post_type' => Es_Property::get_post_type_name(),
                    'post_status' => 'publish',
                    'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'post_title' => '120 S Grove Ave',
                    'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'post_name' => sanitize_title( '120 S Grove Ave' ),
                ),
                'meta' => array(
                    'price' => 120000,
                    'price_note' => 'Optional price note',
                    'featured' => 1,
                    'foreclosure' => '',
                    'hot' => 1,
                    'open-house' => '',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'floors' => 1,
                    'area' => 0.8,
                    'area_unit' => 'sq_ft',
                    'lot_size' => 1.2,
                    'lot_size_unit' => 'sq_ft',
                    'year_built' => 1998,
                    'address' => '120 S Grove Ave, Elgin, IL 60120, USA',
                    'alternative_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                ),
                'taxonomies' => array(
                    'es_category' => array(
                        'For sale',
                    ),
                    'es_feature' => array(
                        'cooling',
                        'garage',
                        'garden',
                        'heating',
                        'parking',
                        'swimming pool'
                    ),
                    'es_status' => array(
                        'Open',
                    ),
                    'es_type' => array(
                        'Office',
                    ),
                    'es_rent_period' => array(),
                    'es_amenities' => array(
                        'dishwasher',
                        'iron',
                        'microwave',
                        'oven',
                        'tv',
                        'wifi',
                    ),
                ),
                'gallery' => array(
                    ES_PLUGIN_URL . 'demo/apto1.jpg',
                    ES_PLUGIN_URL . 'demo/apto1-1.jpg',
                    ES_PLUGIN_URL . 'demo/room4.jpg',
                )
            )
        );
    }

    /**
     * @return array
     */
    public static function get_pages_list() {
        return array(

	        'list_layout' => array(
		        'title' => __( 'List layout', 'es-plugin' ),
		        'content' => "[es_my_listing layout='list']",
	        ),

	        'login_page_id' => array(
		        'title' => __( 'Log In', 'es-plugin' ),
		        'content' => '[es_login]',
	        ),

	        'grid_layout' => array(
		        'title' => __( 'Grid layout', 'es-plugin' ),
		        'content' => '[es_my_listing layout="3_col"]',
	        ),

	        'reset_password_page_id' => array(
		        'title' => __( 'Reset password', 'es-plugin' ),
		        'content' => '[es_reset_pwd]',
	        ),

	        'advanced_search_slideshow_page_id' => array(
		        'title' => __( 'Advanced Search & Slideshow', 'es-plugin' ),
		        'content' => '[es_property_slideshow show="all" slider effect="horizontal" slides_to_show="3" show_arrows=1]
[es_search_form title="Advanced Search" layout="horizontal" fields="price,address,country,state,city,es_category,es_type,es_status,bedrooms,bathrooms,area,es_amenities,es_feature"]
[es_my_listing]',
	        ),

	        'prop_management_page_id' => array(
		        'title' => __( 'Front-end management', 'es-plugin' ),
		        'content' => '[es_prop_management]',
		        'disabled' => true,
	        ),

	        'user_profile_page_id' => array(
		        'title' => __( 'My Profile', 'es-plugin' ),
		        'content' => '[es_profile]',
	        ),

	        'agents_list_page_id' => array(
		        'title' => __( 'Agents list', 'es-plugin' ),
		        'content' => '[es_agents]',
		        'disabled' => true,
	        ),

	        'registration_page_id' => array(
		        'title' => __( 'Registration', 'es-plugin' ),
		        'content' => '[es_register]',
	        ),

	        'map_view' => array(
		        'title' => __( 'Map view', 'es-plugin' ),
		        'content' => '[es_property_map]',
		        'disabled' => true,
	        ),

	        'search_page_id' => array(
		        'title' => __( 'Search Results', 'es-plugin' ),
		        'content' => '[es_search]',
		        'checked' => true,
	        ),

        );
    }
}
