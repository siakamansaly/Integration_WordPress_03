<?php
/**
 * @var Es_Property $es_property
 * @var Es_Settings_Container $es_settings
 */

$es_property = es_get_property( get_the_ID() );
global $es_settings; ?>

<?php do_action( 'es_before_single_content' ); ?>
    <div class="es-wrap">
        <div class="es-single es-single-<?php echo $es_settings->single_layout; ?>">

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2>
                    <div class="es-cat-price">
                        <?php es_the_categories( '<span class="es-category-items">', '', '</span>' ) ?>
                        <div class="es-price__wrap">
	                        <?php es_the_formatted_price( '<span class="es-price">', '</span>' ); ?>
                        </div>
	                    <?php es_the_property_field( 'price_note', '<span class="es-price-note">', '</span>' ); ?>
                    </div>
                </h2>

                <?php es_the_address( '<div class="es-address">', '</div>' ); ?>

                <?php do_action( 'es_single_tabs' ); ?>

                <div class="es-info clearfix" id="es-info">
                    <?php do_action( 'es_single_info' ); ?>
                </div>

                <div class="es-tabbed">

                    <?php if ( $sections = Es_Property_Single_Page::get_sections() ) : ?>
                        <?php foreach ( $sections as $id => $section ) : ?>
                            <?php if ( 'es-info' == $id ) continue; ?>
                            <?php if ( ! empty( $section['render_action'] ) ) : ?>
                                <?php do_action( $section['render_action'], $id, $section ); ?>
                            <?php else: ?>
                                <?php do_action( 'es_single_render_tab', $id, $section ); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php do_action( 'es_property_after_tabs', $es_property ); ?>

                </div>

                <?php do_action( 'es_single_top_button' ); ?>
            </div>
        </div>
    </div>
<?php do_action( 'es_after_single_content' );
