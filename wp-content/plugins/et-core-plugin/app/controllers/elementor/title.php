<?php
namespace ETC\App\Controllers\Elementor;

/**
 * Title widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Title extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-title';
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
		return __( 'Title With Text', 'xstore-core' );
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
		return 'eicon-post-title';
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
		return [ 'title' ];
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
	 * Register Title With Text widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings_title',
			[
				'label' => __( 'Title Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
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
				'label' 		=>	__( 'Use custom font ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'title_text_size',
			[
				'label' 		=>	__( 'Text align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'options' 		=>	[
					'0'				=> '', 
					'left'			=> esc_html__( 'left', 'xstore-core' ), 
					'right'			=> esc_html__( 'right', 'xstore-core' ), 
					'center'		=> esc_html__( 'center', 'xstore-core' ), 
					'justify'		=> esc_html__( 'justify', 'xstore-core' ), 
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_fontsize',
			[
				'label' => __( 'Font size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['use_custom_fonts_title' => 'true'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-title' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_lineheight',
			[
				'label' => __( 'Line height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'default' => 'normal',
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-title' => 'line-height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_font_container_fontfamily',
			[
				'label' => __( 'Font Family', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'condition' => ['use_custom_fonts_title' => 'true'],
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-title' => 'font-family: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_subtitle',
			[
				'label' => __( 'Subtitle Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subitle', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'use_custom_fonts_subtitle',
			[
				'label' 		=>	__( 'Use custom font ?', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'subtitle_text_size',
			[
				'label' 		=>	__( 'Text align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'options' 		=>	[
					'0'				=> '', 
					'left'			=> esc_html__( 'left', 'xstore-core' ), 
					'right'			=> esc_html__( 'right', 'xstore-core' ), 
					'center'		=> esc_html__( 'center', 'xstore-core' ), 
					'justify'		=> esc_html__( 'justify', 'xstore-core' ), 
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_fontsize',
			[
				'label' => __( 'Font size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_lineheight',
			[
				'label' => __( 'Line height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'normal',
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'line-height: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_textcolor',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtitle_font_container_fontfamily',
			[
				'label' => __( 'Font Family', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'condition' => ['use_custom_fonts_subtitle' => 'true'],
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container .banner-subtitle' => 'font-family: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label' 		=>	__( 'Divider Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'0'					 => '', 
					'line-through' 		 => __('Line through', 'xstore-core'), 
					'line-through-short' => __('Line through short', 'xstore-core'),
					'line-under'		 => __('Line under', 'xstore-core'),
				],
				'default'		=> '0'
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' 	=> __( 'Divider Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'divider',
							'operator'  => '!=',
							'value' 	=> '0'
						]
					]
				]
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Divider Width', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'divider',
							'operator'  => '!=',
							'value' 	=> '0'
						]
					]
				]
			]
		);

		$this->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Title With Text widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_google_fonts 	= ( 'true' ==  $settings['use_custom_fonts_title'] ) ? "title_google_fonts=''" : '';
		$subtitle_google_fonts  = ( 'true' ==  $settings['use_custom_fonts_subtitle'] ) ? "subtitle_google_fonts=''" : '';

		echo do_shortcode( '[title 
			title="'. $settings['title'] .'"
			use_custom_fonts_title="'. $settings['use_custom_fonts_title'] .'"
			'. $title_google_fonts . '
			subtitle="'. $settings['subtitle'] .'"
			use_custom_fonts_subtitle="'. $settings['use_custom_fonts_subtitle'] .'"
			'. $subtitle_google_fonts . '
			divider="'. $settings['divider'] .'"
			divider_color="'. $settings['divider_color'] .'"
			divider_width="'. $settings['divider_width'] .'"
			class="'. $settings['class'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
