<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Es_Template_Loader
 */
class Es_Template_Loader extends Es_Object
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        add_action( 'es_before_content', array( $this, 'before_content' ) );
        add_action( 'es_after_content', array( $this, 'after_content' ) );
    }

    /**
     * @inheritdoc
     */
    public function filters()
    {
        add_filter( 'template_include', array( $this, 'template_loader' ) );
        add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );
    }

    /**
     * Filter function for loading property post type templates.
     *
     * @param $template
     * @return mixed
     */
    public function template_loader( $template )
    {
	    // Hierarchical templates array.
	    $find = array();

	    $property = es_get_property( null );
	    $type = $property::get_post_type_name();
	    $cur_type = sanitize_key( filter_input( INPUT_GET, 'post_type' ) );

	    // Template for archive properties page.
	    if( is_post_type_archive( $type ) && ! is_search() ) {
		    $file = 'archive-' . $type . '.php';

		    // Template for property taxonomies page.
	    } else if ( is_tax( get_object_taxonomies( $type ) ) ) {
		    $file = 'taxonomy.php';

		    // If search page.
	    } else if ( is_search() && ! is_admin() &&  $cur_type == $type ) {
		    $file = 'search.php';
	    }

	    if ( ! empty( $file ) ) {
		    $find[] = 'estatik/' . $file;
		    $find[] = static::get_template_path() . $file;
	    }

	    if ( ! empty( $find ) ) {
		    $template = locate_template( array_unique( $find ) );

		    if ( ! $template ) {
			    $template = static::get_template_path() . $file;
		    }
	    }

	    return $template;
    }

    /**
     * @see do_action( 'es_before_content' ) hook.
     */
    public function before_content()
    {
        es_load_template( 'partials/wrapper-start.php' );
    }

    /**
     * @see do_action( 'es_after_content' ) hook.
     */
    public function after_content()
    {
        es_load_template( 'partials/wrapper-end.php' );
    }

    /**
     * Added new classes for post wrap.
     *
     * @param $classes
     * @param $class
     * @param $post
     * @return mixed
     */
    public function post_class( $classes, $class, $post )
    {
        if ( is_archive() || is_search() ) {
            $key = array_search( 'hentry', $classes );
            $post = get_post( $post );
            $template = get_option( 'template' );

            if ( $key && $post->post_type == Es_Property::get_post_type_name() && ( $template == 'twentyfifteen' || $template == 'twentyfourteen' || $template == 'twentysixteen') ) {
                unset($classes[$key]);
            } else {
                $classes[] = 'entry-content';
            }
        }
        return $classes;
    }

    /**
     * Return template plugin path.
     *
     * @return string
     */
    public static function get_template_path()
    {
        return apply_filters( 'es_get_templates_path', ES_TEMPLATES );
    }
}
