<style>
    #es-addresses-tab a{
        color: #389fff;
    }
</style>
 <?php

printf( __( "<p>Some countries (e.g. Spain, UK & others) have different address components and need extra configuration for proper address generation and map work. If <a href='%s' target='_blank'>this documentation</a> seems to be too complicated for understanding, please <a href='%s' target='_blank'>contact us</a> and we will setup the fields below for you.</p>", 'es-plugin' ), 'https://developers.google.com/maps/documentation/geocoding/overview#ReverseGeocoding', 'https://estatik.net/contact-us/' );

echo Es_Html_Helper::render_settings_field( __( 'Country', 'es-plugin' ), 'es_settings[country_component_types][]', 'list', array(
    'value' => $es_settings->country_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );

echo Es_Html_Helper::render_settings_field( __( 'State', 'es-plugin' ), 'es_settings[state_component_types][]', 'list', array(
    'value' => $es_settings->state_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );

echo Es_Html_Helper::render_settings_field( __( 'Province', 'es-plugin' ), 'es_settings[province_component_types][]', 'list', array(
    'value' => $es_settings->province_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );

echo Es_Html_Helper::render_settings_field( __( 'City', 'es-plugin' ), 'es_settings[city_component_types][]', 'list', array(
    'value' => $es_settings->city_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );

echo Es_Html_Helper::render_settings_field( __( 'Street', 'es-plugin' ), 'es_settings[street_component_types][]', 'list', array(
    'value' => $es_settings->street_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );

echo Es_Html_Helper::render_settings_field( __( 'Neighborhood', 'es-plugin' ), 'es_settings[neighborhood_component_types][]', 'list', array(
    'value' => $es_settings->neighborhood_component_types,
    'values' => ES_Address_Components::get_types(),
    'multiple' => 'multiple',
) );