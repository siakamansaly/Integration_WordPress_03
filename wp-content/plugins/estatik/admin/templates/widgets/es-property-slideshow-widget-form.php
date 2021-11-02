<?php

/**
 * @file Estatik search widget form file.
 *
 * @var array $instance
 * @var string $title
 * @var string $layout
 * @var string $display_type
 * @var array $pages_active
 * @var $this Es_Property_Slideshow_Widget
 */

$instance = wp_parse_args( $instance, array(
    'title' => null,
    'layout' => 'horizontal',
    'slider_effect' => 'horizontal',
    'slides_num' => 1,
    'filter_data' => array(),
    'prop_ids' => '',
    'show_arrows' => 0,
    'limit' => 20,
    'margin' => 10,
    'price_min' => '',
    'price_max' => '',
    'show_labels' => '',
) );

$filter_data = ! empty( $instance['filter_data'][0] ) && is_array( $instance['filter_data'][0] ) ?
    $instance['filter_data'][0] : $instance['filter_data']; ?>

<div class="es-widget-wrap">

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'es-plugin' ); ?>: </label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo $instance['title']; ?>"/>
	</p>

	<p>
		<label><?php _e( 'Show arrows', 'es-plugin' ); ?>: </label>
		<label><?php _e( 'Yes', 'es-plugin' ); ?>
			<input type="radio" <?php checked( $instance['show_arrows'], 1 ); ?> class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_arrows' ) ); ?>" value="1"/>
		</label>
		<label><?php _e( 'No', 'es-plugin' ); ?>
			<input type="radio" <?php checked( $instance['show_arrows'], 0 ); ?> class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_arrows' ) ); ?>" value="0"/>
		</label>
	</p>

    <p>
        <label><?php _e( 'Show labels', 'es-plugin' ); ?>
            <input type="checkbox" <?php checked( $instance['show_labels'], 1 ); ?> class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_labels' ) ); ?>" value="1"/>
        </label>
    </p>

	<?php if ( $layouts = $this::get_slider_effects() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slider_effect' ) ); ?>"><?php _e( 'Slide Effect', 'es-plugin' ); ?>: </label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'slider_effect' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slider_effect' ) ); ?>" class="widefat">
				<?php foreach ( $layouts as $field ) : ?>
					<option <?php selected( $field, $instance['slider_effect'] ); ?> value="<?php echo $field; ?>"><?php echo Es_Html_Helper::generate_label( $field ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>

	<?php if ( $data = $this::get_filter_fields_data() ) : ?>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'filter_data' ) ); ?>"><?php _e( 'Filter data', 'es-plugin' ); ?>: </label>
        <select class="widefat" data-placeholder="<?php _e( '-- Select parameters for filtering --', 'es-plugin' ); ?>"
                style="width: 100%"
                multiple class="js-select2-tags"
                name="<?php echo esc_attr( $this->get_field_name( 'filter_data[]' ) ); ?>"
                id="<?php echo esc_attr( $this->get_field_id( 'filter_data' ) ); ?>">

		    <?php foreach ( $data as $group => $items ) : ?>
			    <?php if ( empty( $items ) ) continue; ?>

                <optgroup label="<?php echo $group; ?>">
				    <?php foreach ( $items as $value => $item ) : ?>
					    <?php if ( $item instanceof WP_Term) : ?>
                            <option <?php selected( in_array( $item->term_id , $filter_data ) ); ?> value="<?php echo $item->term_id; ?>"><?php echo $item->name; ?></option>
					    <?php else : ?>
                            <option <?php selected( in_array( $item->term_id , $filter_data ) ); ?> value="<?php echo $value; ?>"><?php echo $item; ?></option>
					    <?php endif; ?>
				    <?php endforeach; ?>
                </optgroup>

		    <?php endforeach; ?>
        </select>
    </p>
	<?php endif; ?>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'price_min' ) ); ?>"><?php _e( 'Min Price', 'es-plugin' ); ?></label>
        <input class="widefat" value="<?php echo $instance['price_min']; ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'price_min' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'price_min' ) ); ?>">
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'price_max' ) ); ?>"><?php _e( 'Max Price', 'es-plugin' ); ?></label>
        <input class="widefat" value="<?php echo $instance['price_max']; ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'price_max' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'price_max' ) ); ?>">
    </p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'prop_ids' ) ); ?>"><?php _e( 'Listings IDs', 'es-plugin' ); ?>: </label>
		<input class="widefat" value="<?php echo $instance['prop_ids']; ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'prop_ids' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'prop_ids' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>"><?php _e( 'Space between slides', 'es-plugin' ); ?>: </label>
		<input class="widefat" value="<?php echo $instance['margin']; ?>" type="number" min="0" name="<?php echo esc_attr( $this->get_field_name( 'margin' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Limit', 'es-plugin' ); ?>: </label>
		<input class="widefat" value="<?php echo $instance['limit']; ?>" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'slides_num' ) ); ?>"><?php _e( 'Slides to show', 'es-plugin' ); ?>: </label>
		<input class="widefat" value="<?php echo $instance['slides_num']; ?>" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'slides_num' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'slides_num' ) ); ?>">
	</p>

	<?php if ( $layouts = $this::get_layouts() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php _e( 'Layout', 'es-plugin' ); ?>: </label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat">
				<?php foreach ( $layouts as $field ) : ?>
					<option <?php selected( $field, $instance['layout'] ); ?> value="<?php echo $field; ?>"><?php echo Es_Html_Helper::generate_label( $field ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>

	<?php do_action( 'es_widget_' . $this->id_base . '_page_access_block', $instance ); ?>

</div>
