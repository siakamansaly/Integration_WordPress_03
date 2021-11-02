<?php
/**
 * @var Es_Settings_Container $es_settings
 */
?>

<?php if ( $data = $es_settings::get_setting_values( 'listing_layout' ) ) : $name = 'listing_layout'; ?>
    <div class="es-layout-wrap">
        <span class="es-layout-label"><?php _e( 'Listings layout', 'es-plugin' ); ?>:</span>
        <?php foreach ( $data as $value => $label ) : $i++; ?>
            <div class="es-layout-box">
                <label>
                    <span class="es-sprite es-sprite-<?php echo $value; ?><?php echo $es_settings->{$name} == $value ? ' es-sprite-active' : ''; ?>"></span>
                    <input type="radio" <?php checked( $value, $es_settings->{$name} ); ?>
                           name="es_settings[<?php echo $name; ?>]"
                           value="<?php echo $value; ?>"
                           class="js-es-layout-checkbox radio" id="es-radio-<?php echo $name . $i; ?>"/>
                    <label for="es-radio-<?php echo $name . $i; ?>"></label>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ( $data = $es_settings::get_setting_values( 'single_layout' ) ) : $name = 'single_layout'; ?>
    <div class="es-layout-wrap es-single-layout-wrap">
        <span class="es-layout-label"><?php _e( 'Single property layout', 'es-plugin' ); ?>:</span>
        <?php foreach ( $data as $value => $label ) : $i++; ?>
            <div class="es-layout-box">
                <label>
                    <span class="es-sprite es-sprite-<?php echo $value; ?><?php echo $es_settings->{$name} == $value ? ' es-sprite-active' : ''; ?>"></span>
                    <input type="radio" <?php checked( $value, $es_settings->{$name} ); ?>
                           name="es_settings[<?php echo $name; ?>]"
                           value="<?php echo $value; ?>"
                           class="js-es-layout-checkbox radio" id="es-radio-<?php echo $name . $i; ?>"/>
                    <label for="es-radio-<?php echo $name . $i; ?>"></label>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;
