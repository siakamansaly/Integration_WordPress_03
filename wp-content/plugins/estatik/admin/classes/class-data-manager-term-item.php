<?php

/**
 * Class Es_Data_manager_Item.
 */
class Es_Data_Manager_Term_Item extends Es_Data_Manager_Item
{
    /**
     * @var string Taxonomy machine name.
     */
    protected $_taxonomy;
    /**
     * @var string Data manager item template path.
     */
    protected $_template_path = 'data-manager/term-item.php';

    /**
     * Es_Data_manager_Item constructor.
     *
     * @param $taxonomy
     * @param array $options
     */
    public function __construct( $taxonomy, array $options = array() ) {
        parent::__construct( false, false, $options );
        $this->_taxonomy = get_taxonomy( $taxonomy );
    }

    /**
     * Render Data manager item.
     *
     * @return void
     */
    public function render() {
        if ( ! empty( $this->_taxonomy ) ) {
            parent::render();
        }
    }

    /**
     * Return data manager item values.
     *
     * @return array|int|null|WP_Error
     */
    public function getItems() {
        $items = ! empty( $this->_taxonomy ) ?
            get_terms( array( 'taxonomy' => $this->_taxonomy->name, 'hide_empty' => false ) ) : null;

        return apply_filters( 'es_data_manager_get_items', $items, $this );
    }

    /**
     * Save new term to the taxonomy using wp ajax.
     *
     * @return void
     */
    public static function save() {
        // Nonce field name.
        $nonce = 'es_add_data_manager_term';

        // If valid ajax request.
        if ( check_ajax_referer( $nonce, $nonce ) && current_user_can( 'es_save_dm_item' ) ) {
            // Insert term to the taxonomy.
            $result = wp_insert_term( sanitize_text_field( $_POST['item_name'] ), sanitize_key( $_POST['taxonomy'] ) );

            // If term successfully added.
            if ( is_array( $result ) ) {
                $response = array( 'message' => __( 'Item is successfully created.', 'es-plugin' ), 'status' => 'success', 'item' => $result['term_taxonomy_id'] );

            // If something was wrong and we have an wp error.
            } else if ( $result instanceof WP_Error ) {
                $response = array( 'message' => $result->get_error_message(), 'status' => 'error' );

            // Another crash error.
            } else {
                $response = array( 'message' => __( 'Item doesn\'t create. Please contact support.', 'es-plugin' ), 'status' => 'warning' );
            }
        // If request isn't valid.
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }

        wp_die( json_encode( $response ) );
    }

    /**
     * Remove term from taxonomy.
     *
     * @return void
     */
    public static function remove() {
        // If valid ajax request.
        if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_delete_dm_item' ) && ! empty( $_POST['id'] ) ) {
            $term = get_term( intval( $_POST['id'] ) );

            if ( $term instanceof WP_Term ) {

                // Remove term if exists.
                $result = wp_delete_term( $term->term_id, $term->taxonomy );

                // If all is ok.
                if ( $result && ! $result instanceof WP_Error ) {
                    $response = array( 'message' => __( 'Item is successfully deleted.', 'es-plugin' ), 'status' => 'success' );

                // If something was wrong.
                } else if ( $result instanceof WP_Error ) {
                    $response = array( 'message' => $result->get_error_message(), 'status' => 'error' );

                // Another crash error
                } else {
                    $response = array( 'message' => __( 'Item doesn\'t delete. Please contact support.', 'es-plugin' ), 'status' => 'warning' );
                }
            // If term doesn't exists.
            } else {
                $response = array( 'message' => __( 'Item doesn\'t exists.', 'es-plugin' ), 'status' => 'warning' );
            }
        } else {
            $response = array( 'message' => __( 'Invalid ajax request.', 'es-plugin' ), 'status' => 'error' );
        }
        wp_die( json_encode ( $response ) );
    }
}
