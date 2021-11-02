<?php update_option( 'es_demo_executed', 1 ); global $es_settings; ?>

<div class="es-demo__wrap es-demo__wrap--pages">
    <div class="es-demo__content es-demo__finish">

        <img src="<?php echo ES_ADMIN_IMAGES_URL . 'success.png'; ?>"/>
        <h1><?php _e( 'You did it!', 'es-plugin' ); ?></h1>
        <p><?php _e( 'What to do next:', 'es-plugin' ); ?></p>

        <div class="es-demo__finished-links">
            <ul>
                <li>
                    <a href="<?php echo es_listings_link(); ?>">
                        <span class="icon icon-demo-listings"></span><br>
                        <?php _e( 'View demo listings', 'es-plugin' ); ?>
                    </a>
                </li>

                <li>
                    <a href="<?php echo es_admin_property_add_uri(); ?>">
                        <span class="icon icon-dashboard_addnew"></span><br>
                        <?php _e( 'Add my first listing', 'es-plugin' ); ?>
                    </a>
                </li>

                <?php if ( empty( $es_settings->enable_white_label ) ) : ?>
                <li>
                    <a href="<?php echo es_admin_dashboard_uri(); ?>">
                        <span class="icon icon-dashboard"></span><br>
                        <?php _e( 'Go to estatik dashboard', 'es-plugin' ); ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</div>
