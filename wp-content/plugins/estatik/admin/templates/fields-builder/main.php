<?php

/**
 * @file
 * Fields builder base template.
 */

?>

<div class='wrap es-wrap es-fbuilder-wrap es-settings-wrap'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Fields builder', 'es-plugin' ); ?></h1>

    <p><?php _e( 'Here you can add new preset fields that you can find in Add new listing. To add new field, please configure the fields below and click Create button.', 'es-plugin' ); ?></p>

    <?php $messenger = new Es_Messenger( 'fbuilder', false ); $messenger->render_messages(); ?>

    <?php if ( $tabs = Es_Fields_Builder_Page::get_tabs() ): ?>
        <div class='nav-tab-wrapper es-box'>
            <ul class="nav-tab-menu">
                <?php foreach ( $tabs as $key => $tab ): ?>
                    <li><a href='#es-<?php echo $key; ?>-tab'><?php echo $tab['label'] ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php foreach ( $tabs as $key => $tab ): ?>
                <div id='es-<?php echo $key; ?>-tab' class="es-tab">
                    <?php require_once $tab['template']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
