<div class="es-powered">
	<p>
		<?php $url = 'https://estatik.net';

		printf( wp_kses( __( 'Powered by <a href="%s" target="_blank">Estatik</a>', 'es-plugin' ), array(
			'a' => array( 'href' => array(), 'target' => array() ) )
		), esc_url( $url ) ); ?>
	</p>
</div>
