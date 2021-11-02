<?php

class Es_Property_Metabox extends Es_Object
{
    /**
     * Add actions for property metabox.
     *
     * @return void
     */
    public function actions()
    {
        add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_scripts' ) );
        add_action( 'add_meta_boxes', array( $this, 'build' ) );
        add_action( 'es_after_property_metabox_tab_content', array( $this, 'render_admin_map' ) );
    }

    /**
     * Add filters for property metabox.
     *
     * @return void
     */
    public function filters() {
        add_filter( 'wp_prepare_attachment_for_js', array( $this, 'prepare_attachment_js' ), 10, 3 );
    }

    /**
     * Add scripts for property metabox.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        $custom = 'admin/assets/js/custom/';

        wp_enqueue_media();

        wp_register_script( 'es-admin-media-script', ES_PLUGIN_URL . $custom . 'media.js', array( 'jquery' ), false, $in_footer = true );
        wp_enqueue_script( 'es-admin-media-script' );
    }

    /**
     * Customize response for wp media gallery.
     *
     * @param $response
     * @param $attachment
     * @param $meta
     * @return mixed
     */
    public function prepare_attachment_js( $response, $attachment, $meta )
    {
        if ( has_image_size( 'thumbnail' ) ) {
            $attachment_url = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );

            $response['sizes'][ 'thumbnail' ] = array(
                'height'        => $attachment_url[2],
                'width'         => $attachment_url[1],
                'url'           => $attachment_url[0],
                'orientation'   => $attachment_url[2] > $attachment_url[1] ? 'portrait' : 'landscape',
            );
        }

        return $response;
    }

    /**
     * Initialize property data metabox.
     *
     * @see add_meta_box
     */
    public function build()
    {
        add_meta_box(
            'es-property-data', __( 'Property data', 'es-plugin' ), array( 'Es_Property_Metabox', 'render' ),
            Es_Property::get_post_type_name()
        );
    }

    /**
     * Render property metabox.
     *
     * @return void
     */
    public static function render()
    {
        if ( $tabs = static::get_tabs() ) {
            $template = es_locate_template( 'property/metabox.php', 'admin', 'es_property_metabox_template_path' );
            include ( $template );
        }
    }

    /**
     * Return property metabox tabs.
     *
     * @return array
     */
    public static function get_tabs() {
        $id = get_the_ID();
        $elementor_editor_mode = es_is_elementor_builder_enabled( $id );

        $def_sections = array(
            'es-description' => array( 'label' => __( 'Description', 'es-plugin' ) ),
            'es-info' => array( 'label' => __( 'Basic information', 'es-plugin' ) ),
            'es-map' =>    array( 'label' => __( 'Address', 'es-plugin' ) ),
            'es-media' =>      array( 'label' => __( 'Media', 'es-plugin' ) ),
        );

        if ( ! $elementor_editor_mode ) {
            unset( $def_sections['es-description'] );
        }

        return apply_filters( 'es_property_metabox_tabs', $def_sections );
    }

    /**
     * Render wrapper for google map.
     *
     * @return void
     */
    public function render_admin_map( $id )
    {
        global $es_settings;

        if ( 'es-map' == $id && ! empty( $es_settings->google_api_key ) ) {
            ob_start(); ?>
            <div id="es-property-map" style="height:500px; width: 100%;"></div>
            <?php echo apply_filters( 'es_admin_property_map', ob_get_clean() );
        }
    }

	/**
	 * Check is tab has content.
	 *
	 * @param $id
	 *    Property metabox tab ID.
	 * @param $fields
	 *    Property fields list. @see Es_Property::get_fields()
	 *
	 * @return bool
	 */
	public static function tab_has_content( $id, $fields = false ) {
		$fields = $fields ? $fields : Es_Property::get_fields();

		if ( $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( ! empty( $field['tab'] ) && $field['tab'] == $id ) {
					return true;
				}
			}
		}

		return false;
	}
}
