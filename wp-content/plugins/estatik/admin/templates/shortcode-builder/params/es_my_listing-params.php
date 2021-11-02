<?php global $es_settings; ?>
<h3>
	<i class="fa fa-cog" aria-hidden="true"></i>
	<?php _e( 'General settings' ); ?>
</h3>

<div class="est-form-row">

	<div class="est-field">
		<label for="es-layout-field">
            <?php _e( 'Layout', 'es-plugin' ); ?>
            <i class="fa fa-info-circle" aria-hidden="true" data-tooltipster-content="<?php _e( 'Choose the layout for listings.', 'es-plugin' ); ?>"></i>
        </label>
		<div class="est-field__content est-field__content__select">
			<select required id="es-layout-field" name="attr[layout]">
				<?php foreach ( $es_settings::get_setting_values( 'listing_layout' ) as $key => $value ) : ?>
					<option <?php selected( $es_settings->listing_layout, $key ); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="est-field">
		<label for="es-properties-per-page-field"><?php _e( 'Properties Per Page', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="number" id="es-properties-per-page-field" name="attr[posts_per_page]" required min="1" value="<?php echo $es_settings->properties_per_page; ?>"/>
		</div>
	</div>

    <div class="est-field">
        <label for="es-sort-field"><?php _e( 'Sort By', 'es-plugin' ); ?>:</label>
        <div class="est-field__content est-field__content__select">
            <select id="es-sort-field" name="attr[sort]">
                <option value=""><?php _e( 'Select Sort', 'es-plugin' ); ?></option>
				<?php foreach ( Es_Archive_Sorting::get_sorting_dropdown_values() as $key => $value ) : ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php endforeach; ?>
            </select>
        </div>
    </div>

</div>

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

<div class="est-form-row">

    <div class="est-field">
        <label for="es-post-in-field"><?php _e( 'Show Properties', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
            <select id="es-post-in-field" name="attr[prop_id]" class="js-select2-properties" multiple>
                <option value=""><?php _e( 'Type Property ID or Title', 'es-plugin' ); ?></option>
            </select>
        </div>
    </div>

    <div class="est-field">
        <label for="es-show_filter-field"><?php _e( 'Hide Filter', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
            <input type="checkbox" name="attr[show_filter]" value="0" id="es-show_filter-field"/>
        </div>
    </div>

</div>

<h3>
    <i class="fa fa-map-marker" aria-hidden="true"></i>
	<?php _e( 'Address settings', 'es-plugin' ); ?>
</h3>

<div class="est-form-row">

    <div class="est-field">
        <label for="es-address-field"><?php _e( 'Address String', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
            <input type="text" id="es-address-field" name="attr[address]"/>
        </div>
    </div>

</div>

<div class="est-form-row">

	<?php if ( $items = ES_Address_Components::get_component_list( Es_Search_Location::LOCATION_COUNTRY_TYPE ) ) : ?>
        <div class="est-field">
            <label for="es-country-field"><?php _e( 'Country', 'es-plugin' ); ?>:</label>
            <div class="est-field__content">
                <select name="attr[country]" id="es-country-field" class="js-select2">
                    <option value=""><?php _e( 'Select Country', 'es-plugin' ); ?></option>

                    <?php foreach ( $items as $item ) : ?>
                        <option value="<?php echo $item->id; ?>"><?php echo $item->long_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>

	<?php if ( $items = ES_Address_Components::get_component_list( Es_Search_Location::LOCATION_STATE_TYPE ) ) : ?>
        <div class="est-field">
            <label for="es-state-field"><?php _e( 'State', 'es-plugin' ); ?>:</label>
            <div class="est-field__content">
                <select name="attr[state]" id="es-state-field" class="js-select2">
                    <option value=""><?php _e( 'Select State', 'es-plugin' ); ?></option>

	                <?php foreach ( $items as $item ) : ?>
                        <option value="<?php echo $item->id; ?>"><?php echo $item->long_name; ?></option>
	                <?php endforeach; ?>
                </select>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( $items = ES_Address_Components::get_component_list( Es_Search_Location::LOCATION_CITY_TYPE ) ) : ?>
        <div class="est-field">
            <label for="es-city-field"><?php _e( 'City', 'es-plugin' ); ?>:</label>
            <div class="est-field__content">
                <select name="attr[city]" id="es-city-field" class="js-select2">
                    <option value=""><?php _e( 'Select City', 'es-plugin' ); ?></option>

	                <?php foreach ( $items as $item ) : ?>
                        <option value="<?php echo $item->id; ?>"><?php echo $item->long_name; ?></option>
	                <?php endforeach; ?>
                </select>
            </div>
        </div>
	<?php endif; ?>

</div>

<h3>
    <i class="fa fa-star" aria-hidden="true"></i>
	<?php _e( 'Specific Features', 'es-plugin' ); ?>
</h3>

<div class="est-form-row">

    <div class="est-field">
        <label><?php _e( 'Features', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[feature][]',
				'class' => 'js-select2-multiple',
				'id' => 'status',
				'taxonomy' => 'es_feature',
				'value_field' => 'name',
			) ); ?>
        </div>
    </div>

    <div class="est-field">
        <label><?php _e( 'Amenities', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[amenities][]',
				'class' => 'js-select2-multiple',
				'id' => 'amenities',
				'taxonomy' => 'es_amenities',
				'value_field' => 'name',
			) ); ?>
        </div>
    </div>

    <div class="est-field">
        <label><?php _e( 'Labels', 'es-plugin' ); ?>:</label>
        <div class="est-field__content">
			<?php wp_dropdown_categories( array(
				'name' => 'attr[labels][]',
				'class' => 'js-select2-multiple',
				'id' => 'labels',
				'hide_empty' => false,
				'taxonomy' => 'es_labels',
				'value_field' => 'name',
			) ); ?>
        </div>
    </div>

</div>
