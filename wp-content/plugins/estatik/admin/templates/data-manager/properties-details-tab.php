<?php do_action( 'es_before_properties_details_tab' ); ?>

<?php $dmi = new Es_Data_Manager_Term_Item( 'es_status' ); $dmi->render(); ?>
<?php $dmi = new Es_Data_Manager_Term_Item( 'es_category' ); $dmi->render(); ?>
<?php $dmi = new Es_Data_Manager_Term_Item( 'es_type' ); $dmi->render(); ?>
<?php $dmi = new Es_Data_Manager_Term_Item( 'es_rent_period' ); $dmi->render(); ?>

<?php do_action( 'es_after_properties_details_tab' ); ?>
