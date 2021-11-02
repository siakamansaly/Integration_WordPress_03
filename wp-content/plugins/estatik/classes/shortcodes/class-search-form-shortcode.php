<?php

if ( ! defined( 'WPINC' ) ) die;

/**
 * Class Es_Search_Page_Shortcode.
 */
class Es_Search_Form_Shortcode extends Es_Shortcode
{
    protected $_widget_name = 'Es_Search_Widget';

	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Search form', 'es-plugin' );
	}

    /**
     * Function used for build shortcode.
     * @see add_shortcode
     *
     * @param array $atts Shortcode attributes array.
     *
     * @return mixed
     */
    public function build( $atts = array() )
    {
        $atts = wp_parse_args( $atts, $this->get_shortcode_default_atts() );
        $atts['fields'] = explode( ',', $atts['fields'] );
        $instance = $atts;

        ob_start();

        include es_locate_template( 'widgets/es-search-widget.php', 'admin' );

        return ob_get_clean();
    }

    /**
     * @inheritdoc
     */
    public function get_shortcode_default_atts()
    {
    	global $es_settings;
        return array(
            'fields' => implode( ',', Es_Search_Widget::get_widget_fields() ), // Fields separated by comma.
            'title' => null, // Widget title.
            'layout' => 'vertical', // Also *vertical* is available.
            'page_id' => $es_settings->search_page_id,
            'save_search_button' => $es_settings->search_page_id,
        );
    }

    /**
     * Return shortcode name.
     *
     * @return string
     */
    public function get_shortcode_name()
    {
        return 'es_search_form';
    }
}
