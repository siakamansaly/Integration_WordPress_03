<?php

/**
 * @file Estatik search widget form file.
 *
 * @var array $instance
 * @var string $title
 * @var string $layout
 * @var string $display_type
 * @var array $pages_active
 * @var $this Es_Search_Widget
 */

$instance = wp_parse_args( $instance, array(
    'title' => null,
    'layout' => null,
    'fields' => null,
    'save_search_button' => null,
    'enable_unit_converter' => null,
) );

$title         = $instance['title'];
$layout        = $instance['layout'];
$fields_active = ! empty( $instance['fields'][0] ) ? $instance['fields'] : null;
$enable_unit_converter = $instance['enable_unit_converter'];
$save_search   = $instance['save_search_button']; ?>

<div class="es-search-widget__wrap">

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Search Title', 'es-plugin' ); ?>:</label>
    <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
</p>

<?php if ( $layouts = $this::get_layouts() ) : ?>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php _e( 'Layouts', 'es-plugin' ); ?>:</label>
        <select id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat">
            <?php foreach ( $layouts as $field => $label ) : ?>
                <option <?php selected( $field, $layout ); ?> value="<?php echo $field; ?>"><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
    </p>
<?php endif; ?>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'enable_unit_converter' ) ); ?>"><?php _e( 'Show unit converter', 'es-plugin' ); ?>:</label>
    <input <?php checked( 1, $enable_unit_converter ); ?> id="<?php echo esc_attr( $this->get_field_id( 'enable_unit_converter' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'enable_unit_converter' ) ); ?>" class="widefat" value="1"/>
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'save_search_button' ) ); ?>"><?php _e( 'Enable save search', 'es-plugin' ); ?>:</label>
    <input <?php checked( 1, $save_search ); ?> id="<?php echo esc_attr( $this->get_field_id( 'save_search_button' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'save_search_button' ) ); ?>" class="widefat" value="1"/>
</p>

<?php if ( $fields = $this::get_widget_fields() ) : ?>
    <p>
        <label for=""><?php _e( 'Add search field', 'es-plugin' ); ?>:</label>
        <select name="" id="" class="widefat js-es-field-select">
            <option value=""><?php _e( '-- Select field --', 'es-plugin' ); ?></option>
            <?php foreach ( $fields as $name ) : $field_info = Es_Property::get_field_info( $name ); ?>
                <option value="<?php echo esc_attr( $name ); ?>">
                    <?php echo ! empty( $field_info['label'] ) ? $field_info['label'] : Es_Html_Helper::generate_label( $name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
<?php endif; ?>

<ul class="es-search-widget-fields">
    <?php if ( ! empty( $fields_active ) ) : ?>
        <?php foreach( $fields_active as $field ) :
            if ( empty( $field ) || ! ( $field_info = Es_Property::get_field_info( $field ) ) ) continue; ?>
            <li data-field-name="<?php echo $field; ?>">
                <?php echo ! empty( $field_info['label'] ) ? $field_info['label'] : Es_Html_Helper::generate_label( $field ); ?>
                <a href="#" class="es-remove-field">×</a>
                <input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'fields[]' ) ); ?>" value="<?php echo esc_attr( $field ); ?>">
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'fields[]' ) ); ?>" class="es-fields-name">

<?php do_action( 'es_widget_' . $this->id_base . '_page_access_block', $instance ); ?>

</div>
