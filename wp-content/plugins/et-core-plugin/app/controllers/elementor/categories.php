<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;

/**
 * Categories widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Categories extends \Elementor\Widget_Base {

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
		return 'etheme_categories';
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
		return __( 'Product Categories', 'xstore-core' );
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
		return 'eicon-product-categories';
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
		return [ 'categories', 'product-categories' ];
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
	 * Register Categories widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'general_settings',
			[
				'label' => __( 'General Settings', 'xstore-core' ),
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
			'sorting',
			[
				'label' 		=>	__( 'Text fields', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=> esc_html__( 'Sorting the texts layout', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	[
					'name' 		=> esc_html__( 'Category name', 'xstore-core' ),
					'products' 	=> esc_html__( 'Products', 'xstore-core' ),
				],
				'default' 		=>	'name,products',
			]
		);

		$this->add_control(
			'hide_all',
			[
				'label' 		=> __( 'Disable category name and products count', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->add_control(
			'no_space',
			[
				'label' 		=> __( 'Remove space between items', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'data_settings',
			[
				'label' => __( 'Data Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 		=>	false,
				'options' 		=>	[
					2	=>	esc_html__('2', 'xstore-core'),
					3	=>	esc_html__('3', 'xstore-core'),
					4	=>	esc_html__('4', 'xstore-core'),
					5	=>	esc_html__('5', 'xstore-core'),
					6	=>	esc_html__('6', 'xstore-core'),
				],
				'condition' => ['display_type' => 'grid'],
			]
		);

		$this->add_control(
			'ids',
			[
				'label' 		=>	__( 'Categories', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'List of product categories', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_terms('product_cat'),
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=>	__( 'Order by', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ids_order' => esc_html__( 'As IDs provided order', 'xstore-core' ),
					'ID'  		=> esc_html__( 'ID', 'xstore-core' ),
					'name'  	=> esc_html__( 'Title', 'xstore-core' ),
					'count' 	=> esc_html__( 'Quantity', 'xstore-core' ),
				),
				'default' 	=>	'',
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=>	__( 'Order way', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ASC' 	=> esc_html__( 'Ascending', 'xstore-core' ),
					'DESC' 	=> esc_html__( 'Descending', 'xstore-core' ),
				),
				'default' 	=>	'',
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number of categories', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::NUMBER,
				'min' 	=> '',
				'max' 	=> '',
				'step' 	=> '5',
			]
		);

		$this->add_control(
			'parent',
			[
				'label' 		=> __( 'Parent ID', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> 'Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.',
				'min' 			=> '',
				'max' 			=> '',
				'step' 			=> '5',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'design_settings',
			[
				'label' => __( 'Design Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' 	=>	__( 'Style', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	array(
					'default' 	=>	esc_html__( 'Default','xstore-core'),
					'with-bg' 	=>	esc_html__( 'Title with background','xstore-core'),
					'zoom' 		=>	esc_html__( 'Zoom','xstore-core'),
					'diagonal'	=>	esc_html__( 'Diagonal','xstore-core'),
					'classic' 	=>	esc_html__( 'Classic','xstore-core'),
				),
			]
		);

		$this->add_control(
			'count_label',
			[
				'label' 		=> __( 'Products count as label', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'xstore-cores' ),
				'label_off' 	=> __( 'No', 'xstore-cores' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' 	=>	__( 'Horizontal align', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'left'  	=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'),
					'right' 	=>	esc_html__('Right', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'valign',
			[
				'label' 	=>	__( 'Vertical align', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'center'  	=>	esc_html__('Center', 'xstore-core'), 
					'top'		=>	esc_html__('Top', 'xstore-core'),
					'bottom' 	=>	esc_html__('Bottom', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' 	=>	__( 'Design', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'uppercase'  	=>	esc_html__('Uppercase', 'xstore-core'), 
					'lowercase'		=>	esc_html__('Lowercase', 'xstore-core'),
					'capitalize' 	=>	esc_html__('Capitalize', 'xstore-core'), 
					'none' 			=>	esc_html__('None', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' 	=>	__( 'Color scheme', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'white'  	=>	esc_html__('White', 'xstore-core'), 
					'dark'		=>	esc_html__('Dark', 'xstore-core'),
					'custom' 	=>	esc_html__('Custom', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' 	=>	__( 'Category name color', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::COLOR,
				'scheme' 	=>	[
					'type' 	=>	\Elementor\Scheme_Color::get_type(),
					'value' =>	\Elementor\Scheme_Color::COLOR_1,
				],
				'condition' =>	['text_color' => 'custom'],
				'default' 	=>	'#000',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label' 	=>	__( 'Product count color', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::COLOR,
				'scheme' 	=>	[
					'type' 	=>	\Elementor\Scheme_Color::get_type(),
					'value' =>	\Elementor\Scheme_Color::COLOR_1,
				],
				'condition' =>	['text_color' => 'custom'],
				'default' 	=>	'#000',
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' 	=>	__( 'Background color', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::COLOR,
				'scheme' 	=>	[
					'type' 	=>	\Elementor\Scheme_Color::get_type(),
					'value' =>	\Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' 		=>	__( 'Category name font size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
				'condition' 	=>	['text_color' => 'custom'],
			]
		);

		$this->add_control(
			'subtitle_size',
			[
				'label' 		=>	__( 'Product count font size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
				'condition' 	=>	['text_color' => 'custom'],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'display_settings',
			[
				'label' => __( 'Display Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'display_type',
			[
				'label' 		=>	__( 'Display type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 		=>	false,
				'options' 		=>	[
					'grid' 		=> esc_html__('Grid', 'xstore-core'),
					'slider' 	=> esc_html__('Slider', 'xstore-core'),
					'menu' 		=> esc_html__('Menu', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label' 		=> __( 'Slider Speed', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::TEXT,
				'description' 	=> __( 'Duration of transition between slides. Default: 300', 'xstore-core' ),
				'default' 		=> '',
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
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
				'condition' => ['display_type' => 'slider'],
			]
		);
		
		$this->add_control(
			'hide_fo',
			[
				'label' 		=> __( 'Hide pagination only for', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'options'		=> [
					'' => '',
					__( 'Mobile', 'xstore' ) => 'mobile',
					__( 'Desktop', 'xstore' ) => 'desktop',
				],
				'condition' => ['pagination_type' => ['bullets', 'lines' ]],
			]
		);

		$this->add_control(
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
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->add_control(
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
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->add_control(
			'large',
			[
				'label' 	=>	__( 'Large screens', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	4,
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->add_control(
			'notebook',
			[
				'label' 	=>	__( 'On notebooks', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	3,
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->add_control(
			'tablet_land',
			[
				'label' 	=>	__( 'On tablet portrait', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	2,
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->add_control(
			'mobile',
			[
				'label' 	=>	__( 'On mobile', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
				'default' 	=>	1,
				'condition' => ['display_type' => 'slider'],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Categories widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['ids'] = !empty( $settings['ids'] ) ? implode( ',',$settings['ids'] ) : '';
		$settings['sorting'] = !empty( $settings['sorting'] ) ? implode( ',',$settings['sorting'] ) : '';

		echo do_shortcode( '[etheme_categories 
			title="'. $settings['title'] .'"
			sorting="'. $settings['sorting'] .'"
			hide_all="'. $settings['hide_all'] .'"
			no_space="'. $settings['no_space'] .'"
			display_type="'. $settings['display_type'] .'"
			columns="'. $settings['columns'] .'"
			ids="'. $settings['ids'] .'"
			orderby="'. $settings['orderby'] .'"
			order="'. $settings['order'] .'"
			number="'. $settings['number'] .'"
			parent="'. $settings['parent'] .'"
			style="'. $settings['style'] .'"
			count_label="'. $settings['count_label'] .'"
			text_align="'. $settings['text_align'] .'"
			valign="'. $settings['valign'] .'"
			text_transform="'. $settings['text_transform'] .'"
			text_color="'. $settings['text_color'] .'"
			title_color="'. $settings['title_color'] .'"
			subtitle_color="'. $settings['subtitle_color'] .'"
			bg_color="'. $settings['bg_color'] .'"
			title_size="'. $settings['title_size'] .'"
			subtitle_size="'. $settings['subtitle_size'] .'"
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
