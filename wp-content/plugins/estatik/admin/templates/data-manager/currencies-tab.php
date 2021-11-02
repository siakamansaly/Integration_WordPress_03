<?php do_action( 'es_before_currencies_tab' ); ?>

<?php $dmi = new Es_Data_Manager_Currency_Item(
    'es_currency_values' ,
    'currency',
    array(
        'label' => __( 'Currencies', 'es-plugin' )
    )
); $dmi ->render(); ?>

<?php do_action( 'es_after_currencies_tab' );
