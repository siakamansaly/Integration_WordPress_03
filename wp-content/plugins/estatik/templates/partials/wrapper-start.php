<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = get_option( 'template' );

global $post;

if ( is_front_page() ) {
	$post_id = get_option('page_on_front');
} else {
	$post_id = ! empty( $post ) ? $post->ID : null;
}

$post_class = null;

switch( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	case 'twentyfifteen' :
		echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
		break;
	case 'twentysixteen' :
    case 'perth':
		echo '<div id="primary" class="content-area ' . $template . '"><main id="main" class="site-main" role="main">';
		break;
    case 'twentyseventeen' :
        echo '<div class="wrap twentyseventeen"><div id="primary" class="content-area">';
        break;
    case 'twentyten' :
        echo '<div id="container"><div id="content" role="main">';
        break;
    case 'Divi' :
		echo '<div id="main-content"><div class="container"><div id="content-area" class="clearfix divi"><div id="left-area">';
		break;
    case 'total':
        echo '<div class="ht-container"><div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
        break;
    case 'giga-store':
        echo '<div class="row container rsrc-content"><div class="col-md-9 rsrc-main">';
        break;
    case 'rectangulum':
        echo '<div id="content" class="site-content clearfix ' . $template . '"><div class="content-right" role="main">';
        break;
	case 'accesspress-lite':
		if ( $post_id ) {
			$post_class = get_post_meta( $post_id, 'accesspresslite_sidebar_layout', true );
		}

		echo '<div class="ak-container">';
		if ( $post_class == 'both-sidebar' ) {
			echo '<div id="primary-wrap" class="clearfix">';
		}

		echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';

		break;
	default :
		echo '<div id="container"><div id="content" role="main">';
		break;
}
