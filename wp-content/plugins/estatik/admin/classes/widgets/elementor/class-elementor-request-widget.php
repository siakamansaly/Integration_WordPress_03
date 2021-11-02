<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Request_Widget extends Elementor_Es_Base_Widget {
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
        return 'es-request-widget';
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
        return _x( 'Estatik Request Form', 'widget name', 'es-plugin' );
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
        return 'es-icon es-icon_request-form';
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
            'title' => __( 'Learn more about this property', 'es-plugin' ),
            'message' => __( 'Hi, I`m interested in the property. Please send me more information about it. Thank you!', 'es-plugin' ),
            'disable_name' => false,
            'disable_tel' => false,
            'custom_email' => false,
            'from_name' => '',
            'send_to' => Es_Request_Widget::SEND_ADMIN,
            'from_email' => '',
            'subject' => __( 'Estatik Request Info from', 'es-plugin' ),
        );

        $this->start_controls_section(
            'section_content', array( 'label' => _x( 'Content', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'title', array(
            'label' => __( 'Title', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['title'],
        ) );

        $this->add_control( 'message', array(
            'label' => __( 'Message', 'es-plugin' ),
            'type' => Controls_Manager::TEXTAREA,
            'default' => $defaults['message'],
        ) );

        $this->add_control( 'disable_name', array(
            'label' => __( 'Disable name field', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $defaults['disable_name'] ? 'yes' : $defaults['disable_name'],
        ) );

        $this->add_control( 'disable_tel', array(
            'label' => __( 'Disable phone field', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $defaults['disable_tel'] ? 'yes' : $defaults['disable_tel'],
        ) );

        $this->add_control( 'send_to', array(
            'label' => __( 'Recipients', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'default' => $defaults['send_to'],
            'options' => Es_Request_Widget::get_send_to_list()
        ) );

        $this->add_control( 'custom_email', array(
            'label' => __( 'Custom emails', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['custom_email'],
        ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'email_settings', array( 'label' => _x( 'Email settings', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'subject', array(
            'label' => __( 'Subject', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['subject'],
            'placeholder' => __( 'Email subject', 'es-plugin' )
        ) );

        $this->add_control( 'from_name', array(
            'label' => __( 'From name', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['from_name'],
            'placeholder' => __( 'Sender name', 'es-plugin' )
        ) );

        $this->add_control( 'from_email', array(
            'label' => __( 'From email', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
            'default' => $defaults['from_email'],
            'placeholder' => __( 'Sender email', 'es-plugin' )
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
        include es_locate_template( 'widgets/es-request-widget.php', 'admin' );
    }
}
