<?php
/**
 *	Register routes 
 */
add_filter( 'etc/add/elementor/widgets', 'etc_elementor_widgets_routes' );
function etc_elementor_widgets_routes( $routes ) {

	$routes[] = array(
		'ETC\App\Controllers\Elementor\Special_Offer',
		'ETC\App\Controllers\Elementor\Banner',
		'ETC\App\Controllers\Elementor\Carousel',
		'ETC\App\Controllers\Elementor\Categories_lists',
		'ETC\App\Controllers\Elementor\Categories',
		'ETC\App\Controllers\Elementor\Category',
		'ETC\App\Controllers\Elementor\Countdown',
		'ETC\App\Controllers\Elementor\Fancy_Button',
		'ETC\App\Controllers\Elementor\Follow',
		'ETC\App\Controllers\Elementor\Instagram',
		'ETC\App\Controllers\Elementor\Looks',
		'ETC\App\Controllers\Elementor\Menu',
		'ETC\App\Controllers\Elementor\Menu_List',
		'ETC\App\Controllers\Elementor\Portfolio_Recent',
		'ETC\App\Controllers\Elementor\Portfolio',
		'ETC\App\Controllers\Elementor\Twitter',
		'ETC\App\Controllers\Elementor\Title',
		'ETC\App\Controllers\Elementor\Team_Member',
		'ETC\App\Controllers\Elementor\Slider',
		'ETC\App\Controllers\Elementor\Scroll_Text',
		'ETC\App\Controllers\Elementor\Products',
		'ETC\App\Controllers\Elementor\Blog',
		'ETC\App\Controllers\Elementor\Blog_Carousel',
		'ETC\App\Controllers\Elementor\Blog_List',
		'ETC\App\Controllers\Elementor\Blog_Timeline',
		'ETC\App\Controllers\Elementor\Icon_Box',
	);

	if( function_exists( 'etheme_get_option' ) ){

		if ( etheme_get_option( 'enable_brands' ) ) {
			$routes[] = array(
				'ETC\App\Controllers\Elementor\Brands_List',
				'ETC\App\Controllers\Elementor\Brands',
			);
		}

		if ( etheme_get_option( 'testimonials_type' ) ) {
			$routes[] = array(
				'ETC\App\Controllers\Elementor\Testimonials',
			);
		}
	}

	return $routes;
}

/**
 *	Register controls 
 */
add_filter( 'etc/add/elementor/controls', 'etc_elementor_controls' );
function etc_elementor_controls( $controls ) {

	$controls['etheme-icon-control'] = array(
		'class'	=>	'ETC\App\Controllers\Elementor\Controls\Icon_Control',
	);

	return $controls;
}


/**
 *	Icon control
 */
add_filter( 'etc/add/elementor/control/icon', 'etc_elementor_icon_control' );
function etc_elementor_icon_control( $icon ) {

	$new_icon = array(
		'themify'			=>	 'Themify',
		'7-stroke'			=>	 '7 Stroke',
		'eicons'			=>	 'Eicons',
		'font-awesome'		=>	 'Font Awesome',
		'linea'				=>	 'Linea',
		'material-design'	=>	 'Material Design',
		'simple-line'		=>	 'Simple Line',
		'xstore-bold'		=>	 'Xstore Bold',
		'xstore-regular'	=>	 'Xstore Regular',
	);


	return array_merge( $new_icon, $icon );
}
