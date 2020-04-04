<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;

/**
 * Instagram widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Instagram extends \Elementor\Widget_Base {

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
		return 'etheme-instagram';
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
		return __( 'Instagram', 'xstore-core' );
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
		return 'eicon-instagram-post';
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
		return [ 'instagram' ];
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
	 * Register Instagram widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Instagram Settings', 'xstore-core' ),
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
			'user',
			[
				'label' 		=>	__( 'Instagram account', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> '<a href="' . admin_url('admin.php?page=et-panel-social'). '" target="_blank">'. esc_html__('Add Instagram account?', 'xstore-core') . '</a>',
				'options' 		=>	Elementor::instagram_api_data(),
			]
		);

		$this->add_control(
			'username',
			[
				'label' => __( 'Hashtag', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Numer of photos', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Photo size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'thumbnail' => esc_html__( 'Thumbnail', 'xstore-core' ),
					'medium' 	=> esc_html__( 'Medium', 'xstore-core' ),
					'large' 	=> esc_html__( 'Large', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'img_type',
			[
				'label' 		=>	__( 'Image Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'squared'  	 =>	esc_html__( 'Squared', 'xstore-core' ),
					'default'	 =>	esc_html__( 'Default', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'element' => esc_html__( 'Element', 'xstore-core' ),
					'widget'  => esc_html__( 'Widget', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'2'	=>	esc_html__('2', 'xstore-core'),
					'3'	=>	esc_html__('3', 'xstore-core'),
					'4'	=>	esc_html__('4', 'xstore-core'),
					'5'	=>	esc_html__('5', 'xstore-core'),
					'6'	=>	esc_html__('6', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'target',
			[
				'label' 		=>	__( 'Open links in', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					 '_self' => esc_html__( 'Current window (_self)', 'xstore-core' ),
					'_blank' => esc_html__( 'New window (_blank)', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' 	  => __( '"Follow us" text', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Leave field empty to not showing this link after images', 'xstore-core'),
			]
		);

		$this->add_control(
			'spacing',
			[
				'label' 		=>	__( 'Without spacing', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'ajax',
			[
				'label' 		=>	__( 'Lazy loading for this element', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'slider',
			[
				'label' 		=>	__( 'Slider', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	'',
				'condition' 	=> [ 'type' => 'element' ],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label' 		=> __( 'Slider Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'default' 		=> '',
				'condition' 	=> ['slider' => 'true'],
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' 		=> __( 'Slider autoplay', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> ['slider' => 'true'],
			]
		);

		$this->add_control(
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

		$this->add_control(
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
		
		$this->add_control(
			'slider_loop',
			[
				'label' 		=> __( 'Slider loop', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> ['slider' => 'true'],
			]
		);
		
		$this->add_control(
			'hide_buttons',
			[
				'label' 		=> __( 'Hide prev/next buttons', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> ['slider' => 'true'],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' 		=> __( 'Pagination type', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SELECT,
				'options'		=> [
					'hide' 		=>	__( 'Hide', 'xstore' ),
					'bullets' 	=>	__( 'Bullets', 'xstore' ),
					'lines' 	=>	__( 'Lines', 'xstore' ),
				],
				'condition' 	=> ['slider' => 'true'],
			]
		);
		
		$this->add_control(
			'hide_fo',
			[
				'label' 		=> __( 'Hide pagination only for', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'options'		=> [
					'' => '',
					'mobile'	=>	__( 'Mobile', 'xstore' ),
					'desktop'	=>	__( 'Desktop', 'xstore' ),
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$this->add_control(
			'default_color',
			[
				'label' 	=> __( 'Pagination default color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#e1e1e1',
			]
		);

		$this->add_control(
			'active_color',
			[
				'label' 	=> __( 'Pagination active color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
				'scheme' 	=> [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#222',
				'condition' => ['slider' => 'true'],
			]
		);

		$this->add_control(
			'large',
			[
				'label' 	=>	__( 'Large screens', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	4,
				'condition' => ['slider' => 'true'],
			]
		);

		$this->add_control(
			'notebook',
			[
				'label' 	=>	__( 'On notebooks', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	3,
				'condition' => ['slider' => 'true'],
			]
		);

		$this->add_control(
			'tablet_land',
			[
				'label' 	=>	__( 'On tablet portrait', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	2,
				'condition' => ['slider' => 'true'],
			]
		);

		$this->add_control(
			'mobile',
			[
				'label' 	=>	__( 'On mobile', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	1,
				'condition' => ['slider' => 'true'],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Instagram widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[instagram 
			title="'. $settings['title'] .'"
			user="'. $settings['user'] .'"
			username="'. $settings['username'] .'"
			number="'. $settings['number'] .'"
			size="'. $settings['size'] .'"
			img_type="'. $settings['img_type'] .'"
			type="'. $settings['type'] .'"
			columns="'. $settings['columns'] .'"
			target="'. $settings['target'] .'"
			link="'. $settings['link'] .'"
			slider="'. $settings['slider'] .'"
			spacing="'. $settings['spacing'] .'"
			ajax="'. $settings['ajax'] .'"
			slider_speed="'. $settings['slider_speed'] .'"
			slider_autoplay="'. $settings['slider_autoplay'] .'"
			slider_stop_on_hover="'. $settings['slider_stop_on_hover'] .'"
			slider_interval="'. $settings['slider_interval'] .'"
			slider_loop="'. $settings['slider_loop'] .'"
			hide_buttons="'. $settings['hide_buttons'] .'"
			pagination_type="'. $settings['pagination_type'] .'"
			hide_fo="'. $settings['hide_fo'] .'"
			default_color="'. $settings['default_color'] .'"
			active_color="'. $settings['active_color'] .'"
			large="'. $settings['large'] .'"
			notebook="'. $settings['notebook'] .'"
			tablet_land="'. $settings['tablet_land'] .'"
			mobile="'. $settings['mobile'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
