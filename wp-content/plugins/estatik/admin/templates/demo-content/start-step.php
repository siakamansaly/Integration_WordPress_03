<?php global $es_settings; ?>
<div class="es-demo__wrap es-demo__wrap--start">
    <div class="es-demo__content">
        <div class="es-demo__logo es-demo__logo--centered">
            <img src="<?php echo ES_ADMIN_IMAGES_URL . 'logo.png'; ?>'" alt="">
        </div>
        <h2 class="shadowed"><?php _e( 'Thank you for choosing Estatik ', 'es-plugin' ); ?></h2>

        <p class="shadowed"><?php _e( 'We are so happy you joined us', 'es-plugin' ); ?>!</p>
        <p class="shadowed"><?php _e( 'Grab your instant <b>5% off on any Estatik products</b> using coupon code below', 'es-plugin'); ?>:</p>

        <div class="es-demo__start-demo-container">
            <div class="es-demo__thank-you">
                <i class="fa fa-star" aria-hidden="true"></i>
                <span class="shadowed"><?php _e( 'THANKYOU', 'es-plugin' ); ?></span>
                <i class="fa fa-star" aria-hidden="true"></i>
            </div>

            <div class="es-demo__start-buttons">

	            <?php if ( empty( $es_settings->enable_white_label ) ) : ?>
                <a href="<?php echo es_admin_dashboard_uri(); ?>" class="es-button es-button-gray"><?php _e( 'Skip demo setup', 'es-plugin' ); ?></a>
	            <?php else: ?>
                <a href="<?php echo es_admin_property_list_uri(); ?>" class="es-button es-button-gray"><?php _e( 'Skip demo setup', 'es-plugin' ); ?></a>
                <?php endif; ?>

                <a href="<?php echo add_query_arg( 'step', 'demo' ); ?>" class="es-button es-button-green"><?php _e( 'Setup DEMO', 'es-plugin' ); ?></a>
            </div>
        </div>
    </div>
</div>
