<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Search_Widget extends Elementor_Es_Base_Widget {
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
        return 'es-search-widget';
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
        return _x( 'Estatik Search Form', 'widget name', 'es-plugin' );
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
        return 'es-icon es-icon_search-form';
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
        $defaults = array(
            'title' => null,
            'layout' => 'vertical',
            'fields' => null,
            'save_search_button' => null,
            'enable_unit_converter' => null,
            'page_id' => ''
        );

        global $es_settings;

        $pages[] = __( 'WP Search Page', 'es-plugin' );
        $post_pages = get_pages();

        if ( $post_pages ) {
            foreach ( $post_pages as $page ) {
                $pages[ $page->ID ] = $page->post_title;
            }
        }

        $fields = Es_Search_Widget::get_widget_fields();
        $fields_arr = array();

        foreach ( $fields as $field ) {
            $finfo = Es_Property::get_field_info( $field );
            if ( ! empty( $finfo ) ) {
                $fields_arr[ $field ] = ! empty( $finfo['label'] ) ? $finfo['label'] : $field;
            }
        }

        $this->start_controls_section(
            'section_content', array( 'label' => _x( 'Content', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'title', array(
            'label' => __( 'Title', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['title'],
        ) );

        $this->add_control( 'layout', array(
            'label' => __( 'Layout', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'options' => Es_Search_Widget::get_layouts(),
            'default' => $defaults['layout'],
        ) );

        $this->add_control( 'enable_unit_converter', array(
            'label' => __( 'Show unit converter', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $defaults['enable_unit_converter'] ? 'yes' : $defaults['enable_unit_converter'],
        ) );

        $this->add_control( 'save_search_button', array(
            'label' => __( 'Enable save search', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $defaults['save_search_button'] ? 'yes' : $defaults['save_search_button'],
        ) );

        $this->add_control( 'page_id', array(
            'label' => __( 'Search results page', 'es-plugin' ),
            'type' => Controls_Manager::SELECT2,
            'default' => $es_settings->search_page_id,
            'options' => $pages
        ) );

        if ( $this->get_id() ) {
            $settings = $this->get_settings();
            $fields = ! empty( $settings['fields'] ) ? $settings['fields'] : array();
        } else {
            $fields = array();
        }

        foreach ( $fields as $index => $field ) {
            $fields_arr = Es_Object::push_column( array( $field => $fields_arr[ $field ] ), $fields_arr, $index );
        }

        $this->add_control( 'fields', array(
            'label' => __( 'Fields', 'es-plugin' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => 'multiple',
            'default' => '',
            'options' => $fields_arr,
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
        include es_locate_template( 'widgets/es-search-widget.php', 'admin' );
    }
}
