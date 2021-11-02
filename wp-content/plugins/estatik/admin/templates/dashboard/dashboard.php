<div class="wrap es-wrap">
    <?php echo es_get_logo(); ?>

    <h1><?php _e( 'Dashboard', 'es-plugin' ); ?></h1>

    <div class="es-dashboard">
        <div class="es-content">
            <ul class="es-dashboard-menu es-box-wrap">
                <li class="es-box es-box-hover es-box-5">
                    <a href="<?php echo es_admin_property_list_uri(); ?>">
                        <span class="icon icon-dashboard_listings"></span><br/>
                        <?php _e( 'My listings', 'es-plugin' ); ?>
                    </a>
                </li>
                <li class="es-box es-box-hover es-box-5">
                    <a href="<?php echo es_admin_property_add_uri(); ?>">
                        <span class="icon icon-dashboard_addnew"></span><br/>
                        <?php _e( 'Add new', 'es-plugin' ); ?>
                    </a>
                </li>
                <li class="es-box es-box-hover es-box-5">
                    <a href="<?php echo es_admin_data_manager_uri(); ?>">
                        <span class="icon icon-dashboard_manager"></span><br/>
                        <?php _e( 'Data manager', 'es-plugin' ); ?>
                    </a>
                </li>
                <li class="es-box es-box-hover es-box-5">
                    <a href="<?php echo es_admin_fields_builder_uri(); ?>">
                        <span class="icon icon-dashboard_settings"></span><br/>
			            <?php _e( 'Fields Builder', 'es-plugin' ); ?>
                    </a>
                </li>
                <li class="es-box es-box-hover es-box-5">
                    <a href="<?php echo es_admin_settings_uri(); ?>">
                        <span class="icon icon-dashboard_settings"></span><br/>
                        <?php _e( 'Settings', 'es-plugin' ); ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="es-content">
            <h1><?php _e( 'Get support', 'es-plugin' ); ?></h1>
            <div class="es-box es-support-menu">
                <ul>
                    <li class="button-4">
                        <a href="http://estatik.net/estatik-plugin-documentation/" target="_blank" class="es-support-button">
                            <i class="fa fa-compass" aria-hidden="true"></i>
                            <?php _e( 'Step-by-step guide', 'es-plugin' ); ?>
                        </a>
                    </li>
                    <li class="button-4">
                        <a href="https://estatik.net/faq/" target="_blank" class="es-support-button">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                            <?php _e( 'FAQ', 'es-plugin' ); ?>
                        </a>
                    </li>
                    <li class="button-4">
                        <a href="https://estatik.net/support-area/" target="_blank" class="es-support-button">
                            <i class="fa fa-ticket" aria-hidden="true"></i>
                            <?php _e( 'Support Area', 'es-plugin' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="es-box-wrap">
            <?php if ( $shortcodes = Es_Dashboard_Page::get_shortcodes_list() ) : ?>
                <div class="es-content es-content-2 es-shortcodes-scroll">
                    <h1><?php _e( 'Shortcodes', 'es-plugin' ); ?></h1>
                    <div class="es-scroll-list">
                        <ul>
                            <?php foreach ($shortcodes as $shortcode): ?>
                                <li><?php echo $shortcode; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $changelog = Es_Dashboard_Page::get_changelog_list() ) : ?>
                <div class="es-content es-content-2 es-changelog-scroll">
                    <h1><?php _e( 'Changelog', 'es-plugin' ); ?></h1>
                    <div class="es-scroll-list">
                        <ul>
                            <?php foreach ($changelog as $date => $log): ?>
                                <li><b><?php echo ! is_numeric( $date ) ? $date : null; ?></b><p><?php echo $log; ?></p></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( $themes = Es_Dashboard_Page::get_themes_list() ) : ?>
            <div class="es-content">
                <h1 class="es-text-center"><?php _e( 'Choose your theme', 'es-plugin' ); ?></h1>
                <div class="es-themes-slider">
                    <?php foreach ( $themes as $item ) : ?>
                        <div>
                            <a href="<?php echo $item['link']; ?>" target="_blank">
                                <img src="<?php echo $item['image']; ?>" alt="">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="es-content">
        <h1><?php _e( 'Upgrade Options', 'es-plugin' ); ?></h1>

        <ul class="es-box-wrap es-related-services">
            <li class="es-box es-box-hover es-box-3">
                <a href="https://estatik.net/product/estatik-professional/" target="_blank">
                    <span class="icon icon-relatedservices_install"></span><br/>
                    <b class="service-title"><?php _e( 'PRO Pack', 'es-plugin' ); ?></b>
                    <p><?php _e( 'Unlock advanced features like PDF flyer, Map view, Frontend Submission, Agents, Subscriptions, CSV import, White Label, etc.', 'es-plugin' ); ?></p>
                </a>
            </li>
            <li class="es-box es-box-hover es-box-3">
                <a href="https://estatik.net/product/best-offer/" target="_blank">
                    <span class="icon icon-relatedservices_customization"></span><br/>
                    <b class="service-title"><?php _e( 'Developer\'s Pack', 'es-plugin' ); ?></b>
                    <p><?php _e( 'Great deal for developers! Save money and get Estatik PRO with absolutely ALL available Estatik Themes. <b>unlimited</b> unlimited number of websites & get <b>lifetime</b> Estatik updates!', 'es-plugin' ); ?></p>
                </a>
            </li>
            <li class="es-box es-box-hover es-box-3">
                <a href="https://estatik.net/product/estatik-premium-rets/" target="_blank">
                    <span class="icon icon-relatedservices_website"></span><br/>
                    <b class="service-title"><?php _e( 'Premium Pack', 'es-plugin' ); ?></b>
                    <p><?php _e( 'All-inclusive Estatik Package with MLS organic import tool via RETS. Sit back and let us handle everything! Click here to read details.', 'es-plugin' ); ?></p>
                </a>
            </li>
        </ul>
    </div>
</div>
