<?php
namespace ETC\App\Controllers\Elementor;

/**
 * Follow widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Follow extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-follow';
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
		return __( 'Social links', 'xstore-core' );
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
		return 'eicon-social-icons';
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
		return [ 'social-links', 'follow' ];
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
	 * Register follow widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Social links Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'facebook',
			[
				'label' => __( 'Facebook link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'twitter',
			[
				'label' => __( 'Twitter link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'instagram',
			[
				'label' => __( 'Instagram link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'pinterest',
			[
				'label' => __( 'Pinterest link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'linkedin',
			[
				'label' => __( 'LinkedIn link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tumblr',
			[
				'label' => __( 'Tumblr link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'youtube',
			[
				'label' => __( 'YouTube link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'vimeo',
			[
				'label' => __( 'Vimeo link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'rss',
			[
				'label' => __( 'RSS link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'vk',
			[
				'label' => __( 'VK link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'houzz',
			[
				'label' => __( 'Houzz link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'tripadvisor',
			[
				'label' => __( 'Tripadvisor link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'target',
			[
				'label' 		=>	__( 'Social Target', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'_self'		=>	esc_html__('Current window', 'xstore-core'),
					'_blank'	=>	esc_html__('Blank', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'normal'	=>	esc_html__('Normal', 'xstore-core'),
					'small'	=>	esc_html__('Small', 'xstore-core'),
					'large'	=>	esc_html__('Large', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'align',
			[
				'label' 		=>	__( 'Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'), 
					'right'		=>	esc_html__('Right', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'filled',
			[
				'label' 		=>	__( 'Filled icons', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	false,
			]
		);

		$this->add_control(
			'icons_color',
			[
				'label' 	=> __( 'Icons color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icons_color_hover',
			[
				'label' 	=> __( 'Icons color hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icons_bg',
			[
				'label' 	=> __( 'Icons background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => [ 'filled' => 'true' ],
			]
		);

		$this->add_control(
			'icons_bg_hover',
			[
				'label' 	=> __( 'Icons background hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 		=> \Elementor\Scheme_Color::get_type(),
					'value' 	=> \Elementor\Scheme_Color::COLOR_1,
				],
				'condition' => [ 'filled' => 'true' ],
			]
		);

		$this->add_control(
			'icons_border_radius',
			[
				'label' => __( 'Icons styles', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'condition' => [ 'filled' => 'true' ],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render follow widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[follow 
			facebook="'. $settings['facebook'] .'"
			twitter="'. $settings['twitter'] .'"
			instagram="'. $settings['instagram'] .'"
			pinterest="'. $settings['pinterest'] .'"
			linkedin="'. $settings['linkedin'] .'"
			tumblr="'. $settings['tumblr'] .'"
			youtube="'. $settings['youtube'] .'"
			vimeo="'. $settings['vimeo'] .'"
			rss="'. $settings['rss'] .'"
			vk="'. $settings['vk'] .'"
			houzz="'. $settings['houzz'] .'"
			tripadvisor="'. $settings['tripadvisor'] .'"
			target="'. $settings['target'] .'"
			size="'. $settings['size'] .'"
			align="'. $settings['align'] .'"
			filled="'. $settings['filled'] .'"
			icons_color="'. $settings['icons_color'] .'"
			icons_color_hover="'. $settings['icons_color_hover'] .'"
			icons_bg="'. $settings['icons_bg'] .'"
			icons_bg_hover="'. $settings['icons_bg_hover'] .'"
			icons_border_radius="'. $settings['icons_border_radius'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
