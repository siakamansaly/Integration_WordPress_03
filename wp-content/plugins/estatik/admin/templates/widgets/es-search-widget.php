<?php

/**
 * Search widget.
 *
 * @var array $instance
 * @var array $args
 * @var array $fields
 * @var Es_Search_Widget $this
 */

$fields = Es_Search_Widget::get_widget_fields();
$page_exists = ! empty( $instance['page_id'] ) && get_post_status( $instance['page_id'] ) == 'publish';
$handler = $page_exists ? get_permalink( $instance['page_id'] ) : esc_url( home_url( '/' ) );
$instance['layout'] = ! empty( $instance['layout'] ) ? $instance['layout'] : 'vertical';

$single_field_class = null;

if ( ! empty( $instance['fields'] ) ) {
    $temp_fields = array_filter( $instance['fields'] );
    $single_field_class = count( $temp_fields ) == 1 && in_array( 'address', $temp_fields ) ? 'es-search__wrapper--address-only' : null;
}
$args = ! empty( $args ) ? $args : array();
$args = wp_parse_args( $args, array(
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
) );

echo ! empty( $args['before_widget'] ) ? $args['before_widget'] : ''; ?>

    <div class="es-search__wrapper es-search__wrapper--<?php echo $instance['layout'] . ' ' . $single_field_class; ?>">

        <?php if ( ! empty( $instance['title'] ) ) : ?>
            <?php echo $args['before_title']; ?><?php echo apply_filters( 'widget_title', $instance['title'] ); ?><?php echo $args['after_title']; ?>
        <?php endif; ?>

        <form action="<?php echo $handler; ?>" role="search" method="get">

            <?php do_action( 'es_before_search' );

            if ( ! $page_exists ) : ?>
                <input type="hidden" name="s"/>
            <?php else: ?>
                <?php if ( ! get_option( 'permalink_structure' ) ) : ?>
                    <input type="hidden" name="page_id" value="<?php echo $instance['page_id']; ?>"/>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( ! empty( $instance['fields'] ) ) : ?>
                <?php foreach ( $instance['fields'] as $name ) : ?>
                    <?php if ( in_array( $name, $fields ) ) : ?>
                        <div class="es-search__field es-search__field--<?php echo $name; ?>">
                            <?php do_action( 'es_search_widget_render_field', $name, $instance ); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ( ! $page_exists ) : ?>
                <input type="hidden" name="post_type" value="<?php echo Es_Property::get_post_type_name(); ?>"/>
            <?php endif; ?>

            <div class="es-search__buttons">
                <div class="es-button__wrap">
                    <input type="reset" class="es-button es-button-gray" value="<?php _e( 'Reset', 'es-plugin' ); ?>"/>
                </div>
                <div class="es-button__wrap">
                    <input type="submit" class="es-button es-button-orange-corner" value="<?php _e( 'Search', 'es-plugin' ); ?>"/>
                </div>
	            <?php if ( ! empty( $instance['save_search_button'] ) ) : ?>
                    <div class="es-button__wrap">
			            <?php if ( get_current_user_id() ) : ?>
                            <input type="button" class="es-button es-button-green-corner js-es-save-search" value="<?php _e( 'Save search', 'es-plugin' ); ?>"/>
			            <?php else: ?>
                            <input type="button" class="es-button es-button-green-corner js-es-login-form" value="<?php _e( 'Save search', 'es-plugin' ); ?>"/>
			            <?php endif; ?>
                    </div>
	            <?php endif; ?>
            </div>

            <div class="es-search__messages js-es-search__messages"></div>

            <?php do_action( 'es_after_search' ); ?>

        </form>

    </div>
<?php echo ! empty( $args['after_widget'] ) ? $args['after_widget'] : '';
