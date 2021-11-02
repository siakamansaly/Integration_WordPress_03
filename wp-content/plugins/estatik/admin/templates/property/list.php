<?php
/**
 * @file list.php
 * Properties custom admin list page.
 */
?>

<div class='wrap es-wrap es-property-list-page'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'My listings', 'es-plugin' ); ?>
        <a href="<?php echo es_admin_property_add_uri(); ?>" class="es-button es-button-green es-button-add"><?php _e( 'Add new', 'es-plugin' ); ?></a></h1>

    <?php
    /**
     * Action for render properties custom filter.
     */
    do_action( 'es_admin_properties_list_filter' ); ?>


    <?php
    /**
     * Action for render properties list table.
     */
    do_action( 'es_admin_properties_list_table' ); ?>

</div>
