<?php

global $es_settings;

echo Es_Html_Helper::render_settings_field( __( 'Enable Auto tags', 'es-plugin' ), 'es_settings[is_tags_enabled]', 'checkbox', array(
	'checked' => (bool) $es_settings->is_tags_enabled ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
	'data-toggle-container' => '#es-seo-container'
) ); ?>

<div id="es-seo-container">
	<?php echo Es_Html_Helper::render_settings_field( __( 'Enable clickable tags', 'es-plugin' ), 'es_settings[is_tags_clickable]', 'checkbox', array(
		'checked' => (bool) $es_settings->is_tags_clickable ? 'checked' : false,
		'value' => 1,
		'class' => 'es-switch-input',
	) ); ?>
</div>

<?php echo Es_Html_Helper::render_settings_field( __( 'Enable Dynamic content', 'es-plugin' ), 'es_settings[is_dynamic_content_enabled]', 'checkbox', array(
    'checked' => (bool) $es_settings->is_dynamic_content_enabled ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Dynamic content', 'es-plugin' ), 'dynamic_content', 'wp_editor', array(
    'value' => wp_unslash( $es_settings->dynamic_content ),
    'options' => array(
        'textarea_rows' => 5,
        'textarea_name' => 'es_settings[dynamic_content]',
        'tinymce' => array(
            'plugins' => "link,textcolor,hr",
            'toolbar1'=>"fontsizeselect,forecolor,bold,italic,underline,strikethrough,alignleft,aligncenter,alignright,alignjustify",
            'toolbar2'=>"blockquote,hr,table,bullist,numlist,undo,redo,link,unlink" )
    ),
) );
