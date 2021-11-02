<?php do_action( 'es_before_addresses_tab' );
global $es_settings;
$types = apply_filters( 'es_load_dm_address_types', array(
	'country' => $es_settings->country_component_types,
	'state' => $es_settings->state_component_types,
    'province' => $es_settings->province_component_types,
	'city' => $es_settings->city_component_types,
	'neighborhood' => $es_settings->neighborhood_component_types,
) ); ?>

<?php if ( ! empty( $types ) ) : ?>
    <?php foreach ( $types as $t => $type ) : ?>
        <?php $dmi = new Es_Data_Manager_Address_Item( $type, array(
			'label' => ES_Address_Components::get_label_by_type( $t ),
			'id' => 'es-' . $t . '-dm-item',
		) ); $dmi->render(); ?>
    <?php endforeach; ?>
<?php endif;

do_action( 'es_after_addresses_tab' );
