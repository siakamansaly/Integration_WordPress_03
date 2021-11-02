<?php global $es_settings; ?>
<div class="es-demo__wrap es-demo__wrap--pages">
	<div class="es-demo__content">
		<?php require_once Es_Demo_Content_Page::get_partials_template( 'step-pagination' ); ?>

        <form action="" method="post" class="js-demo-form">
            <div class="es-step es-step__first active">
                <h1><?php _e( 'Step 1. Estatik Pages Setup', 'es-plugin' ); ?></h1>
                <p><?php _e( 'Please select the pages you want to setup below and click Next step to continue. Estatik will create a few listings with demo content and ready-to-use page you will select below.', 'es-plugin' ); ?></p>

                <div class="es-demo__pages-list-wrap">
                    <ul class="js-pages-list">
                        <?php foreach ( Es_Demo_Setup::get_pages_list() as $key => $item ): ?>
                            <li <?php echo ! empty ( $item['disabled'] ) ? 'class="disabled"' : null; ?>>
                                <span><?php echo $item['title']; ?></span>
                                <?php if ( ! empty ( $item['disabled'] ) ) : ?>
                                    <img src="<?php echo ES_ADMIN_IMAGES_URL . 'pro.png'; ?>">
                                <?php endif;

                                $checked = ! empty( $item['checked'] ) ? 'checked' : false; ?>
                                <input class="js-checked-input <?php echo $checked; ?>" type="checkbox" name="es_demo[page][]" value="<?php echo $key; ?>"/>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="#" class="es-button es-button-green js-switch-step" data-switch-step=".es-step__second"><?php _e( 'Next step', 'es-plugin' ); ?> ></a>
                </div>
            </div>
            <div class="es-step es-step__second">
                <h1><?php _e( 'Step 2. Setup keys', 'es-plugin' ); ?></h1>
                <p><?php _e( 'Add your Google Map API key and Recaptcha keys below. It\'s required for Search and Request info widgets.', 'es-plugin' ); ?></p>

                <div class="es-demo__container">
                    <div class="es-field__flex">
                        <label for="es-gmap-field"><?php _e( 'Google Map API Key' ); ?>:</label>
                        <input value="<?php echo ! empty( $es_settings->google_api_key ) ? $es_settings->google_api_key : ''; ?>" type="text" id="es-gmap-field" name="es_demo[keys][google_api_key]"/>
                        <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key?hl=en"><?php _e( 'Get My Key', 'es-plugin' ); ?></a>
                    </div>

                    <div class="es-field__flex">
                        <label for="es-recaptcha-sitekey-field"><?php _e( 'Google Recaptcha SiteKey' ); ?>:</label>
                        <input value="<?php echo ! empty( $es_settings->recaptcha_site_key ) ? $es_settings->recaptcha_site_key : ''; ?>" type="text" id="es-recaptcha-sitekey-field" name="es_demo[keys][recaptcha_site_key]"/>
                        <a target="_blank" href="https://www.google.com/recaptcha/admin#list"><?php _e( 'Get My Key', 'es-plugin' ); ?></a>
                    </div>

                    <div class="es-field__flex">
                        <label for="es-recaptcha-secretkey-field"><?php _e( 'Google Recaptcha SecretKey' ); ?>:</label>
                        <input value="<?php echo ! empty( $es_settings->recaptcha_secret_key ) ? $es_settings->recaptcha_secret_key : ''; ?>" type="text" id="es-recaptcha-secretkey-field" name="es_demo[keys][recaptcha_secret_key]">
                        <a target="_blank" href="https://www.google.com/recaptcha/admin#list"><?php _e( 'Get My Key', 'es-plugin' ); ?></a>
                    </div>
                </div>

                <div class="es-demo__navigation">
                    <a href="" class="es-button es-button-gray js-switch-step" data-switch-step=".es-step__first">< <?php _e( 'Back', 'es-plugin' ); ?></a>
                    <a href="" class="es-button es-button-green js-switch-step" data-switch-step=".es-step__third"><?php _e( 'Next step', 'es-plugin' ); ?> ></a>
                </div>
            </div>
            <div class="es-step es-step__third">
                <h1><?php _e( 'Step 3. Demo listings setup', 'es-plugin' ); ?></h1>
                <p><?php _e( 'To import demo listings, please click on Import button or click Finish if you want to skip this step.', 'es-plugin' ); ?></p>

                <div class="es-demo__container">
                    <div class="es-progress-container">
                        <svg class="es-progress" style="margin-top: 10px;"></svg>

                        <div class="es-scroll-container">
                            <ul class="es-logger-container"></ul>
                        </div>
                    </div>

                    <input type="hidden" name="es_demo[demo]" value="" class="js-demo-field">
                    <a href="" class="es-button es-button-green js-import-demo"><?php _e( 'Import', 'es-plugin' ); ?></a>
                </div>

                <div class="es-demo__navigation">
                    <a href="" class="es-button es-button-gray js-switch-step" data-switch-step=".es-step__second">< <?php _e( 'Back', 'es-plugin' ); ?></a>
                    <a href="" class="es-button es-button-green js-setup-btn"><?php _e( 'Finish', 'es-plugin' ); ?></a>
                </div>
            </div>
            <input type="hidden" name="action" value="es_demo_setup"/>
            <?php echo wp_nonce_field( 'es_demo_setup', 'es_demo_setup' ); ?>
        </form>
	</div>
</div>
