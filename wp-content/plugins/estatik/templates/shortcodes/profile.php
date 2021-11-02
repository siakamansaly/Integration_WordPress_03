<?php

/**
 * @var $context Es_Profile_Shortcode
 */

$logger = new Es_Messenger( 'es_profile' ); ?>

<div class="es-profile__wrapper js-es-tabs es-profile__wrapper--<?php echo $atts['layout']; ?>">

	<?php $logger->render_messages(); ?>

	<?php if ( $tabs = $context::get_tabs() ) : ?>
		<div class="es-profile__tabs-wrapper js-es-tabs__links et_smooth_scroll_disabled">
			<ul>
				<?php foreach ( $tabs as $tab => $options ) :
					$id = ! empty( $options['id'] ) ? $options['id'] : $tab;
					$icon = ! empty( $options['icon'] ) ? $options['icon'] : null; ?>
                    <?php if ( ( isset( $options['can_view'] ) && $options['can_view'] ) || ( ! isset( $options['can_view'] ) ) ) : ?>
					    <li><a href="#<?php echo $id; ?>"><?php echo $icon . $options['label']; ?></a></li>
                    <?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( $tabs ) : ?>
		<div class="es-profile__tab-content-wrapper js-es-tabs__content">
			<?php foreach ( $tabs as $tab => $options ) : $id = ! empty( $options['id'] ) ? $options['id'] : $tab; ?>
                <?php if ( ( isset( $options['can_view'] ) && $options['can_view'] ) || ( ! isset( $options['can_view'] ) ) ) : ?>
                    <div class="js-es-tabs__tab es-profile__tab-content es-profile__tab-content--<?php echo $id; ?>" id="<?php echo $id; ?>">
                        <?php do_action( 'es_profile_tab_content', $id ); ?>
                    </div>
                <?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
