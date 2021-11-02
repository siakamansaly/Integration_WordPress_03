<?php

/**
 * Class Es_Property_Clone
 */
class Es_Post_Duplicate
{
    /**
     * @var int Post ID for duplication.
     */
    protected $_post_id;
    /**
     * @var array|null|WP_Post
     */
    protected $_post;
    /**
     * @var array Array of clone handler options.
     */
    protected $_options;

    /**
     * Es_Property_Duplicate constructor.
     *
     * @param $post_id
     *    Post ID.
     * @param array $options
     *
     * @throws Exception
     */
    public function __construct( $post_id, $options = array() )
    {
        if ( ! $this->_post = get_post( $post_id ) ) {
            throw new Exception( __( 'Property for clone doesn\'t exists.', 'es-plugin' ) );
        }

        $this->_post_id = $post_id;
        $this->_options = $options;
    }

    /**
     * Make post clone.
     *
     * @return void
     */
    public function makeClone()
    {
        // Unset post ID from array.
        unset( $this->_post->ID );

        // Cloned post title.
        $title = $this->_post->post_title . ' - ' . __( 'Copy', 'es-plugin' );

        // Set title for copied post.
        $this->_post->post_title = apply_filters( 'es_post_clone_title', $title, $this->_post->ID );

        $post_id = wp_insert_post( $this->_post );

        if ( $post_id ) {
            // Get all metadata.
            $meta = get_post_meta( $this->_post_id );
            // Get all terms objects.
            $terms = wp_get_object_terms( array( $this->_post_id ), get_object_taxonomies( Es_Property::get_post_type_name() ) );

            // Copy all metadata with new post ID.
            if ( $meta ) {
                foreach ( $meta as $meta_key => $values ) {
                    if ( ! $values ) continue;

                    foreach ( $values as $value ) {
                        add_post_meta( $post_id, $meta_key, is_serialized( $value ) ? unserialize( $value ) : $value );
                    }
                }
            }

            // Copy all terms of the post.
            if ( $terms ) {
                foreach ($terms as $term) {
                    wp_set_object_terms( $post_id, array( $term->term_id ), $term->taxonomy, true );
                }
            }
        }
    }
}
