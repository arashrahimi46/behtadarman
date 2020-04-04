<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Slider as ShortcodeSlider;

/**
 * Slider widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Slider extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_slider';
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
		return __( 'slider', 'xstore-core' );
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
		return 'eicon-slider-3d';
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
		return [ 'slider', 'slider-item' ];
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
	 * Register Slider widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'height',
			[
				'label' 		=>	__( 'Height', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'full'		=>	esc_html__('Full height', 'xstore-core'),
					'custom'	=>	esc_html__('Custom height', 'xstore-core'),
					'auto'		=>	esc_html__('Height of content', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'height_value',
			[
				'label' => __( 'Custom height value', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Allowed units px, em, vh, vw, %', 'xstore-core' ),
				'condition' => ['height' => 'custom'],
			]
		);

		$this->add_control(
			'height_value_mobile',
			[
				'label' => __( 'Custom height value (0 - 992px)', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Allowed units px, em, vh, vw, %', 'xstore-core' ),
				'condition' => ['height' => 'custom'],
			]
		);

		$this->add_control(
			'nav',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'arrows_bullets'	=>	esc_html__('Arrows + Bullets', 'xstore-core'),
					'arrows'			=>	esc_html__('Arrows', 'xstore-core'),
					'bullets'			=>	esc_html__('Bullets', 'xstore-core'), 
					'disable'			=>	esc_html__('Disable', 'xstore-core')           
				],
			]
		);

		$this->add_control(
			'nav_on_hover',
			[
				'label' 		=>	__( 'Show navigation on hover', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	false,
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'nav',
							'operator'  => '!=',
							'value' 	=> 'disable'
						]
					]
				]
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label' 	=> __( 'Navigation color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#e1e1e1',
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'nav',
							'operator'  => '!=',
							'value' 	=> 'disable'
						]
					]
				]
			]
		);

		$this->add_control(
			'arrows_bg_color',
			[
				'label' 	=> __( 'Arrows background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#e1e1e1',
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'nav',
							'operator'  => '!=',
							'value' 	=> 'disable'
						]
					]
				]
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' 		=>	__( 'Slider autoplay', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label' 		=>	__( 'Slider loop', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	'yes',
				'default' 		=>	'yes',
			]
		);

		$this->add_control(
			'slider_interval',
			[
				'label' => __( 'Interval', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Interval between slides. In milliseconds. Default: 5000', 'xstore-core' ),
				'condition' => ['slider_autoplay' => 'yes'],
			]
		);

		$this->add_control(
			'transition_effect',
			[
				'label' 		=>	__( 'Transition style', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'slide'		=>	esc_html__('Slide', 'xstore-core'),
					'fade'		=>	esc_html__('Fade', 'xstore-core'),
					'cube'		=>	esc_html__('Cube', 'xstore-core'),
					'coverflow'	=>	esc_html__('Coverflow', 'xstore-core'),
					'flip'		=>	esc_html__('Flip', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label' => __( 'Transition speed', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' 	=> __( 'Background color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'description' 	=> __( 'Apply for slider and loader background colors', 'xstore-core' ),
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'el_class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_settings',
			[
				'label' => __( 'Item Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		Elementor::get_slider_item( $repeater );

		$this->add_control(
			'get_slider_item',
			[
				'label' => __( 'Slider Item', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Slider Item', 'xstore-core' ),
						'list_content' => __( 'Add slider item from here.', 'xstore-core' ),
					],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Slider widget output on the frontend.
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

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$content = '';
		if ( $settings['get_slider_item'] ) {
			foreach (  $settings['get_slider_item'] as $item ) {
				// Url link
				if ( $item['button_link']['nofollow'] ) {
					$item['button_link']['nofollow'] = 'nofollow';
				}		
				if ( $item['button_link']['is_external'] ) {
					$item['button_link']['is_external'] = '%20_blank';
				}
				$item['button_link'] = 'url:' . $item['button_link']['url'] . '|target:' . $item['button_link']['is_external'] . '|rel:' . $item['button_link']['nofollow'];

				$content .= '[etheme_slider_item  size="'. $item['size'] .'" spacing="'. $item['spacing'] .'" line_height="'. $item['line_height'] .'" color="'. $item['color'] .'" title_animation_duration="'. $item['title_animation_duration'] .'" title_animation_delay="'. $item['title_animation_delay'] .'" title_class="'. $item['title_class'] .'" subtitle_size="'. $item['subtitle_size'] .'" subtitle_spacing="'. $item['subtitle_spacing'] .'" subtitle_line_height="'. $item['subtitle_line_height'] .'" subtitle_color="'. $item['subtitle_color'] .'" subtitle_animation_duration="'. $item['subtitle_animation_duration'] .'" subtitle_animation_delay="'. $item['subtitle_animation_delay'] .'" subtitle_class="'. $item['subtitle_class'] .'" title="'. $item['title'] .'" use_custom_fonts_title="'. $item['use_custom_fonts_title'] .'" subtitle="'. $item['subtitle'] .'" subtitle_above="'. $item['subtitle_above'] .'" description_animation="'. $item['description_animation'] .'" description_animation_duration="'. $item['description_animation_duration'] .'" description_animation_delay="'. $item['description_animation_delay'] .'" align="'. $item['align'] .'" v_align="'. $item['v_align'] .'" content_width="'. $item['content_width'] .'" text_align="'. $item['text_align'] .'" el_class="'. $item['el_class'] .'" bg_img="'. $item['bg_img']['id'] .'" bg_size="'. $item['bg_size'] .'" background_position="'. $item['background_position'] .'" bg_pos_x="'. $item['bg_pos_x'] .'" bg_pos_y="'. $item['bg_pos_y'] .'" background_repeat="'. $item['background_repeat'] .'" bg_color="'. $item['bg_color'] .'" bg_overlay="'. $item['bg_overlay'] .'" button_link="'. $item['button_link'] .'" link="'. $item['link'] .'" button_color="'. $item['button_color'] .'" button_hover_color="'. $item['button_hover_color'] .'" button_bg="'. $item['button_bg'] .'" button_hover_bg="'. $item['button_hover_bg'] .'" button_font_size="'. $item['button_font_size'] .'" button_border_radius="'. $item['button_border_radius'] .'" button_paddings="'. $item['button_paddings'] .'" button_margins="'. $item['button_margins'] .'" button_animation="'. $item['button_animation'] .'" button_animation_duration="'. $item['button_animation_duration'] .'" button_animation_delay="'. $item['button_animation_delay'] .'" css="'. $item['css'] .'" content_bg_position="'. $item['content_bg_position'] .'" is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]'. $item['content'] .'[/etheme_slider_item]';
			}
		}

		$slider =  ShortcodeSlider::get_instance();
		echo $slider->slider_shortcode( $atts , $content);

	}

}
