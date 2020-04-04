<?php
namespace ETC\App\Traits;

/**
 * Elementor Trait
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Core
 */
trait Elementor {

	/**
	 * Get brands terms.
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_terms( $taxonomy ) {
		$args = array(
			'taxonomy'      => $taxonomy,
			'hide_empty'    =>	false,
			'include' 		=> 'all',
		);

		$the_query = new \WP_Term_Query($args);
		$list = array();
		$list[] = __( 'Select Option', 'xstore-core' );

		foreach( $the_query->get_terms() as $term ) { 
			$list[$term->term_id] = $term->name;
		}

		return $list;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_products_args() {
		$args = array(
			'post_type'   			=> array( 'product_variation', 'product' ),
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'		=> -1,
		);

		return $args;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_products() {
		$args = self::get_products_args();

		$the_query       = new \WP_Query( $args );
		$list = array();
		$list[] = __( 'Select Option', 'xstore-core' );

		if ( $the_query->have_posts() ) : 
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$list[get_the_ID()] = get_the_title();
			endwhile;
			wp_reset_postdata();
		endif;

		return $list;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_post_pages_args() {
		$args = array(
			'post_type'   			=> array( 'post', 'page' ),
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'		=> -1,
		);

		return $args;
	}

	/**
	 * Get products id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_post_pages() {
		$args = self::get_post_pages_args();

		$the_query       = new \WP_Query( $args );
		$list = array();
		$list[] = __( 'Select Option', 'xstore-core' );

		if ( $the_query->have_posts() ) : 
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$list[get_the_ID()] = get_the_title();
			endwhile;
			wp_reset_postdata();
		endif;

		return $list;
	}

	/**
	 * Get static block id and title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function get_static_blocks() {
		if ( ! function_exists( 'etheme_get_static_blocks' ) ) {
			return;
		}

		$static_blocks = array();
		$static_blocks[] = "--choose--";
		
		foreach ( etheme_get_static_blocks() as $block ) {
			$static_blocks[$block['value']] = $block['label'];
		}

		return $static_blocks;
	}

	/**
	 * Get instagram_api_data.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function instagram_api_data() {
		$api_data = get_option( 'etheme_instagram_api_data' );
		$api_data = json_decode($api_data, true);
		$users    = array( '' => '' );

		if ( is_array($api_data) && count( $api_data ) ) {
			foreach ( $api_data as $key => $value ) {
				$value = json_decode( $value, true );

				$users[$key] = $value['data']['username'];
			}
		}

		return $users;
	}

	/**
	 * Get menu params.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function menu_params() {
		$menus = wp_get_nav_menus();
		$menu_params = array();
		foreach ( $menus as $menu ) {
			$menu_params[$menu->term_id] = $menu->name;
		}

		return $menu_params;
	}

	/**
	 * Get product templates.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public static function product_templates() {

		$content_product_args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'vc_grid_item',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'       => '',
			'author_name'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);

		$content_product_args = get_posts( $content_product_args );
		$product_templates = array();
		foreach ( $content_product_args as $key ) {
			$product_templates[$key->ID] = $key->post_title;
		}

		return $product_templates;
	}
	
	/**
	 * Create new controls for requested widgets
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_slider_params( $control ) {

		$control->add_control(
			'slider_speed',
			[
				'label' 		=> __( 'Slider Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'default' 		=> '',
			]
		);

		$control->add_control(
			'slider_autoplay',
			[
				'label' 		=> __( 'Slider autoplay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$control->add_control(
			'slider_stop_on_hover',
			[
				'label' 		=> __( 'Stop autoplay on mouseover', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> ['slider_autoplay' => 'yes'],
			]
		);

		$control->add_control(
			'slider_interval',
			[
				'label' 		=> __( 'Autoplay speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'description' 	=> __( 'Interval between slides. In milliseconds. Default: 1000', 'xstore-core' ),
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> ['slider_autoplay' => 'yes'],
			]
		);
		
		$control->add_control(
			'slider_loop',
			[
				'label' 		=> __( 'Slider loop', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$control->add_control(
			'hide_buttons',
			[
				'label' 		=> __( 'Hide prev/next buttons', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$control->add_control(
			'pagination_type',
			[
				'label' 		=> __( 'Pagination type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'hide' 		=>	__( 'Hide', 'xstore' ),
					'bullets' 	=>	__( 'Bullets', 'xstore' ),
					'lines' 	=>	__( 'Lines', 'xstore' ),
				]
			]
		);
		
		$control->add_control(
			'hide_fo',
			[
				'label' 		=> __( 'Hide pagination only for', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'' 			=>  __( 'Hide', 'xstore' ),
					'mobile'	=>	__( 'Mobile', 'xstore' ),
					'desktop'	=>	__( 'Desktop', 'xstore' ),
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$control->add_control(
			'default_color',
			[
				'label' => __( 'Pagination default color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' => '#e1e1e1',
			]
		);

		$control->add_control(
			'active_color',
			[
				'label' => __( 'Pagination active color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' => '#222',
			]
		);

		$control->add_control(
			'large',
			[
				'label' 	=>	__( 'Large screens', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	4,
			]
		);

		$control->add_control(
			'notebook',
			[
				'label' 	=>	__( 'On notebooks', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	3,
			]
		);

		$control->add_control(
			'tablet_land',
			[
				'label' 	=>	__( 'On tablet portrait', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	2,
			]
		);

		$control->add_control(
			'mobile',
			[
				'label' 	=>	__( 'On mobile', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	1,
			]
		);

	}	

	/**
	 * Create menu list item widget
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_menu_list_item( $repeater ) {

		$repeater->add_control(
			'divider1',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'use_custom_fonts_title',
			[
				'label' => __( 'Use custom font for title ?', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'title_font_container_fontsize',
			[
				'label' => __( 'Font size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-title-holder .menu-title h2' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'title_font_container_lineheight',
			[
				'label' => __( 'Line height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'default' => 'normal',
				'selectors' => [
					'{{WRAPPER}} .item-title-holder .menu-title h2' => 'line-height: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'title_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .item-title-holder .menu-title h2' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'title_font_container_fontfamily',
			[
				'label' => __( 'Font Family', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .item-title-holder .menu-title h2' => 'font-family: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' 		=>	__( 'Label', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''		=>	esc_html__( 'Select label','xstore-core' ),
					'hot'	=>	esc_html__( 'Hot', 'xstore-core' ),
					'sale'	=>	esc_html__( 'Sale', 'xstore-core' ),
					'new'	=>	esc_html__( 'New', 'xstore-core' ),
				],
			]
		);

		$repeater->add_control(
			'transform',
			[
				'label' 		=>	__( 'Text transform', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'' => '',
					'text-uppercase'	=>	esc_html__( 'Uppercase', 'xstore-core' ),
					'text-lowercase'	=>	esc_html__( 'Lowercase', 'xstore-core' ),
					'text-capitalize'	=>	esc_html__( 'Capitalize', 'xstore-core' ),
				],
			]
		);

		$repeater->add_control(
			'align',
			[
				'label' 		=>	__( 'Horizontal align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'  	=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'),
					'right' 	=>	esc_html__('Right', 'xstore-core'), 
				],
			]
		);

		$repeater->add_control(
			'dividericon',
			[
				'label' => __( 'Title icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'add_icon',
			[
				'label' 		=>	__( 'Add icon ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' 		=>	__( 'Icon library', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
				'svg'			=>	esc_html__( 'Icon', 'xstore-core' ),
				'image'			=>	esc_html__( 'Upload image', 'xstore-core' ),
				],
				'condition' => [ 'add_icon' => 'true' ],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' 	=> __( 'Icon', 'xstore-core' ),
				'type' 		=> 'etheme-icon-control',
				'condition' => [ 'type' => 'svg' ],
			]
		);

		$repeater->add_control(
			'divider2',
			[
				'label' => __( 'Icon styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' 	=> ['type' => 'svg'],
			]
		);

		$repeater->add_control(
			'icon_size',
			[
				'label' => __( 'Icon size', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'condition' 	=> ['type' => 'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .menu-sublist .menu-item .icon-svg svg' => 'width: {{VALUE}}; height: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Icon border radius', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'condition' 	=> ['type' => 'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .menu-sublist .menu-item .icon-svg svg' => 'width: {{VALUE}}; height: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'dividercolor',
			[
				'label' => __( 'Colors', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' 	=> ['type' => 'svg'],
			]
		);

		$repeater->add_control(
			'icon_color',
			[
				'label' 	=> __( 'Icon color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' => ['svg' ]],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .menu-sublist .menu-item .icon-svg svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_color_hover',
			[
				'label' 	=> __( 'Icon color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' => ['svg' ]],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .menu-sublist .menu-item .icon-svg svg path:hover' => 'fill: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'icon_bg_color',
			[
				'label' 	=> __( 'Icon background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' => ['svg' ]],
			]
		);

		$repeater->add_control(
			'icon_bg_color_hover',
			[
				'label' 	=> __( 'Icon background color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' => ['svg' ]],
			]
		);

		$repeater->add_control(
			'divider4',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => ['type' => 'image'],
			]
		);

		$repeater->add_control(
			'img',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => ['type' => ['image' ]],
			]
		);

		$repeater->add_control(
			'img_size',
			[
				'label' => __( 'Image size', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'condition' 	=> ['type' => 'image'],
			]
		);

		$repeater->add_control(
			'position',
			[
				'label' 		=>	__( 'Position of the image', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''					=>	__('Select position', 'xstore-core'),
					'left-top'			=>	__('Left top', 'xstore-core'),
					'left-center'		=>	__('Left center', 'xstore-core'),
					'left-bottom'		=>	__('Left bottom', 'xstore-core'),
					'center-center'		=>	__('Center center', 'xstore-core'),
					'center-bottom'		=>	__('Center bottom', 'xstore-core'),
					'right-top'			=>	__('Right top', 'xstore-core'),
					'right-center'		=>	__('Right center', 'xstore-core'),
					'right-bottom'		=>	__('Right bottom', 'xstore-core'),
				],
				'condition'		=> ['type' => 'image'],
			]
		);

		$repeater->add_control(
			'divider5',
			[
				'label' => __( 'Link Paddings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'padding_top',
			[
				'label' => __( 'Top', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'padding_right',
			[
				'label' => __( 'Right', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'padding_bottom',
			[
				'label' => __( 'Bottom', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'padding_left',
			[
				'label' => __( 'Left', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);		

		$repeater->add_control(
			'divider6',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'divider7',
			[
				'label' => __( 'Colors', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => ['use_custom_fonts_title' => ['true']],
			]
		);

		$repeater->add_control(
			'hover_color',
			[
				'label' 	=> __( 'Text color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['use_custom_fonts_title' => ['true']],
			]
		);

		$repeater->add_control(
			'hover_bg',
			[
				'label' 	=> __( 'Background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['use_custom_fonts_title' => ['true']],
			]
		);

		$repeater->add_control(
			'css',
			[
				'label' => __( 'CSS box', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::CODE,
			]
		);

	}

	/**
	 * Create menu list item widget
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_scroll_text_item( $repeater ) {

        $repeater->add_control(
            'content',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder' => __( 'Lorem ipsum dolor ...', 'xstore-core' ),
            ]
        );

        $repeater->add_control(
            'tooltip',
            [
                'label'         =>  __( 'Use tooltip instead of link', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'label_on'      =>  __( 'Hide', 'xstore-core' ),
                'label_off'     =>  __( 'Show', 'xstore-core' ),
                'return_value'  =>  'true',
                'default'       =>  '',
            ]
        );        

        $repeater->add_control(
            'tooltip_title',
            [
                'label' => __( 'Tooltip title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition'     =>  ['tooltip' => 'true'],
            ]
        );

        $repeater->add_control(
            'tooltip_content',
            [
                'label' => __( 'Tooltip content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'placeholder'   => __( 'Lorem ipsum dolor ...', 'xstore-core' ),
                'condition'     =>  ['tooltip' => 'true'],
            ]
        );

        $repeater->add_control(
            'tooltip_content_pos',
            [
                'label'         =>  __( 'Tooltip content position', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SELECT,
                'options'       =>  [
                    'bottom' => esc_html__( 'Bottom', 'xstore-core' ),
                    'top' 	 => esc_html__( 'Top', 'xstore-core' ),
                ],
            ]
        );

        $repeater->add_control(
        	'button_link',
        	[
        		'label' => __( 'Button link', 'xstore-core' ),
        		'type' => \Elementor\Controls_Manager::URL,
        		'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
        		'show_external' => true,
        		'default' => [
        			'url' => '',
        			'is_external' => true,
        			'nofollow' => true,
        		],
        		'conditions' 	=> [
        			'terms' 	=> [
        				[
        					'name' 		=> 'tooltip',
        					'operator'  => '!=',
        					'value' 	=> 'true'
        				]
        			]
        		]
        	]
        );

        $repeater->add_control(
            'el_class',
            [
                'label' => __( 'Extra Class', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

	}

	/**
	 * Create slider item
	 *
	 * @since 2.1.3
	 * @access public
	 */
	public static function get_slider_item( $repeater ) {

		$repeater->add_control(
			'title',
			[
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'use_custom_fonts_title',
			[
				'label' 		=>	__( 'Use custom font', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'size',
			[
				'label' 		=>	__( 'Font size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'spacing',
			[
				'label' 		=>	__( 'Letter spacing', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write letter spacing for element with dimentions. Example 2px, 0.2em', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'line_height',
			[
				'label' 		=>	__( 'Line height', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write line height for element with dimentions or without. Example 14px, 15em, 2', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'title_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Default 500ms. Write number in ms', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'title_animation_delay',
			[
				'label' 		=>	__( 'Animation delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write number in ms', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'title_class',
			[
				'label' 		=>	__( 'Custom Class', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'condition' 	=>	['use_custom_fonts_title' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'use_custom_fonts_subtitle',
			[
				'label' 		=>	__( 'Use custom font', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'subtitle_size',
			[
				'label' 		=>	__( 'Font size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_spacing',
			[
				'label' 		=>	__( 'Letter spacing', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write letter spacing for element with dimentions. Example 2px, 0.2em', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_line_height',
			[
				'label' 		=>	__( 'Line height', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write line height for element with dimentions or without. Example 14px, 15em, 2', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Default 500ms. Write number in ms', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_animation_delay',
			[
				'label' 		=>	__( 'Animation delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Default 500ms. Write number in ms', 'xstore-core' ),
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);

		$repeater->add_control(
			'subtitle_class',
			[
				'label' 		=>	__( 'Extra Class', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'condition' 	=>	['use_custom_fonts_subtitle' => 'true'],
			]
		);


		$repeater->add_control(
			'subtitle_above',
			[
				'label' 		=>	__( 'Show subtitle above title ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __( 'content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Some promo words', 'xstore-core' ),
				'placeholder' => __( 'Some promo words', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'description_animation',
			[
				'label' => __( 'Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'prefix_class' => 'animated ',
			]
		);

		$repeater->add_control(
			'description_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Default 500. Write number in ms', 'xstore-core' ),
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'description_animation',
							'operator'  => '!=',
							'value' 	=> 'none'
						]
					]
				]
			]
		);

		$repeater->add_control(
			'description_animation_delay',
			[
				'label' 		=>	__( 'Animation delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write number in ms', 'xstore-core' ),
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'description_animation',
							'operator'  => '!=',
							'value' 	=> 'none'
						]
					]
				]
			]
		);

		$repeater->add_control(
			'divider_styles',
			[
				'label' => __( 'Content styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'content_width',
			[
				'label' 		=>	__( 'Content width', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'100'	=>	esc_html__( '100%', 'xstore-core' ),
					'90'	=>	esc_html__( '90%', 'xstore-core' ),
					'80'	=>	esc_html__( '80%', 'xstore-core' ),
					'70'	=>	esc_html__( '70%', 'xstore-core' ),
					'60'	=>	esc_html__( '60%', 'xstore-core' ),
					'50'	=>	esc_html__( '50%', 'xstore-core' ),
					'40'	=>	esc_html__( '40%', 'xstore-core' ),
					'30'	=>	esc_html__( '30%', 'xstore-core' ),
					'20'	=>	esc_html__( '20%', 'xstore-core' ),
					'10'	=>	esc_html__( '10%', 'xstore-core' ),
				],
			]
		);

		$repeater->add_control(
			'align',
			[
				'label' 		=>	__( 'Horizontal width', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'start'		=>	esc_html__( 'Left', 'xstore-core' ),
					'end'		=>	esc_html__( 'Right', 'xstore-core' ),
					'center'	=>	esc_html__( 'Center', 'xstore-core' ),
					'between'	=>	esc_html__( 'Stretch', 'xstore-core' ),
					'around'	=>	esc_html__( 'Stretch (no paddings)', 'xstore-core' ),
				],
			]
		);

		$repeater->add_control(
			'v_align',
			[
				'label' 		=>	__( 'Vertical align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'start'		=>	esc_html__( 'Top', 'xstore-core' ),
					'end'		=>	esc_html__( 'Bottom', 'xstore-core' ),
					'center'	=>	esc_html__( 'Middle', 'xstore-core' ),
					'stretch'	=>	esc_html__( 'Full height', 'xstore-core' ),
				],
			]
		);

		$repeater->add_control(
			'text_align',
			[
				'label' 		=>	__( 'Text align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'),
					'right'		=>	esc_html__('Right', 'xstore-core'),
					'center'	=>	esc_html__('Center', 'xstore-core'),
					'justify'	=>	esc_html__('Justify', 'xstore-core')
				],
			]
		);

		$repeater->add_control(
			'divider_class',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'el_class',
			[
				'label' 		=>	__( 'Extra Class', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'divider_bg',
			[
				'label' => __( 'Background', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'bg_img',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'bg_size',
			[
				'label' 		=>	__( 'Image size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'cover'		=>	esc_html__('Cover', 'xstore-core'),
					'contain'	=>	esc_html__('Contain', 'xstore-core'),
					'auto'		=>	esc_html__('Auto', 'xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'background_position',
			[
				'label' 		=>	__( 'Background position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'' => '',
					esc_html__('Left top', 'xstore-core') => 'left_top',
					esc_html__('Left center', 'xstore-core') => 'left',
					esc_html__('Left bottom', 'xstore-core') => 'left_bottom',
					esc_html__('Right top', 'xstore-core') => 'right_top',
					esc_html__('Right center', 'xstore-core') => 'right',
					esc_html__('Right bottom', 'xstore-core') => 'right_bottom',
					esc_html__('Center top', 'xstore-core') => 'center_top',
					esc_html__('Center center', 'xstore-core') => 'center',
					esc_html__('Center bottom', 'xstore-core') => 'center_bottom',
					esc_html__('(x% y%)', 'xstore-core') => 'custom',
				],
			]
		);

		$repeater->add_control(
			'bg_pos_x',
			[
				'label' 		=>	__( 'Axis X', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Use this field to add background position by X axis. For example 50', 'xstore-core' ),
				'condition' 	=>	['background_position' => 'custom'],
			]
		);

		$repeater->add_control(
			'bg_pos_y',
			[
				'label' 		=>	__( 'Axis Y', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Use this field to add background position by Y axis. For example 50', 'xstore-core' ),
				'condition' 	=>	['background_position' => 'custom'],
			]
		);

		$repeater->add_control(
			'background_repeat',
			[
				'label' 		=>	__( 'Background repeat', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''			=>	esc_html__('Unset', 'xstore-core'),
					'no-repeat'	=>	esc_html__('No repeat', 'xstore-core'),
					'repeat'	=>	esc_html__('Repeat', 'xstore-core'),
					'repeat-x'	=>	esc_html__('Repeat x', 'xstore-core'),
					'repeat-y'	=>	esc_html__('Repeat y', 'xstore-core'),
					'round'		=>	esc_html__('Round', 'xstore-core'),
					'space'		=>	esc_html__('Space', 'xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'bg_overlay',
			[
				'label' 	=> __( 'Background Overlay', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);
		
		$repeater->add_control(
			'button_link',
			[
				'label' => __( 'Button link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' 		=>	__( 'Link for all slide', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'button_color',
			[
				'label' 	=> __( 'Text color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'button_hover_color',
			[
				'label' 	=> __( 'Text color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'button_bg',
			[
				'label' 	=> __( 'Background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'button_hover_bg',
			[
				'label' 	=> __( 'Background color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$repeater->add_control(
			'button_font_size',
			[
				'label' => __( 'Button Font size', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Use this field to add element font size. For example 20px', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border radius ', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Use this field to add element border radius. For example 3px 7px', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'divider_spacing',
			[
				'label' => __( 'Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_paddings',
			[
				'label' => __( 'Paddings (top right bottom left) ', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'button_margins',
			[
				'label' => __( 'Margins (top right bottom left)', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
			]
		);

		$repeater->add_control(
			'divider_animation',
			[
				'label' => __( 'Button animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_animation',
			[
				'label' => __( 'Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'prefix_class' => 'animated ',
			]
		);

		$repeater->add_control(
			'button_animation_duration',
			[
				'label' 		=>	__( 'Animation duration', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Default 500. Write number in ms', 'xstore-core' ),
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'button_animation',
							'operator'  => '!=',
							'value' 	=> 'none'
						]
					]
				]
			]
		);

		$repeater->add_control(
			'button_animation_delay',
			[
				'label' 		=>	__( 'Animation delay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Write number in ms', 'xstore-core' ),
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'description_animation',
							'operator'  => '!=',
							'value' 	=> 'none'
						]
					]
				]
			]
		);

		$repeater->add_control(
			'css',
			[
				'label' => __( 'CSS box', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'rows' => 10,
			]
		);

		$repeater->add_control(
			'content_bg_position',
			[
				'label' 		=>	__( 'Background position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''	=>	__('Inherit from slide settings', 'xstore-core'),
					'left_top'		=>	__('Left top', 'xstore-core'),
					'left'			=>	__('Left center', 'xstore-core'),
					'left_bottom'	=>	__('Left bottom', 'xstore-core'),
					'right_top'		=>	__('Right top', 'xstore-core'),
					'right'			=>	__('Right center', 'xstore-core'),
					'right_bottom'	=>	__('Right bottom', 'xstore-core'),
					'center_top'	=>	__('Center top', 'xstore-core'),
					'center'		=>	__('Center center', 'xstore-core'),
					'center_bottom'	=>	__('Center bottom', 'xstore-core'),
				],
			]
		);
	}
}
