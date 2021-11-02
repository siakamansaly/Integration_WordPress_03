<?php if ( $tabs = Es_Property_Single_Page::get_tabs() ) : ?>
    <?php do_action( 'es_before_property_tabs' ); ?>
    <div class="es-single-tabs-wrap">
        <ul class="es-single-tabs">
            <?php foreach ( $tabs as $id => $label ) : ?>
                <li><a href="#<?php echo $id; ?>" class="es-tab-<?php echo $id; ?>"><?php echo $label; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php do_action( 'es_after_property_tabs' ); ?>
    </div>
<?php endif;
