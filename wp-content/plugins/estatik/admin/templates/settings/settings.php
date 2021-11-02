<?php global $es_settings; wp_enqueue_style( 'wp-color-picker' ); ?>

<div class='wrap es-wrap es-settings-wrap'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Settings', 'es-plugin' ); ?></h1>

    <form action='' method="POST">

        <div class="es-header-button">
            <span><?php _e( 'Please fill up your settings details and click save to finish', 'es-plugin' ); ?></span>
            <input type="submit" value="<?php _e( 'Save', 'es-plugin' ); ?>">
        </div>

        <?php if ( $tabs = Es_Settings_Page::get_tabs() ): ?>
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
        <?php wp_nonce_field( 'es_save_settings', 'es_save_settings' ); ?>
    </form>
</div>
