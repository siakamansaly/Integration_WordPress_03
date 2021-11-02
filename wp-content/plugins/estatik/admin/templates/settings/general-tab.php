<?php

/**
 * @var Es_Settings_Container $es_settings
 */

$pages = get_pages();
$list_pages[] = __( '-- Select page --', 'es-plugin' );

if ( ! empty( $pages ) ) {
    foreach ( $pages as $page ) {
        $list_pages[ $page->ID ] = $page->post_title;
    }
}

//echo Es_Html_Helper::render_settings_field( __( 'Country', 'es-plugin' ), 'es_settings[country]', 'list', array(
//    'value' => $es_settings->country,
//    'values' => $es_settings::get_setting_values( 'country' ),
//) );

echo Es_Html_Helper::render_settings_field( __( 'Powered by link', 'es-plugin' ), 'es_settings[powered_by_link]', 'checkbox', array(
    'checked' => (bool) $es_settings->powered_by_link ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Number of listings per page', 'es-plugin' ), 'es_settings[properties_per_page]', 'number', array(
    'checked' => (bool) $es_settings->properties_per_page ? 'checked' : false,
    'value' => $es_settings->properties_per_page,
    'min' => '1',
) );

echo Es_Html_Helper::render_settings_field( __( 'Custom Property Slug URI', 'es-plugin' ), 'es_settings[property_slug]', 'text', array(
    'value' => $es_settings->property_slug,
	'required' => 'required',
) );

echo Es_Html_Helper::render_settings_field( __( 'Custom Property Name', 'es-plugin' ), 'es_settings[property_name]', 'text', array(
	'value' => $es_settings->property_name,
	'required' => 'required',
) );

echo Es_Html_Helper::render_settings_field( __( 'Show price', 'es-plugin' ), 'es_settings[show_price]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_price ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable Property Featured Image', 'es-plugin' ), 'es_settings[disable_featured_image]', 'checkbox', array(
	'checked' => (bool) $es_settings->disable_featured_image ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) ); ?>

<?php if ( $data = $es_settings::get_setting_values( 'title_address' ) ) : $name = 'title_address'; $label = __( 'Title / Address', 'es-plugin' ) ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif; ?>

<?php echo Es_Html_Helper::render_settings_field( __( 'Show Address', 'es-plugin' ), 'es_settings[show_address]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_address ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable LightBox on Single Page', 'es-plugin' ), 'es_settings[is_lightbox_disabled]', 'checkbox', array(
	'checked' => (bool) $es_settings->is_lightbox_disabled ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Hide map on Single Page', 'es-plugin' ), 'es_settings[hide_map]', 'checkbox', array(
    'checked' => (bool) $es_settings->hide_map ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable editor auto paragraph', 'es-plugin' ), 'es_settings[wp_autop]', 'checkbox', array(
	'checked' => (bool) $es_settings->wp_autop ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Remove images from Media Library', 'es-plugin' ), 'es_settings[remove_images_on_prop_delete]', 'checkbox', array(
	'checked' => (bool) $es_settings->remove_images_on_prop_delete ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
	'data-tooltipster-content' => __( 'Disable if you want images to remain in Media Library when properties deleted.', 'es-plugin' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Enable REST Support', 'es-plugin' ), 'es_settings[is_rest_support_enabled]', 'checkbox', array(
	'checked' => (bool) $es_settings->is_rest_support_enabled ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
    'data-tooltipster-content' => __( 'Please enable REST support if you need to use Gutenberg or pull your listings via wp api.', 'es-plugin' ),
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable sticky property top bar', 'es-plugin' ), 'es_settings[disable_sticky_property_top_bar]', 'checkbox', array(
	'checked' => (bool) $es_settings->disable_sticky_property_top_bar ? 'checked' : false,
	'value' => 1,
	'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Hide Property Top Bar', 'es-plugin' ), 'es_settings[hide_property_top_bar]', 'checkbox', array(
    'checked' => (bool) $es_settings->hide_property_top_bar ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Enable Wishlist', 'es-plugin' ), 'es_settings[is_wishlist_enabled]', 'checkbox', array(
    'checked' => (bool) $es_settings->is_wishlist_enabled ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Disable meta:og tags', 'es-plugin' ), 'es_settings[disable_og_meta_tags]', 'checkbox', array(
    'checked' => (bool) $es_settings->disable_og_meta_tags ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Labels', 'es-plugin' ), 'es_settings[show_labels]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_labels ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Show Labels on Single Page', 'es-plugin' ), 'es_settings[show_labels_on_single_page]', 'checkbox', array(
    'checked' => (bool) $es_settings->show_labels_on_single_page ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Date added', 'es-plugin' ), 'es_settings[date_added]', 'checkbox', array(
    'checked' => (bool) $es_settings->date_added ? 'checked' : false,
    'value' => 1,
    'class' => 'es-switch-input',
) );

echo Es_Html_Helper::render_settings_field( __( 'Date format', 'es-plugin' ), 'es_settings[date_format]', 'list', array(
    'value' => $es_settings->date_format,
    'values' => $es_settings::get_setting_values( 'date_format' ),
) );

if ( $data = $es_settings::get_setting_values( 'privacy_policy_checkbox' ) ) : $name = 'privacy_policy_checkbox'; $label = __( 'Privacy Policy Checkbox', 'es-plugin' ) ?>
    <?php include( 'fields/radio-list.php' ); ?>
<?php endif;

echo Es_Html_Helper::render_settings_field( __( 'Terms of Use page', 'es-plugin' ), 'es_settings[term_of_use_page_id]', 'list', array(
    'value' => $es_settings->term_of_use_page_id,
    'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Privacy Policy page', 'es-plugin' ), 'es_settings[privacy_policy_page_id]', 'list', array(
    'value' => $es_settings->privacy_policy_page_id,
    'values' => $list_pages,
) );

echo Es_Html_Helper::render_settings_field( __( 'Search page', 'es-plugin' ), 'es_settings[search_page_id]', 'list', array(
	'value' => $es_settings->search_page_id,
	'values' => $list_pages,
) );
