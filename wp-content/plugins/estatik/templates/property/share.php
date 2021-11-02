<?php

/**
 * @var Es_Settings_Container $es_settings
 */

global $es_settings, $post; ?>

<div class="a2a_kit es-share-wrapper">
	<?php do_action( 'es_before_share_block_buttons' ); ?>
	<?php do_action( 'es_wishlist_add_button', $post->ID ) ?>
	<?php if ( $es_settings->share_facebook ) : ?>
        <a class="a2a_button_facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
	<?php endif; ?>
	<?php if ( $es_settings->share_twitter ) : ?>
        <a class="a2a_button_twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
	<?php endif; ?>
	<?php if ( $es_settings->share_google_plus ) : ?>
        <a class="a2a_button_google_plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
	<?php endif; ?><?php if ( $es_settings->share_linkedin ) : ?>
        <a class="a2a_button_linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
	<?php endif; ?>
	<?php do_action( 'es_after_share_block_buttons' ); ?>
</div>