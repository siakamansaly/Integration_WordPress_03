<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Slideshow_Widget extends Elementor_Es_Base_Widget {
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'es-slideshow-widget';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return _x( 'Estatik Slideshow', 'widget name', 'es-plugin' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'es-icon es-icon_slider';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls() {
        $attr = array(
            'title' => null,
            'layout' => 'horizontal',
            'slider_effect' => 'horizontal',
            'slides_to_show' => 1,
            'filter_data' => array(),
            'prop_ids' => '',
            'show_arrows' => 0,
            'show_labels' => 0,
            'limit' => 20,
            'margin' => 10,
            'price_min' => '',
            'price_max' => '',
        );

        $this->start_controls_section(
            'section_content', array( 'label' => _x( 'Slider settings', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'show_arrows', array(
            'label' => __( 'Show arrows', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $attr['show_arrows'] ? 'yes' : $attr['show_arrows'],
        ) );

        $this->add_control( 'show_labels', array(
            'label' => __( 'Show Labels', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $attr['show_labels'] ? 'yes' : $attr['show_labels'],
        ) );

        $this->add_control( 'layout', array(
            'label' => __( 'Layout', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'default' => $attr['layout'],
            'options' => array(
                'horizontal' => __( 'Horizontal', 'es-plugin' ),
                'vertical' => __( 'Vertical', 'es-plugin' ),
            ),
        ) );

        $this->add_control( 'slider_effect', array(
            'label' => __( 'Slider effect', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'default' => $attr['slider_effect'],
            'options' => array(
                'horizontal' => __( 'Horizontal', 'es-plugin' ),
                'vertical' => __( 'Vertical', 'es-plugin' ),
            ),
        ) );

        $this->add_control( 'slides_to_show', array(
            'label' => __( 'Slides to show', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'default' => $attr['slides_to_show'],
        ) );

        $this->add_control( 'margin', array(
            'label' => __( 'Space between slides', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'default' => $attr['margin'],
        ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'filter', array( 'label' => _x( 'Filter', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'filter_data', array(
            'label' => __( 'Filter data', 'es-plugin' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'default' => $attr['filter_data'],
            'options' => get_terms( array(
                'taxonomy' => Es_Taxonomy::get_taxonomies_list(),
                'hide_empty' => false,
                'fields' => 'id=>name'
            ) ),
        ) );

        $this->add_control( 'price_min', array(
            'label' => __( 'Min Price', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'default' => $attr['price_min'],
        ) );

        $this->add_control( 'price_max', array(
            'label' => __( 'Max Price', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'default' => $attr['price_max'],
        ) );

        $this->add_control( 'prop_ids', array(
            'label' => __( 'Listings IDs', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $attr['prop_ids'],
        ) );

        $this->add_control( 'limit', array(
            'label' => __( 'Limit', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'default' => $attr['limit'],
        ) );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $instance = $this->get_settings_for_display();
        $instance['show_arrows'] = ! empty( $instance['show_arrows'] ) && $instance['show_arrows'] === 'yes' ? 1 : 0;

        if ( ! empty( $instance['filter_data'] ) ) {

            $terms = array();

            foreach ( $instance['filter_data'] as $term_id ) {
                $term = get_term( $term_id );
                $taxonomy = str_replace( 'es_', '', $term->taxonomy );

                $terms[ $taxonomy ][] = $term->name;
            }

            if ( ! empty( $terms ) ) {
                foreach ( $terms as $key => $term_list ) {
                    $terms[ $key ] = implode( ',', $term_list );
                }

                $instance = array_merge( $instance, $terms );
            }
        }

        $slideshow = new Es_Property_Slideshow_Shortcode();
        echo $slideshow->build( $instance );
    }
}
