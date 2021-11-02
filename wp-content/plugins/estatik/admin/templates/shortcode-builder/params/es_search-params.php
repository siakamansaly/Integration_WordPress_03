<?php global $es_settings; ?>

<div class="est-form-row">

	<div class="est-field">
		<label for="es-layout-field">
            <?php _e( 'Layout', 'es-plugin' ); ?>
            <i class="fa fa-info-circle" aria-hidden="true" data-tooltipster-content="<?php _e( 'Choose the layout for listings on Search results page.', 'es-plugin' ); ?>"></i>
        </label>
		<div class="est-field__content est-field__content__select">
			<select required id="es-layout-field" name="attr[layout]">
				<?php foreach ( $es_settings::get_setting_values( 'listing_layout' ) as $key => $value ) : ?>
					<option <?php selected( $es_settings->listing_layout, $key ); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

</div>