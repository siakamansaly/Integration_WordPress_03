<div class='wrap es-wrap es-migration-wrap'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Migration', 'es-plugin' ); ?></h1>

    <form action='' method="POST" id="es-migrate-form">
        <div class='es-box' style="padding: 1px 15px 1px 15px">
            <p class="es-msg-1">
                <?php _e( 'To update the previous version to major version 3.* you need to migrate your listings 
                    as the new version of the plugin requires. Before doing this, please make sure that your version 
                    of the plugin doesn\'t have custom changes of the code.', 'es-plugin' ); ?>
            </p>
            <p class="es-msg-2 es-hidden">
                <?php _e( 'Migration process may take some time, especially if you have lots of properties added. 
                    Please be patient.', 'es-plugin' ); ?>
            </p>
            <input type="submit" class="es-button es-button-green" value="<?php _e( 'Migrate', 'es-plugin' ); ?>"/>

            <div class="es-progress-container">
                <svg class="es-progress" style="margin-top: 10px;"></svg>

                <div class="es-scroll-container">
                    <ul class="es-logger-container"></ul>
                </div>
            </div>
        </div>
        <input type="hidden" name="action" value="es_migration">
        <?php echo wp_nonce_field( 'es_migration', 'es_migration_arg' ); ?>
    </form>
</div>
