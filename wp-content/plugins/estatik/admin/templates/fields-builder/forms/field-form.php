<?php

/**
 * @file
 * Create / Update entity field form.
 *
 * @var array $tab
 */

$instance = null;

if ( Es_FBuilder_Helper::is_edit_action() ) {
    $page_title = __( 'Update field', 'es-plugin' );
    $btn_title = __( 'Update', 'es-plugin' );
    $instance = Es_FBuilder_Helper::get_edit_field();
} else {
    $page_title = __( 'Create field', 'es-plugin' );
    $btn_title = __( 'Create', 'es-plugin' );
}

$tabs = Es_Property_Metabox::get_tabs();
unset( $tabs['es-media'] );?>

<h1>
    <?php echo $page_title; ?>

    <?php if ( Es_FBuilder_Helper::is_edit_action() ) : ?>
        <a class="es-button es-button-green es-button-add-field" href="<?php echo 'admin.php?page=es_fbuilder'; ?>"><?php _e( 'Add new', 'es-plugin' ); ?></a>
    <?php endif; ?>
</h1>

<form action="" method="POST">

<?php

// Field name input.
echo Es_Html_Helper::render_settings_field( __( 'Field Name', 'es-plugin' ), 'fbuilder[label]', 'text', array(
    'required' => 'required',
    'value' => Es_FBuilder_Helper::get_settings_value( $instance, 'label' ),
) );

// Field type selectbox.
echo Es_Html_Helper::render_settings_field(__( 'Type', 'es-plugin' ), 'fbuilder[type]', 'list', array(
    'values' => Es_FBuilder_Helper::get_field_types(),
    'placeholder' => __( '-- Choose field type --', 'es-plugin' ),
    'required' => 'required',
    'value' => Es_FBuilder_Helper::get_settings_value( $instance, 'type' ),
    'class' => 'js-es-load-options-fields',
) ); ?>

<div class="js-es-fbuilder__field-options">
    <?php if ( ! empty( $instance['type'] ) ) : $template = Es_FBuilder_Helper::get_field_options_template( $instance['type'] ); ?>
        <?php if ( $template ) : ?>
            <?php include Es_Fields_Builder_Page::get_template_path( 'partials/options/' . $template ); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
// Field section input.
echo Es_Html_Helper::render_settings_field( __( 'Page Section', 'es-plugin' ), 'fbuilder[section]', 'list', array(
    'values' => Es_FBuilder_Helper::get_sections_options( $tab['entity'] ),
    'value' => Es_FBuilder_Helper::get_settings_value( $instance, 'section' ),
) );

// Field type selectbox.
echo Es_Html_Helper::render_settings_field( __( 'Admin tab', 'es-plugin' ), 'fbuilder[tab]', 'list', array(
    'values' => $tabs,
    'required' => 'required',
    'value' => Es_FBuilder_Helper::get_settings_value( $instance, 'tab' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Mandatory', 'es-plugin' ), 'fbuilder[options][required]', 'checkbox', array(
    'class' => 'es-switch-input',
    'value' => 1,
    'checked' => true == (bool) Es_FBuilder_Helper::get_options_value( $instance, 'required' ),
) );

// Field rets support input.
echo Es_Html_Helper::render_settings_field(__( 'RETS Support', 'es-plugin' ) . ' <i class="fa fa-info-circle js-es__available-tooltipster--rets" aria-hidden="true"></i>', 'fbuilder[rets_support]', 'checkbox', array(
    'class' => 'es-switch-input',
    'disabled' => 'disabled',
    'value' => 1,
) );

// Field search support input.
echo Es_Html_Helper::render_settings_field( __( 'Search Support', 'es-plugin' ) . ' <i class="fa fa-info-circle js-es__available-tooltipster--search" aria-hidden="true"></i>', 'fbuilder[search_support]', 'checkbox', array(
    'class' => 'es-switch-input',
    'disabled' => 'disabled',
    'value' => 1,
) );

$name = 'fbuilder[visible_permission]'; $label = __( 'Visible for', 'es-plugin' );

$data = apply_filters( 'es_fbuilder_field_form_caps', array(
    '' => __( 'All Users', 'es-plugin' ),
    'es_fb_admins_field_visible' => __( 'Admins', 'es-plugin' ),
), $instance ); $i = 0; ?>

<div class="es-field">
    <div class="es-field__label"><?php echo $label; ?>:</div>
    <div class="es-field__content" style="flex-wrap: wrap;">
        <?php foreach ( $data as $value => $label ) : $i++; ?>
            <span style="position: relative; display: block;">
    <input class="es-radio-input" type="radio" id="es-for-<?php echo $name . $i; ?>" <?php checked( $value, Es_FBuilder_Helper::get_settings_value( $instance, 'visible_permission' ) ); ?>
           name="<?php echo $name; ?>" value="<?php echo $value; ?>">
    <label for="es-for-<?php echo $name . $i; ?>"><?php echo $label; ?></label><span class="es-space"></span>
    </span>
        <?php endforeach; ?>
    </div>
</div>

<?php if ( ! empty( $instance['id'] ) ) : ?>
    <input type="hidden" name="fbuilder[id]" value="<?php echo $instance['id']; ?>"/>
<?php endif; ?>

<input type="submit" style="margin-top: 10px;" class="es-button <?php echo $instance ? 'es-button-blue' : 'es-button-green'; ?>" value="<?php echo $btn_title; ?>"/>

<?php wp_nonce_field( 'es_fbuilder_save_field', 'es_fbuilder_save_field' ); ?>

</form>
