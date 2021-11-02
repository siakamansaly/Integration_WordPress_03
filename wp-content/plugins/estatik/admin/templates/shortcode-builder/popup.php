<?php

/**
 * @var $instance Es_Shortcode
 */

add_filter( 'es_shortcodes_list', function( $list ) {

    unset( $list['expensive'], $list['featured'], $list['cheapest'], $list['latest'], $list['latest'], $list['category'] );

    return $list;
} );

$shortcodes = Es_Shortcodes::es_get_shortcodes_classes();
$context = sanitize_key( filter_input( INPUT_GET, 'context' ) ); ?>

<div id="shortcode-builder-popup" class="white-popup">
    <form class="js-es-shortcode-builder-form" data-editor="<?php echo esc_attr( $context ); ?>">
        <h2><?php _e( 'Insert shortcode', 'es-plugin' ); ?></h2>
        <sub><?php _e( 'Please click on a drop-down list and pick up the required shortcode.', 'es-plugin' ); ?></sub>

        <div class="est-form-row">
            <div class="est-field">
                <label for="ept-select-shortcode-field"><?php _e( 'Select a shortcode', 'es-plugin' ); ?>:</label>
                <div class="est-field__content est-field__content__select">
                    <select name="shortcode_name" data-nonce="<?php echo wp_create_nonce( 'es_shortcode_builder_params' ); ?>" class="js-shortcode-field" id="ept-select-shortcode-field">
                        <option value=""><?php _e( 'Choose shortcode', 'es-plugin' ); ?></option>
                        <?php foreach ( $shortcodes as $_class ) : $instance = new $_class(); ?>
                            <option value="<?php echo $instance->get_shortcode_name(); ?>">
                                <?php echo $instance->get_shortcode_title(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="js-shortcode-content shortcode-content"></div>

        <input type="hidden" name="action" value="es_build_shortcode"/>
        <?php echo wp_nonce_field( 'es_build_shortcode', 'es_build_shortcode_nonce' ); ?>
        <div class="es-shortcode-builder__buttons">
            <a href="#" class="js-es-sb-close"><?php _e( 'Cancel', 'es-plugin' ); ?></a>
            <input type="submit" value="<?php _e( 'Generate Shortcode', 'es-plugin' ); ?>" data-editor="<?php echo esc_attr( $context ); ?>" class="js-insert-shortcode hidden es-button es-button-green"/>
        </div>
    </form>
</div>