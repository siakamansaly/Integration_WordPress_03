<?php

/**
 * @var Es_Settings_Container $es_settings
 * @var array $tabs
 */

global $es_settings, $post_ID; $property = es_get_property( $post_ID ); $fields = $property::get_fields(); ?>

<div class="property-data-tabs nav-tab-wrapper es-box es-wrap">
    <ul class="nav-tab-menu">
        <?php foreach ( $tabs as $id => $tab ) :
	        if ( ! Es_Property_Metabox::tab_has_content( $id, $fields ) ) continue; ?>
            <li><a href="#<?php echo $id; ?>"><?php echo empty( $tab['label'] ) ? $id : $tab['label'] ; ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php foreach ( $tabs as $id => $tab ) : ?>
        <div id="<?php echo $id; ?>" class="es-tab">
            <?php do_action( 'es_before_property_metabox_tab_content', $id, $tab ); ?>

            <?php if ( ( $id != 'es-map' ) || ( $id == 'es-map' && ! empty( $es_settings->google_api_key ) ) ): ?>

                <?php if ( $fields = $property::get_fields() ) : ?>
                    <?php foreach ( $fields as $field_id => $field ) : ?>
                        <?php if ( ! empty( $field['tab'] ) && $field['tab'] == $id ): ?>
                            <?php do_action( 'es_before_property_metabox_field_content', $field_id, $field ); ?>
				            <?php echo Es_Html_Helper::render_field( $field_id, $field, $property ); ?>
                            <?php do_action( 'es_after_property_metabox_field_content', $field_id, $field ); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else : ?>
                <p><?php echo sprintf( wp_kses( __( 'Please enter Google Map API key in <a href="%s" target="_blank">General Settings</a> of the plugin.', 'es-plugin' ), array(
                        'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( es_admin_settings_uri() ) ); ?></p>
            <?php endif; ?>

            <?php do_action( 'es_after_property_metabox_tab_content', $id, $tab ); ?>
        </div>
    <?php endforeach; ?>
</div>
