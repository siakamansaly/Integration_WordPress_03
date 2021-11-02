<?php

/**
 * @var $step string
 */

$demo_active = $step == 'demo' ? 'active' : 'finished'; ?>

<div class="es-demo__pagination-wrap">
	<ul>
		<li class="active" data-step="es-step__first">
			<span class="circle circle-gray">
				<span>1</span>
			</span>
			<span class="label"><?php _e( 'Pages setup', 'es-plugin' ); ?></span>
		</li>
		<li data-step="es-step__second">
			<span class="circle circle-gray active"><span>2</span></span>
			<span class="label"><?php _e( 'Setup keys', 'es-plugin' ); ?></span>
		</li>
		<li data-step="es-step__third">
			<span class="circle circle-gray active"><span>3</span></span>
			<span class="label"><?php _e( 'Demo listing', 'es-plugin' ); ?></span>
		</li>
	</ul>
</div>
