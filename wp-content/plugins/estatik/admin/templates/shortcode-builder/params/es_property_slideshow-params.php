<h3>
	<i class="fa fa-cog" aria-hidden="true"></i>
	<?php _e( 'General settings' ); ?>
</h3>

<div class="est-form-row">

	<div class="est-field">
		<label><?php _e( 'Status', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[status][]',
				'class' => 'js-select2-multiple',
				'id' => 'status',
				'taxonomy' => 'es_status',
				'value_field' => 'name',
			) ); ?>
		</div>
	</div>

	<div class="est-field">
		<label><?php _e( 'Type', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[type][]',
				'class' => 'js-select2-multiple',
				'id' => 'type',
				'taxonomy' => 'es_type',
				'value_field' => 'name',
			) ); ?>
		</div>
	</div>
</div>

<div class="est-form-row">

	<div class="est-field">
		<label><?php _e( 'Rent Period', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[rent_period][]',
				'class' => 'js-select2-multiple',
				'id' => 'rent_period',
				'taxonomy' => 'es_rent_period',
				'value_field' => 'name',
			) ); ?>
		</div>
	</div>

	<div class="est-field">
		<label><?php _e( 'Category', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[category][]',
				'class' => 'js-select2-multiple',
				'id' => 'category',
				'taxonomy' => 'es_category',
				'value_field' => 'name',
			) ); ?>
		</div>
	</div>
</div>

<h3>
	<i class="fa fa-cog" aria-hidden="true"></i>
	<?php _e( 'Slider settings' ); ?>
</h3>

<div class="est-form-row">
	<div class="est-field">
		<label for="es-effect-field"><?php _e( 'Slider effect', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<select id="es-effect-field" name="attr[slider_effect]" class="js-select2">
				<option value="vertical"><?php _e( 'Vertical', 'es-plugin' ); ?></option>
				<option value="horizontal"><?php _e( 'Horizontal', 'es-plugin' ); ?></option>
			</select>
		</div>
	</div>

	<div class="est-field">
		<label for="es-layout-field"><?php _e( 'Layout', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<select id="es-layout-field" name="attr[layout]" class="js-select2">
				<option value="vertical"><?php _e( 'Vertical', 'es-plugin' ); ?></option>
				<option value="horizontal"><?php _e( 'Horizontal', 'es-plugin' ); ?></option>
			</select>
		</div>
	</div>
</div>

<div class="est-form-row">
	<div class="est-field">
		<label for="es-slides_to_show-field"><?php _e( 'Slides To Show', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="number" id="es-slides_to_show-field" min="1" value="1" name="attr[slides_to_show]"/>
		</div>
	</div>

	<div class="est-field">
		<label for="es-margin-field"><?php _e( 'Margin', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="number" id="es-margin-field" value="10" name="attr[margin]"/>
		</div>
	</div>

    <div class="est-field">
        <label for="es-limit-field"><?php _e( 'Limit', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
            <input type="number" id="es-limit-field" value="20" name="attr[limit]"/>
        </div>
    </div>
</div>

<div class="est-form-row">

	<div class="est-field">
		<label for="es-show-field"><?php _e( 'Show All Properties', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="checkbox" id="es-show-field" value="all" name="attr[show]"/>
		</div>
	</div>

	<div class="est-field">
		<label for="es-show_arrows-field"><?php _e( 'Show Arrows', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="checkbox" id="es-show_arrows-field" value="1" name="attr[show_arrows]"/>
		</div>
	</div>

    <div class="est-field">
        <label for="es-show_arrows-field"><?php _e( 'Show Labels', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
            <input type="checkbox" id="es-show_arrows-field" value="1" name="attr[show_labels]"/>
        </div>
    </div>
</div>