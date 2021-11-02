<?php

/**
 * Class Es_Inline_Assets.
 */
class Es_Inline_Assets extends Es_Object {

    /**
     * @inheritdoc
     */
    public function actions() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        parent::actions();
    }

    /**
     * Enqueue inline styles.
     *
     * @return void
     */
    public function enqueue_styles() {

	    $filter = apply_filters( 'es_load_inline_styles', true );

	    if ( $filter && ! class_exists( 'Native_Theme' ) && ! class_exists( 'Trendy_Theme' ) && ! class_exists('Project_Theme' ) && ! function_exists( 'ept_register_widgets' ) ) {
            /** @var Es_Settings_Container $es_settings */
            global $es_settings;

            $css = null;

            $css = ".es-btn-orange-bordered, .es-button-orange-corner, .js-es-request-form-show { border: 1px solid {$es_settings->main_color}!important; color:{$es_settings->main_color}; }";
            $css .= ".es-btn-orange-bordered:hover, .es-button-orange-corner:hover, .js-es-request-form-show:hover { background: {$es_settings->main_color}!important; }";
            $css .= ".es-search__wrapper .es-search__field .es-field__wrap .select2 .select2-selection__choice { background: {$es_settings->main_color}!important }";
            $css .= ".es-btn-orange, .es-button-orange { background: {$es_settings->main_color}!important }";
            $css .= ".es-btn-orange:hover { border: 1px solid {$es_settings->main_color}!important; color:{$es_settings->main_color} !important; }";
            $css .= ".es-button-gray { background: {$es_settings->reset_button_color}!important; border: 1px solid {$es_settings->reset_button_color} !important; }";
	        $css .= ".es-widget > div:not(.es-map-property-layout-horizontal, .es-map-property-layout-vertical) { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-listing .es-property-inner:hover { border:1px solid {$es_settings->frame_color}!important }";
            $css .= ".es-layout-3_col .es-property-inner:hover .es-details-wrap, .es-layout-2_col .es-property-inner:hover .es-details-wrap {border:1px solid {$es_settings->frame_color}!important; border-top: 0!important;}";
            $css .= ".es-single .es-share-wrapper a:hover { border:1px solid {$es_settings->frame_color}!important }";
            $css .= ".es-layout-3_col .properties .es-bottom-info, .es-layout-2_col .properties .es-bottom-info { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-layout-3_col .es-details-wrap, .es-layout-2_col .es-details-wrap { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-layout-list .es-price { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-single .es-price { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-single-tabs-wrap ul.es-single-tabs li a:not(.active) { background: {$es_settings->secondary_color}!important }";
            $css .= ".es-list-dropdown li:hover { background: {$es_settings->secondary_color}!important }";

            $css .= ".page-numbers.current, .page-numbers a:hover { color: {$es_settings->main_color}!important }";
            $css .= ".page-numbers li { display: inline-block; }";

            $css .= ".js-es-wishlist-button .fa { color: {$es_settings->main_color} }";
	        $css .= ".es-share-wrapper .js-es-wishlist-button .fa { color: #000 }";
            $css .= ".es-share-wrapper .js-es-wishlist-button.active .fa { color: {$es_settings->main_color}!important }";
            $css .= ".es-profile__wrapper--horizontal li.active { border-top: 3px solid {$es_settings->main_color}!important }";
            $css .= ".es-profile__wrapper--horizontal li.active a { color: {$es_settings->main_color}!important }";
            $css .= ".es-inline-buttons li.es-i-button-green { background: {$es_settings->main_color}!important }";
            $css .= ".es-profile__wrapper--vertical .es-profile__tabs-wrapper li.active { border-right: 3px solid {$es_settings->main_color}!important }";
            $css .= ".es-profile__wrapper--vertical .es-profile__tabs-wrapper li.active a { color: {$es_settings->main_color}!important }";


            wp_add_inline_style('es-front-style', $css);
        }
    }
}
