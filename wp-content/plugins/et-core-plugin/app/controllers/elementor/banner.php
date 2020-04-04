<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Controllers\Shortcodes\Banner as Banner_Shortcode;

/**
 * banner widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Banner extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'banner';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Banner With Mask', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-banner';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'banner with mask', 'banner' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme'];
    }


	/**
	 * Register banner widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'xstore-core' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subitle', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'xstore-core' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'Banner Mask Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Some promo words', 'xstore-core' ),
				'placeholder' => __( 'Some promo words', 'xstore-core' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'img',
			[
				'label' => __( 'Banner Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'button_title',
			[
				'label' => __( 'Button Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_external' => true,
			]
		);

		$this->add_control(
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
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
				'default' => '',
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
			'use_custom_fonts_subtitle',
			[
				'label' => __( 'Use custom font for subtitle?', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'subtitle_font_container_fontsize',
			[
				'label' => __( 'Font size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['use_custom_fonts_subtitle' => 'yes'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_lineheight',
			[
				'label' => __( 'Line height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'normal',
				'condition' => ['use_custom_fonts_subtitle' => 'yes'],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'line-height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_subtitle' => 'yes'],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_fontfamily',
			[
				'label' => __( 'Font Family', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'condition' => ['use_custom_fonts_subtitle' => 'yes'],
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .banner-content .banner-subtitle' => 'font-family: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'type',
			[
				'label' 	=> __( 'Image hover effect', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::SELECT,
				'default' 	=> 3,
				'options' 	=> [
					2 => __('Zoom In', 'xstore-core'),
					6 => __('Slide right', 'xstore-core'),
					4 => __('Zoom out', 'xstore-core'),
					5 => __('Scale out', 'xstore-core'),
					3 => __('None', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'text_effect',
			[
				'label' => __( 'Text hover effect', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					0 => __('None', 'xstore-core'),
					1 => __('To top', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'type_with_diagonal',
			[
				'label' => __( 'With diagonal (on hover)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Image effect with diagonal', 'xstore-cores' ),
				'label_on' => __( 'Show', 'xstore-cores' ),
				'label_off' => __( 'Hide', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'type_with_border',
			[
				'label' => __( 'With border animation (on hover)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Image effect with border inside', 'xstore-cores' ),
				'label_on' => __( 'Show', 'xstore-cores' ),
				'label_off' => __( 'Hide', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Horizontal align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'left' 	 => esc_html__('Left', 'xstore-core') , 
					'center' => esc_html__('Center', 'xstore-core') , 
					'right'	 => esc_html__('Right', 'xstore-core') 
				],
			]
		);

		$this->add_control(
			'valign',
			[
				'label' => __( 'Vertical align', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'top'		=>	esc_html__('Top', 'xstore-core'), 
					'middle'	=>	esc_html__('Middle', 'xstore-core'), 
					'bottom'	=>	esc_html__('Bottom', 'xstore-core')
				],
			]
		);

		$this->add_control(
			'font_style',
			[
				'label' => __( 'Font style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'dark'	=>	esc_html__('Dark', 'xstore-core'),
					'light'	=>	esc_html__('Light', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'responsive_fonts',
			[
				'label' => __( 'Responsive fonts', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'hide_title_responsive',
			[
				'label' => __( 'Hide title on mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);
		
		$this->add_control(
			'hide_subtitle_responsive',
			[
				'label' => __( 'Hide subtitle on mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);
		
		$this->add_control(
			'hide_description_responsive',
			[
				'label' => __( 'Hide description on mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);		

		$this->add_control(
			'hide_button_responsive',
			[
				'label' => __( 'Hide button on mobile', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);
		
		$this->add_control(
			'is_active',
			[
				'label' => __( 'Hovered state by default', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Make banner with hovered effects by default', 'xstore-core' ),
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);
		
		$this->add_control(
			'ajax',
			[
				'label' => __( 'Lazy loading for this element', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'xstore-cores' ),
				'label_off' => __( 'No', 'xstore-cores' ),
				'return_value' => true,
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_section',
			[
				'label' => __( 'Image Style', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' 	=>	__( 'Banner image size', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'multiple' 	=>	false,
				'options' 	=>	[
					'medium' 	=>	__( 'Medium', 'xstore-core' ),
					'large' 	=>	__( 'Large', 'xstore-core' ),
					'full' 		=>	__( 'Full', 'xstore-core' ),
					'custom' 	=>	__( 'Custom', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'img_size_dimension',
			[
				'label' => __( 'Image Dimension', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'xstore-core' ),
				'default' => [
					'width' => '200',
					'height' => '100',
				],
				'condition' => ['img_size' => 'custom'],
			]
		);

		$this->add_control(
			'img_min_size',
			[
				'label' => __( 'Banner image min height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Enter image min-height. Example in pixels: 200px', 'xstore-core' ),
				'min' => 0,
				'step' => 5,
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Image Opacity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core' ),
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'default' => 1,
			]
		);

		$this->add_control(
			'image_opacity_on_hover',
			[
				'label' => __( 'Image Opacity on Hover', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Enter value between 0.0 to 1 (0 is maximum transparency, while 1 is lowest)', 'xstore-core' ),
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'default' => 1,
			]
		);

		$this->add_control(
			'banner_color_bg',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'Use image opacity option to add overlay effect with background', 'xstore-core' ),
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			[
				'label' => __( 'Button Style', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Button text color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Button text color (hover)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'button_bg',
			[
				'label' => __( 'Button background color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'button_hover_bg',
			[
				'label' => __( 'Button background color (hover)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'button_font_size',
			[
				'label' => __( 'Button font size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __( 'Use this field to add element font size. For example 20px', 'xstore-core' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Button border radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __( 'Use this field to add element border radius. For example 3px 7px', 'xstore-core' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
			]
		);

		$this->add_control(
			'button_paddings',
			[
				'label' => __( 'Button paddings (top right bottom left)', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => __( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
			]
		);
	
		$this->end_controls_section();

	}

	/**
	 * Render banner widget output on the frontend.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}

			if ( 'link' == $key && $setting ) {
				if ( $settings[$key]['nofollow'] ) {
					$settings[$key]['nofollow'] = 'nofollow';
				}		
				if ( $settings[$key]['is_external'] ) {
					$settings[$key]['is_external'] = '%20_blank';
				}
				$atts[$key] = 'url:' . $settings[$key]['url'] . '|target:' . $settings[$key]['is_external'] . '|rel:' . $settings[$key]['nofollow'];

				continue;
			}

			if ( 'button_link' == $key && $setting ) {
				// Button link
				if ( $settings[$key]['nofollow'] ) {
					$settings[$key]['nofollow'] = 'nofollow';
				}		
				if ( $settings[$key]['is_external'] ) {
					$settings[$key]['is_external'] = '%20_blank';
				}
				// Button title
				$settings['button_title'] = isset( $settings['button_title'] ) ? $settings['button_title'] : '';
				$atts[$key] = 'url:' . $settings['button_link']['url'] . '|title:' . $settings['button_title'] . '|target:' . $settings[$key]['is_external'] . '|rel:' . $settings[$key]['nofollow'];

				continue;
			}			

			if ( 'use_custom_fonts_title' == $key && $setting ) {
				$atts[$key] = ( 'yes' === $setting ? true : false );
				continue;
			}

			if ( 'use_custom_fonts_subtitle' == $key && $setting ) {
				$atts[$key] = ( 'yes' === $setting ? true : false );
				continue;
			}

			if ( 'button_font_size' == $key && $setting ) {
				$atts[$key] = $setting['size'] . $setting['unit'];
				continue;
			}

			if ( 'button_border_radius' == $key && $setting ) {
				$atts[$key] = $setting['size'] . $setting['unit'];
				continue;
			}

			if ( 'button_paddings' == $key && $setting ) {
				$atts[$key] = $setting['size'] . $setting['unit'];
				continue;
			}

			if ( 'img' == $key && $setting ) {
				$atts[$key] = $setting['id'];
				continue;
			}			

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
		$Banner_Shortcode = Banner_Shortcode::get_instance();
		echo $Banner_Shortcode->banner_shortcode( $atts, $settings['content'] );

	}

}
