<?php
namespace ETC\App\Controllers\Elementor;

use ETC\App\Controllers\Shortcodes\Team_Member as Team_Member_Shortcodes;

/**
 * Team Member widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Team_Member extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team_member';
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
		return __( 'Team Member', 'xstore-core' );
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
		return 'eicon-user-circle-o';
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
		return [ 'team_member' ];
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
	 * Register Team Member widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Team Member Settings', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'xstore_title_divider_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name',
			[
				'label' 	  => __( 'Member name', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'email',
			[
				'label' 	  => __( 'Member email', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'position',
			[
				'label' 	  => __( 'Position', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'content',
			[
				'label' 	=> __( 'Member information', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::WYSIWYG,
				'default' 	=> 'Member information',
				'rows' 		=> 10,
			]
		);

		$this->add_control(
			'xstore_title_divider_layout',
			[
				'label' => __( 'Layout', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Display Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'1' 	=>	esc_html__('Vertical', 'xstore-core'),
					'2' 	=>	esc_html__('Horizontal', 'xstore-core'),
					'3' 	=>	esc_html__('Overlayed content', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'xstore_title_divider_img',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'img',
			[
				'label' => __( 'Avatar', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' 	  => __( 'Image size', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'img_effect',
			[
				'label' 		=>	__( 'Image effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'2'	=>	__('Zoom In', 'xstore-core'),
					'4'	=>	__('Zoom out', 'xstore-core'),
					'5'	=>	__('Scale out', 'xstore-core'),
					'3'	=>	__('None', 'xstore-core'),
				],
				'condition'   => ['type' => ['slider-grid']],
				'default'	  => 2,
			]
		);

		$this->add_control(
			'img_position',
			[
				'label' 		=>	__( 'Image position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'),
					'right'		=> 	esc_html__('Right', 'xstore-core')
				],
				'condition'   	=> ['type' => ['2']],
			]
		);

		$this->add_control(
			'xstore_title_divider_cntnct_style',
			[
				'label' => __( 'Content styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' 		=>	__( 'Content Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'  	=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'),
					'right' 	=>	esc_html__('Right', 'xstore-core'), 
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' 		=>	__( 'Content position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'top'		=>	esc_html__('Top', 'xstore-core'),
					'middle'	=>  esc_html__('Middle', 'xstore-core'),
					'bottom'	=>	esc_html__('Bottom', 'xstore-core')
				],
				'condition'   => ['type' => ['2']],
			]
		);

		$this->add_control(
			'xstore_title_divider_icon_pos',
			[
				'label' => __( 'Icons position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icons_position',
			[
				'label' 		=>	__( 'Icons position', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'img'		=>	esc_html__('On image', 'xstore-core'),
					'content'	=>  esc_html__('In content', 'xstore-core'),
				],
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
				'label' 		=>	__( 'Links target', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'_self'		=>	esc_html__('Current window', 'xstore-core'),
					'_blank'	=>	esc_html__('Blank', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'xstore_title_divider_element_style',
			[
				'label' => __( 'Element styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' 	=> __( 'Name color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' 	=> __( 'Position color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'email_color',
			[
				'label' 	=> __( 'Email color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'email_link_color',
			[
				'label' 	=> __( 'Email link color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' 	=> __( 'Content color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'overlay_bg',
			[
				'label' 	=> __( 'Overlay background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#fff',
			]
		);

		$this->add_control(
			'content_bg',
			[
				'label' 	=> __( 'Content background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'default' 	=> '#fff',
				'condition' => ['type' => ['2', '3']],
			]
		);

		$this->add_control(
			'xstore_title_divider_icon_style',
			[
				'label' => __( 'Icons styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'normal'	=>	esc_html__('Normal', 'xstore-core'),
					'small'		=>	esc_html__('Small', 'xstore-core'),
					'large'		=>	esc_html__('Large', 'xstore-core') 
				],
			]
		);

		$this->add_control(
			'align',
			[
				'label' 		=>	__( 'Icons Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'  	=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'),
					'right' 	=>	esc_html__('Right', 'xstore-core'), 
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
			'tooltip',
			[
				'label' 		=>	__( 'Tooltips for icons', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=>	__( 'Hide', 'xstore-core' ),
				'label_off' 	=>	__( 'Show', 'xstore-core' ),
				'return_value'  =>	true,
				'default' 		=>	false,
			]
		);

		$this->add_control(
			'icons_bg',
			[
				'label' 	=> __( 'Icons background', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icons_bg_hover',
			[
				'label' 	=> __( 'Icons background hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'icons_color',
			[
				'label' 	=> __( 'Icons color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);
		
		$this->add_control(
			'icons_color_hover',
			[
				'label' 	=> __( 'Icons color hover', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'scheme' 	=> [
					'type' 	=> \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Team Member widget output on the frontend.
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

			if ( 'img' == $key ) {
				$atts[$key] = isset( $setting['id'] ) ? $setting['id'] : '';
				continue;
			}

			if ( 'is_preview' == $key ) {
				$atts[$key] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
				continue;
			}

			if ( 'content' == $key ) {
				continue;
			}

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$Team_Member_Shortcodes = Team_Member_Shortcodes::get_instance();
		echo $Team_Member_Shortcodes->team_member_shortcode( $atts, $settings['content'] );

	}

}
