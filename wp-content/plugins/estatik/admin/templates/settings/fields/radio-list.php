<?php
/**
 * @var array $data List key => value of specific setting.
 * @var Es_Settings_Container $es_settings.
 * @var string $name Name of setting.
 * @var string $label Field name.
 */
$i = 0; ?>

<div class="es-field">
    <div class="es-field__label"><?php echo $label; ?>:</div>
    <div class="es-field__content">
    <?php foreach ( $data as $value => $label ) : $i++; ?>
        <span style="position: relative;">
        <input class="es-radio-input" type="radio" id="es-for-<?php echo $name . $i; ?>" <?php checked( $value, $es_settings->{$name} ); ?>
               name="es_settings[<?php echo $name; ?>]" value="<?php echo $value; ?>">
        <label for="es-for-<?php echo $name . $i; ?>"><?php echo $label; ?></label><span class="es-space"></span>
        </span>
        <?php endforeach; ?>
    </div>
</div>
