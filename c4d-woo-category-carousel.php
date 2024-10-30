<?php
/*
Plugin Name: C4D Wocoomerce Category Carousel
Plugin URI: http://coffee4dev.com/
Description: List category by carousel
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-woo-cate-carousel
Version: 2.0.0
*/

define('C4DWCATECAOUSEL_PLUGIN_URI', plugins_url('', __FILE__));

add_action('wp_enqueue_scripts', 'c4d_woo_cate_carousel_safely_add_stylesheet_to_frontsite');
add_shortcode('c4d_woo_cate_carousel', 'c4d_woo_cate_carousel_slider');
add_filter( 'plugin_row_meta', 'c4d_woo_cate_carousel_plugin_row_meta', 10, 2 );

function c4d_woo_cate_carousel_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'forum' => '<a href="http://coffee4dev.com/forums/">Forum</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

foreach (array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description') as $filter) {
    remove_filter($filter, 'wp_filter_kses');
}

foreach (array('term_description', 'link_description', 'link_notes', 'user_description') as $filter) {
    remove_filter($filter, 'wp_kses_data');
}

function c4d_woo_cate_carousel_safely_add_stylesheet_to_frontsite( $page ) {
	if(!defined('C4DPLUGINMANAGER')) {
		wp_enqueue_style( 'c4d-woo-cate-carousel-frontsite-style', C4DWCATECAOUSEL_PLUGIN_URI.'/assets/default.css' );
		wp_enqueue_script( 'c4d-woo-cate-carousel-frontsite-plugin-js', C4DWCATECAOUSEL_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 
	}
	wp_enqueue_style( 'owl-carousel', C4DWCATECAOUSEL_PLUGIN_URI.'/libs/owl-carousel/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-theme', C4DWCATECAOUSEL_PLUGIN_URI.'/libs/owl-carousel/owl.theme.css' );
	wp_enqueue_script( 'owl-carousel-js', C4DWCATECAOUSEL_PLUGIN_URI.'/libs/owl-carousel/owl.carousel.js', array( 'jquery' ), false, true ); 
	wp_localize_script( 'jquery', 'c4d_wp_cate_carousel',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function c4d_woo_cate_carousel_slider($params) {
	$html = '';
	$category 	= isset($params['category']) ? $params['category'] : '9, 35, 36';
	$cateArray 	= explode(',', $category);
	$items 		= array();
	foreach ($cateArray as $key => $cate) {
		$id = get_term_by('term_taxonomy_id', $cate, 'product_cat');

		if ($id) {
			$terms 	= get_terms(array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				// 'include' => $category,
				'parent' => $id->term_id,
				'number' => isset($params['count']) ? $params['count'] : 10
			));
			
			foreach ($terms as $key => $value) {
				$value->content = $value->description;
				$value->title = $value->name;
				$value->permalink = get_term_link( $value->term_id, 'product_cat');

				$items[] = $value;
			}			
		}
	}
	woocommerce_reset_loop();
	wp_reset_postdata();
	ob_start();
 	$template = get_template_part('c4d-woo-category-carousel/templates/default');
	if ($template && file_exists($template)) {
		require $template;
	} else {
		require dirname(__FILE__). '/templates/default.php';
	}
	$html = ob_get_contents();
	$html = do_shortcode($html);
	ob_end_clean();
	return $html;
}


