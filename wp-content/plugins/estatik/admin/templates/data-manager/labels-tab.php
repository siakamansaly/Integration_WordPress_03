<?php do_action( 'es_before_labels_tab' ); ?>

<?php $dmi = new Es_Data_Manager_Label_Item(); $dmi->render(); ?>

<?php do_action( 'es_after_labels_tab' );
