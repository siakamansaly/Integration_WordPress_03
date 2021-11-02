<?php

/**
 * @var $filter_rating int|null
 * @var $filter_company string|null
 */

?>
<input type="hidden" name="wp_http_referer" value="<?php echo es_admin_buyers_uri(); ?>"/>
<div class="es-property-list-filter es-box">

	<div class="es-input-row">
		<span class="es-filter-row-label"><?php _e( 'By Name', 'es-plugin' ); ?>:</span>

		<label>
			<input type='text' name="es_user_filter[name]" class="es-filter-address-input"
			       value='<?php echo ! empty( $filter['name'] ) ? $filter['name'] : null; ?>'>
		</label>

		<input type="submit" class="es-button es-button-blue es-submit-filter" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
		<a href="<?php echo es_admin_buyers_uri(); ?>" class="es-button es-button-gray es-reset-filter"><?php _e( 'Reset', 'es-plugin' ); ?></a>
	</div>

	<div class="es-manage-row">
		<span class="es-filter-row-label"><?php _e( 'Manage', 'es-plugin' ); ?>:</span>
		<button type="submit" class="es-button es-button-gray es-button-check js-es-select-all"><?php _e( 'Select / deselect all', 'es-plugin' ); ?></button>
		<button type="submit" data-error="<?php _e( 'Please select agent you want to delete.', 'es-plugin' ); ?>" data-confirm="<?php _e( 'Are you sure you want to delete these items?', 'es-plugin' ); ?>" data-action="delete" class="es-button es-button-gray es-button-delete js-es-delete"><?php _e( 'Delete', 'es-plugin' ); ?></button>
		<button type="submit" data-error="<?php _e( 'Please select agent you want to active.', 'es-plugin' ); ?>" data-confirm="<?php _e( 'Are you sure you want to activate these agents?', 'es-plugin' ); ?>" data-action="activate" class="es-button es-button-gray es-button-publish js-es-activate"><?php _e( 'Activate', 'es-plugin' ); ?></button>
		<button type="submit" data-error="<?php _e( 'Please select agent you want to deactivate.', 'es-plugin' ); ?>" data-confirm="<?php _e( 'Are you sure you want to deactivate these agents?', 'es-plugin' ); ?>" data-action="deactivate" class="es-button es-button-gray es-button-unpublish js-es-deactivate"><?php _e( 'Deactivate', 'es-plugin' ); ?></button>
	</div>

	<input type="hidden" name="es-action"/>
	<input type="hidden" name="role" value="<?php echo Es_Buyer::get_role_name(); ?>"/>

	<div class="es-confirm-popup"></div>
	<div class="es-message-popup"></div>
</div>
