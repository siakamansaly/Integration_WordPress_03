<?php do_action( 'es_before_features_tab' ); ?>

<?php $dmi = new Es_Data_Manager_Term_Item( 'es_feature' ); $dmi->render(); ?>
<?php $dmi = new Es_Data_Manager_Term_Item( 'es_amenities' ); $dmi->render(); ?>

<?php do_action( 'es_after_features_tab' );
