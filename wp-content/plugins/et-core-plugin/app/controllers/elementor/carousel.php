<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Traits\Elementor;

/**
 * Carousel widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Carousel extends \Elementor\Widget_Base {

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
		return 'etheme_carousel';
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
		return __( 'Custom Carousel', 'xstore-core' );
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
		return 'eicon-carousel';
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
		return [ 'carousel' ];
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
	 * Register Carousel widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Custom Carousel Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'space',
			[
				'label' 	=>	__( 'Add space between slides', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	array(
					'yes' 	=>  esc_html__('Yes', 'xstore-core'),
					'no' 	=>  esc_html__('No', 'xstore-core'),
				),
				'default' 	=>	'',
			]
		);

		$this->add_control(
			'el_class',
			[
				'label' 	=> __( 'Extra Class', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

	}

	/**
	 * Render Carousel widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo do_shortcode( '[etheme_carousel 
			space="'. $settings['space'] .'"
			el_class="'. $settings['el_class'] .'"
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
