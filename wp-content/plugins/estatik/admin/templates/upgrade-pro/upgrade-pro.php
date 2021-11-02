<div class='wrap es-wrap'>
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Upload downloaded estatik pro / premium', 'es-plugin' ); ?></h1>

    <form action='' method="POST" enctype="multipart/form-data">

        <div class="es-header-button">
            <span><?php _e( 'Please upload your Estatik Pro / Premium version in zip format and click upload to finish for update to new Version.', 'es-plugin' ); ?></span>
            <input type="submit" value="<?php _e( 'Save', 'es-plugin' ); ?>">
        </div>

        <div class="es-upgrade-wrap es-box">
            <p><label><span class="es-settings-label"><?php _e( 'Upload archive', 'es-plugin' ); ?>:</span>
                    <input type="file" accept="application/zip" required name="file">
                </label></p>

            <?php $msg = new Es_Messenger( 'es_message' ); $msg->render_messages(); ?>
        </div>

        <?php wp_nonce_field( 'es_upgrade_pro', 'es_upgrade_pro' ); ?>
    </form>
</div>
