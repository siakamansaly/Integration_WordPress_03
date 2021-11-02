<?php

/**
 * @var $tab array
 */

$entity = $tab['entity']; ?>

<div class="es-fbuilder__wrap">
    <div class="es-fbuilder__form">
        <?php require_once( Es_Fields_Builder_Page::get_template_path( 'forms/field-form' ) ); ?>
    </div>

    <div class="es-fbuilder__fields">
        <?php require_once( Es_Fields_Builder_Page::get_template_path( 'partials/_fields-list' ) ); ?>
    </div>
</div>
