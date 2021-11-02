<?php

global $es_settings;

$pages = get_posts( array(
	'post_type' => 'page',
	'fields' => 'ids',
	'posts_per_page' => -1
) ); ?>

<div class="est-form-row">

	<div class="est-field">
		<label for="es-title-field"><?php _e( 'Title', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<input type="text" name="attr[title]" id="es-title-field">
		</div>
	</div>

	<?php if ( $pages ) : ?>
		<div class="est-field">
			<label for="es-page-field"><?php _e( 'Search Result Page', 'es-plugin' ); ?>
                <i class="fa fa-info-circle" aria-hidden="true" data-tooltipster-content="<?php _e( 'Select here the correct page with shortcode [es_search].', 'es-plugin' ); ?>"></i>
            </label>
			<div class="est-field__content">
				<select id="es-page-field" name="attr[page_id]" class="js-select2">
					<option value=""><?php _e( 'Select page', 'es-plugin' ); ?></option>
					<?php foreach ( $pages as $page_id ) : ?>
						<option <?php echo selected( $page_id, $es_settings->search_page_id ); ?> value="<?php echo $page_id; ?>"><?php echo get_the_title( $page_id ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php endif; ?>
</div>


<div class="est-form-row">

	<div class="est-field">
		<label for="es-fields-field"><?php _e( 'Search Fields', 'es-plugin' ); ?>:</label>
		<div class="est-field__content">
			<select id="es-fields-field" name="attr[fields][]" class="js-select2-multiple js-es-search-fields-select2" multiple>
				<?php foreach ( Es_Search_Widget::get_widget_fields() as $field ) : $info = Es_Property::get_field_info( $field ); ?>
					<?php if ( ! empty( $info['label'] ) ) : ?>
						<option value="<?php echo $field; ?>"><?php echo $info[ 'label' ]; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
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
