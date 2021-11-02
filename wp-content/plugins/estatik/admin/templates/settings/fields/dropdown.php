<?php
/**
 * @var array $data List key => value of specific setting.
 * @var Es_Settings_Container $es_settings.
 * @var string $name Name of setting.
 * @var string $label Field name.
 */
?>

<div class="es-settings-field"><label><span class="es-settings-label"><?php echo $label; ?>:</span>
    <select name="es_settings[<?php echo $name; ?>]">
        <?php foreach ( $data as $value => $label ) : ?>
            <option value="<?php echo $value; ?>" <?php selected( $value, $es_settings->{$name} ); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
    </select>
</label></div>
