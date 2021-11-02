<?php

/**
 * @var Es_Settings_Container $es_settings
 */

get_header(); $template = get_option( 'template' ); global $wp_query, $wp_taxonomies; ?>

<?php do_action( 'es_before_content' ); ?>

    <div class="es-wrap">

        <header class="page-header">
            <h1 class="page-title">
                <?php if ( $wp_query->is_tax() ) :
                    $term = get_queried_object();
                    printf( __( '%s properties', 'es-plugin' ), $term->name );
                else : ?>
                    <?php echo ! empty( $title ) ? $title : __( 'Properties', 'es-plugin' ); ?>
                <?php endif; ?>
            </h1>
        </header><!-- .page-header -->

        <?php do_action( 'es_before_content_list' ); ?>

	    <?php do_action( 'es_archive_sorting_dropdown' ); ?>

        <div class="<?php es_the_list_classes(); ?>">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post();
                    es_load_template( 'content-archive.php' );
                endwhile; ?>
            <?php else: ?>
                <p style="font-size: 14px;"><?php _e( 'Nothing to display.', 'es-plugin' ); ?></p>
            <?php endif; ?>
        </div>

        <?php do_action( 'es_after_content_list' ); ?>
    </div>

<?php echo es_the_pagination( $wp_query ); ?>

<?php do_action( 'es_after_content' ); ?>

<?php get_footer();
