<div class='wrap es-wrap'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Data manager', 'es-plugin' ); ?></h1>

    <?php if ( $tabs = Es_Data_Manager_Page::get_tabs() ): ?>
        <div class='nav-tab-wrapper es-box es-data-manager-wrap'>
            <ul class="nav-tab-menu">
                <?php foreach ( $tabs as $key => $tab ): ?>
                    <li><a href='#es-<?php echo $key; ?>-tab'><?php echo $tab['label'] ?></a></li>
                <?php endforeach; ?>
            </ul>

            <?php foreach ($tabs as $key => $tab): ?>
                <div id='es-<?php echo $key; ?>-tab' class="es-tab">
                    <?php require_once $tab['template']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="es-confirm-popup">
        <?php _e( 'Are you sure you want to delete it?', 'es-plugin' ) ?>
    </div>
</div>
