<div class="es-property-list-filter es-box">
    <span class="es-filter-row-label"><?php _e( 'Filter by', 'es-plugin' ); ?>:</span>

    <div class="es-select-row">
        <?php wp_dropdown_categories( array(
            'option_none_value' => '',
            'name' => 'es_filter[tax][es_category]',
            'class' => 'es-select-2',
            'id' => 'cat',
            'taxonomy' => 'es_category',
            'show_option_none' => __( 'Category', 'es-plugin' ),
            'selected' => ! empty( $filter['tax']['es_category'] ) ? $filter['tax']['es_category'] : null,
        ) ); ?>

        <?php wp_dropdown_categories( array(
            'option_none_value' => '',
            'name' => 'es_filter[tax][es_type]',
            'class' => 'es-select-2',
            'id' => 'type',
            'taxonomy' => 'es_type',
            'show_option_none' => __( 'Type', 'es-plugin' ),
            'selected' => ! empty( $filter['tax']['es_type'] ) ? $filter['tax']['es_type'] : null,
        ) ); ?>

        <?php wp_dropdown_categories( array(
            'option_none_value' => '',
            'name' => 'es_filter[tax][es_status]',
            'class' => 'es-select-2',
            'id' => 'status',
            'taxonomy' => 'es_status',
            'show_option_none' => __( 'Status', 'es-plugin' ),
            'selected' => ! empty( $filter['tax']['es_status'] ) ? $filter['tax']['es_status'] : null,
        ) ); ?>
    </div>

    <div class="es-input-row">
        <span class="es-filter-row-label"><?php _e( 'Property ID', 'es-plugin' ); ?>:</span>
        <label>
            <input type='number' name='es_filter[property_id]' class="es-filter-property-id-input"
                   value='<?php echo ! empty( $filter['property_id'] ) ? $filter['property_id'] : null; ?>'>
        </label>

        <label><span class="es-filter-field-label-wrap"><?php _e( 'Address', 'es-plugin' ); ?>:</span>
            <input type='text' name="es_filter[address]" class="es-filter-address-input"
                   value='<?php echo ! empty( $filter['address'] ) ? $filter['address'] : null; ?>'>
        </label>

        <label><span class="es-filter-field-label-wrap"><?php _e( 'Date added', 'es-plugin' ); ?>:</span>
            <input type="text" class="js-datepicker" name="es_filter[date_added]">
        </label>

        <input type="submit" class="es-button es-button-blue es-submit-filter" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
        <a href="<?php echo es_admin_property_list_uri(); ?>" class="es-button es-button-gray es-reset-filter"><?php _e( 'Reset', 'es-plugin' ); ?></a>
    </div>

    <hr/>

    <div class="es-manage-row">
        <span class="es-filter-row-label"><?php _e( 'Manage', 'es-plugin' ); ?>:</span>

        <button type="submit" class="es-button es-button-gray es-button-check js-es-select-all">
            <?php _e( 'Select / deselect all', 'es-plugin' ); ?>
        </button>

        <button type="submit" data-error="<?php _e( 'Please select properties you want to copy.', 'es-plugin' ); ?>"
                data-confirm="<?php _e( 'Are you sure you want to copy these items?', 'es-plugin' ); ?>"
                data-action="copy"
                class="es-button es-button-gray es-button-copy js-es-copy"><?php _e( 'Copy', 'es-plugin' ); ?>
        </button>

        <button type="submit" data-error="<?php _e( 'Please select properties you want to delete.', 'es-plugin' ); ?>"
                data-confirm="<?php _e( 'Are you sure you want to delete these items?', 'es-plugin' ); ?>"
                data-action="delete" class="es-button es-button-gray es-button-delete js-es-delete">
            <?php _e( 'Delete', 'es-plugin' ); ?>
        </button>

        <button type="submit" data-error="<?php _e( 'Please select properties you want to publish.', 'es-plugin' ); ?>"
                data-confirm="<?php _e( 'Are you sure you want to publish these items?', 'es-plugin' ); ?>"
                data-action="publish" class="es-button es-button-gray es-button-publish js-es-publish">
            <?php _e( 'Publish', 'es-plugin' ); ?>
        </button>

        <button type="submit" data-error="<?php _e( 'Please select properties you want to unpublish.', 'es-plugin' ); ?>"
                data-confirm="<?php _e( 'Are you sure you want to unpublish these items?', 'es-plugin' ); ?>"
                data-action="unpublish" class="es-button es-button-gray es-button-unpublish js-es-unpublish">
            <?php _e( 'Unpublish', 'es-plugin' ); ?>
        </button>
    </div>

    <input type="hidden" name="es-action"/>
    <?php wp_nonce_field( 'es_manage_properties_form', 'es_manage_properties_form' ); ?>

    <div class="es-confirm-popup"></div>
    <div class="es-message-popup"></div>
</div>
