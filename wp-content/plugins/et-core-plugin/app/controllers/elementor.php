<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Elementor initial class.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controller
 */
final class Elementor extends Base_Controller {

    /**
     * Minimum Elementor Version Supp
     *
     * @since 2.0.0
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Registered widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $widgets = NULL;

    /**
     * Registered controls.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $controls = NULL;

    /**
     * Constructor
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function __construct() {
    	add_action( 'plugins_loaded', array( $this, 'hooks' ) );
    }

    /**
     * Fired elementor options by `plugins_loaded` action hook.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function hooks() {
        // Check if Elementor installed and activated
    	if ( ! did_action( 'elementor/loaded' ) ) {
    		return;
    	}

        // Check for elementor version
    	if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
    		add_action( 'admin_notices', array( $this, 'admin_notice_version' ) );
    		return;
    	}

        // Register categories, widgets, controls 
    	add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
    	add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
        add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );

        // Enqueue front end js
        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enqueue_scripts' ) );

    }

    /**  
     * Register widget args  
     *  
     * @return mixed|null|void  
     */  
    public static function widgets_args() {  
      
        if ( ! is_null( self::$widgets ) ) {
            return self::$widgets;
        }

        return self::$widgets = apply_filters( 'etc/add/elementor/widgets', array() );
    }

    /**  
     * Register controls args  
     *  
     * @return mixed|null|void  
     */  
    public static function controls_args() {  
      
        if ( ! is_null( self::$controls ) ) {
            return self::$controls;
        }

        return self::$controls = apply_filters( 'etc/add/elementor/controls', array() );
    }

    /**
     * Admin notice when minimum required Elementor version not activating.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function admin_notice_version() {

        $this->view->elementor_version_requirment(
            array(
                'error_message' => esc_html__( 'Your Elemenotr version is too old, Please update your elementor plugin to at least '. MINIMUM_ELEMENTOR_VERSION . ' Version', 'xstore-core' ),
            )
        );

    }

    /**
     * Add eight theme Widgets Category
     *
     * @since 2.0.0
     */
    function register_categories( $elements_manager ) {

    	$elements_manager->add_category(
    		'eight_theme',
    		array(
    			'title' => __( 'Eight Theme', 'xstore-core' ),
    			'icon' => 'fa fa-plug',
    		)
    	);

    }

    /**
     * Include widgets files and register them
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function register_widgets( $widgets_manager ) {

        $args = self::widgets_args();
        foreach ( $args as $widget_classes ) {
            foreach ( $widget_classes as $class ) {
                $widgets_manager->register_widget_type( new $class() );
            }
        }

    }

    /**
     * Register controls
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls( $controls_manager ) {

        $args = self::controls_args();
        foreach ( $args as $control_type => $control ) {
            $controls_manager->register_control( $control_type, new $control['class']() );
        }
    }

    /**
     * Register the stylesheets for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_styles() {

    	wp_enqueue_style(
    		'et-core-elementor-style',
    		ET_CORE_URL . 'app/assets/css/elementor.css',
    		array(),
    		ET_CORE_VERSION,
    		'all'
    	);

    }

    /**
     * Register the JavaScript for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts() {

        // @todo make static ver in theme and change way 
        // $theme = wp_get_theme();

        // wp_enqueue_script('etheme', get_template_directory_uri().'/js/etheme.min.js',array('jquery'),$theme->version,true);

    	wp_enqueue_script(
    		'et-core-elementor-script',
    		ET_CORE_URL . 'app/assets/js/elementor.js',
    		array( 'jquery' ),
    		ET_CORE_VERSION,
    		false
    	);

    }
}
