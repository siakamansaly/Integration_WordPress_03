<?php

/**
 * Class Es_Data_Manager_Label_Item
 */
class Es_Data_Manager_Label_Item extends Es_Data_Manager_Term_Item
{
	/**
	 * Es_Data_Manager_Label_Item constructor.
	 * @param array $options
	 */
	public function __construct(array $options = array() )
	{
		$this->_template_path = 'data-manager/label-item.php';
		parent::__construct( 'es_labels', $options );
	}

	/**
	 * Overridden save term method.
	 *
	 * @return void.
	 */
	public static function save()
	{
		// Nonce field name.
		$nonce = 'es_add_data_manager_label';

		// If valid ajax request.
		if ( check_ajax_referer( $nonce, $nonce ) && current_user_can( 'es_save_dm_item' ) ) {
			// Insert term to the taxonomy.
            $title = sanitize_text_field( $_POST['item_name'] );
			$result = wp_insert_term( $title, sanitize_key( $_POST['taxonomy'] ) );

			// If term successfully added.
			if ( is_array( $result ) && ! is_wp_error( $result ) ) {

				update_term_meta( $result['term_id'], 'es_color', es_get_default_label_color() );

				$response = array( 'message' => __( 'Item is successfully created.', 'es-plugin' ),
				                   'status' => 'success',
				                   'item' => $result['term_taxonomy_id'],
				                   'content' => self::get_item_markup( $result['term_id'] )
				);

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
	 * @param $term_id
	 * @return bool|string
	 */
	private static function get_item_markup( $term_id ) {

		$term = get_term($term_id);
		$term_color = get_term_meta( $term_id, 'es_color', true );

		if ( ! $term ) return false;

		ob_start(); ?>

		<li><label><?php echo $term->name; ?></label>
            <input value="<?php echo $term_color; ?>" data-id="<?php echo $term->term_id; ?>" type="color" class="js-color-item" data-action="es_ajax_data_manager_label_color" name="es_label_color[<?php echo $term->term_id; ?>]"/>

			<a href="#" class="es-item-remove js-item-remove"
			   data-id="<?php echo $term->term_id; ?>"
			   data-action="es_ajax_data_manager_remove_term"><i class="fa fa-trash"></i></a>
		</li>

		<?php return ob_get_clean();
	}

	/**
	 * Set color for label.
	 */
	public static function change_color()
	{
	    if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_save_dm_item' ) ) {
		    $color = update_term_meta( intval( $_POST['id'] ), 'es_color', sanitize_text_field( $_POST['color'] ) );

		    if ( ! $color || $color instanceof WP_Error) {
			    $response = array( 'message' => __( 'Item doesn\'t updated. Please contact support.', 'es-plugin' ), 'status' => 'error' );
		    } else {
			    $response = array( 'message' => __( 'Color successfully changed.', 'es-plugin' ), 'status' => 'success' );
		    }

		    wp_die( json_encode( $response ) );
        }
	}
}
