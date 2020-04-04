<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Menu_List as Menu_Shortcode;

/**
 * Menu List widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Menu_List extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'et_menu_list';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Menu List', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'menu-list', 'menu' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme'];
    }

	/**
	 * Register Menu List widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Menu List Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'divider1',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
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

		$this->add_control(
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
					'{{WRAPPER}} .banner-content .banner-title' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_lineheight',
			[
				'label' => __( 'Line height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'default' => 'normal',
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title' => 'line-height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
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
					'{{WRAPPER}} .banner-content .banner-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_fontfamily',
			[
				'label' => __( 'Font Family', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'condition' => ['use_custom_fonts_title' => 'yes'],
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-title' => 'font-family: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::URL,
			]
		);

		$this->add_control(
			'label',
			[
				'label' 		=>	__( 'Label', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''		=>	esc_html__( 'Select label', 'xstore-core' ),
					'hot'	=>	esc_html__( 'Hot', 'xstore-core' ),
					'sale'	=>	esc_html__( 'Sale', 'xstore-core' ),
					'new'	=>	esc_html__( 'New', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
			'divider2',
			[
				'label' => __( 'Title icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
			'icon',
			[
				'label' 	=> __( 'Icon', 'xstore-core' ),
				'type' 		=> 'etheme-icon-control',
				'condition' => [ 'type' => 'svg' ],
			]
		);

		$this->add_control(
			'divider3',
			[
				'label' => __( 'Icon styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' 	=> ['type' => 'svg'],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon size', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'condition' 	=> ['type' => 'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title .icon-svg svg' => 'width: {{VALUE}}; height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Icon border radius', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'condition' 	=> ['type' => 'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title .icon-svg svg' => 'width: {{VALUE}}; height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dividercolor',
			[
				'label' => __( 'Colors', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' 	=> ['type' => 'svg'],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' 	=> __( 'Icon color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' =>'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title .icon-svg svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' 	=> __( 'Icon color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' =>'svg'],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title .icon-svg svg path:hover' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' 	=> __( 'Icon background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' =>'svg'],
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label' 	=> __( 'Icon background color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['type' => 'svg'],
			]
		);

		$this->add_control(
			'divider4',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => ['type' => 'image'],
			]
		);

		$this->add_control(
			'img',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => ['type' => 'image'],
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' => __( 'Image size', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'condition' 	=> ['type' => 'image'],
			]
		);

		$this->add_control(
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

		$this->add_control(
			'divider5',
			[
				'label' => __( 'Link Paddings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'padding_top',
			[
				'label' => __( 'Top', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'padding_right',
			[
				'label' => __( 'Right', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'padding_bottom',
			[
				'label' => __( 'Bottom', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'padding_left',
			[
				'label' => __( 'Left', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);		

		$this->add_control(
			'divider6',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'divider7',
			[
				'label' => __( 'Colors', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' 	=> __( 'Text color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => ['use_custom_fonts_title' => 'true'],
			]
		);

		$this->add_control(
			'hover_bg',
			[
				'label' 	=> __( 'Background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' 	=> ['use_custom_fonts_title' => 'true']
			]
		);

		$this->add_control(
			'css',
			[
				'label' => __( 'CSS box', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::CODE,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menu_list_settings',
			[
				'label' => __( 'Menu List Items', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		Elementor::get_menu_list_item( $repeater );

		$this->add_control(
			'menu_list_item',
			[
				'label' => __( 'Menu list item', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Menu List Item', 'xstore-core' ),
						'list_content' => __( 'Add menu item from here.', 'xstore-core' ),
					],
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Menu List widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}

			if ( 'icon' == $key && $setting ) {
				// icon svg code
				$icon_type = ET_CORE_DIR . 'app/assets/icon-fonts/svg/' . $setting;
				$icon_type = file_get_contents( $icon_type );
				$icon_type = base64_encode( $icon_type );
				$atts['svg'] = $icon_type;
				continue;
			}

			if ( 'use_custom_fonts_title' == $key && $setting ) {
				$atts[$key] = ( 'yes' === $setting ? true : false );
				continue;
			}

			if ( 'link' == $key && $setting ) {
				// Url link
				if ( $settings['link']['nofollow'] ) {
					$settings['link']['nofollow'] = 'nofollow';
				}		
				if ( $settings['link']['is_external'] ) {
					$settings['link']['is_external'] = '%20_blank';
				}
				$atts[$key] = 'url:' . $setting['url'] . '|target:' . $setting['is_external'] . '|rel:' . $setting['nofollow'] . '|title:' . $settings['title'];
				continue;
			}

			if ( 'is_preview' == $key && $setting ) {
				$atts[$key] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
				continue;
			}

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$content = '';
		if ( $settings['menu_list_item'] ) {
			foreach (  $settings['menu_list_item'] as $item ) {
				// Url link
				if ( $item['link']['nofollow'] ) {
					$item['link']['nofollow'] = 'nofollow';
				}		
				if ( $item['link']['is_external'] ) {
					$item['link']['is_external'] = '%20_blank';
				}
				$item['link'] = 'url:' . $item['link']['url'] . '|target:' . $item['link']['is_external'] . '|rel:' . $item['link']['nofollow'];
				$item['use_custom_fonts_title'] = ( 'yes' === $item['use_custom_fonts_title'] ? true : false );

				$item_icon_type = ET_CORE_DIR . 'app/assets/icon-fonts/svg/' . $item['icon'];
				// icon svg code
				if ( isset( $item['icon'] ) && strpos( $item['icon'], 'svg' ) ) {
					$item_icon_type = file_get_contents( $item_icon_type );
					$item_icon_type = base64_encode( $item_icon_type );
				} else {
					$item_icon_type = '';
				}

				$content .= '[et_menu_list_item title="'. $item['title'] .'" use_custom_fonts_title="'. $item['use_custom_fonts_title'] .'" link="'. $item['link'] .'" label="'. $item['label'] .'" transform="'. $item['transform'] .'" align="'. $item['align'] .'" add_icon="'. $item['add_icon'] .'" type="'. $item['type'] .'" svg="'. $item_icon_type .'" icon_size="'. $item['icon_size'] .'" icon_border_radius="'. $item['icon_border_radius'] .'" icon_color="'. $item['icon_color'] .'" icon_color_hover="'. $item['icon_color_hover'] .'" icon_bg_color="'. $item['icon_bg_color'] .'" icon_bg_color_hover="'. $item['icon_bg_color_hover'] .'" img="'. $item['img']['id'] .'" img_size="'. $item['img_size'] .'" position="'. $item['position'] .'" padding_top="'. $item['padding_top'] .'" padding_right="'. $item['padding_right'] .'" padding_bottom="'. $item['padding_bottom'] .'" padding_left="'. $item['padding_left'] .'" class="'. $item['class'] .'" hover_color="'. $item['hover_color'] .'" css="'. $item['css'] .'" is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]';
			}
		}

		$Menu_Shortcode = Menu_Shortcode::get_instance();
		echo $Menu_Shortcode->menu_list_shortcode( $atts, $content );

	}

}
