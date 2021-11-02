<?php

/**
 * @var $entity string
 */

if ( $sections = Es_FBuilder_Helper::get_sections( $entity, true, true ) ) : ?>
	<ul class="es-list es-list__styled es-list__sortable js-es-list__sections">
		<?php foreach ( $sections as $key => $section ) : ?>
			<?php require ( Es_Fields_Builder_Page::get_template_path( 'partials/_section' ) ); ?>
		<?php endforeach; ?>
	</ul>
<?php endif;
