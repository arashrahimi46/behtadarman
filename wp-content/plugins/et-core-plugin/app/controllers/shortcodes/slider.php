<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Slider shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Slider extends Shortcodes {

	function hooks() {}

	function slider_shortcode( $atts, $content ) {

		if (  ( isset( $_GET[ 'vc_editable' ] ) && $_GET[ 'vc_editable' ] == 'true' ) || 
			( isset( $_POST[ 'action' ] ) && isset( $_POST[ 'vc_inline' ] ) && 
			$_POST[ 'action' ] == 'vc_load_shortcode' && $_POST[ 'vc_inline' ] == 'true' ) ) { 
			return '<div class="woocommerce-info">'.esc_html__('Unfortunately this element isn\'t available in', 'xstore-core').
			' <em>'.esc_html__('Frontend Editor', 'xstore-core').'</em> '.
			esc_html__('at the moment.', 'xstore-core').' '.esc_html__('Use the', 'xstore-core').
			' <em>'.esc_html__('Backend Editor', 'xstore-core').'</em> '.esc_html__('to change the options. We are sorry for any inconvenience.', 'xstore-core').'</div>';
		}

		$atts = shortcode_atts(array(
			'height' => 'full',
			'height_value' => '',
			'height_value_mobile' => '',
			'stretch' => '',
			'nav' => 'arrows_bullets',
			'nav_color' => '#222',
			'arrows_bg_color' => '#e1e1e1',
			'slider_autoplay' => false,
			'slider_speed' => 300,
			'slider_loop' => 'yes',
			'slider_interval' => 5000,
			'nav_on_hover' => '',
			'transition_effect' => '',
			'bg_color' => '',
			'el_class' => '',
			'is_preview' => false
		), $atts);

		$options = array();

		$options['box_id'] = rand(1000,10000);

		if ( $atts['slider_autoplay'] ) 
			$atts['slider_autoplay'] = $atts['slider_interval'];

		// selectors 
        $options['selectors'] = array();
        
        $options['selectors']['slider'] = '.slider-'.$options['box_id'];
        $options['selectors']['loader'] = '.slider-'.$options['box_id'] . ' .et-loader:before';
        $options['selectors']['pagination'] = $options['selectors']['slider'] . ' span.swiper-pagination-bullet';
        $options['selectors']['navigation'] = $options['selectors']['slider'] . ' .swiper-custom-left, ' . ' ' . $options['selectors']['slider'] . ' .swiper-custom-right';
        $options['selectors']['navigation_hover'] = $options['selectors']['slider'] . ' .swiper-custom-left:hover, ' . ' ' . $options['selectors']['slider'] . ' .swiper-custom-right:hover';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['slider'] => array(),
            $options['selectors']['loader'] => array(),
            $options['selectors']['pagination'] => array(),
            $options['selectors']['navigation'] => array(),
            $options['selectors']['navigation_hover'] => array()
        );

        if ( $atts['height'] != '' && $atts['height_value'] != '' ) 
			$options['css'][$options['selectors']['slider']][] = 'height:'.$atts['height_value'];

		if ( $atts['bg_color'] != '' )
			$options['css'][$options['selectors']['slider']][] = $options['css'][$options['selectors']['loader']][] = 'background-color:'.$atts['bg_color'];

		if ( $atts['nav_color'] != '' ) 
			$options['css'][$options['selectors']['pagination']][] = $options['css'][$options['selectors']['navigation']][] = 'color:'.$atts['nav_color'];

		if ( $atts['arrows_bg_color'] != '' ) 
			$options['css'][$options['selectors']['navigation']][] = $options['css'][$options['selectors']['navigation_hover']][] = 'background-color: '.$atts['arrows_bg_color'].' !important';

		// create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['pagination']] ) )
            $options['output_css'][] = $options['selectors']['pagination'] . '{'.implode(';', $options['css'][$options['selectors']['pagination']]).'}';

        if ( count( $options['css'][$options['selectors']['navigation']] ) )
            $options['output_css'][] = $options['selectors']['navigation'] . '{'.implode(';', $options['css'][$options['selectors']['navigation']]).'}';

        if ( count( $options['css'][$options['selectors']['navigation_hover']] ) )
            $options['output_css'][] = $options['selectors']['navigation_hover'] . '{'.implode(';', $options['css'][$options['selectors']['navigation_hover']]).'}';

        $options['frontend_css'] = array();

        if ( count( $options['css'][$options['selectors']['loader']] ) )
			$options['frontend_css'][] = $options['selectors']['loader'] . '{'.implode(';', $options['css'][$options['selectors']['loader']]).'}';
   		
   		if ( $atts['height'] != '' && $atts['height_value_mobile'] != '' ) 
   			$options['frontend_css'][] = '@media only screen and (max-width: 992px) {' . $options['selectors']['slider'] . '{' . 'height:' . $atts['height_value_mobile'] . '!important;';

		$atts['el_class'] .= ' slider-' . esc_attr($options['box_id']);

		if ( $atts['height'] == 'full' ) 
			$atts['el_class'] .= ' full-height';

		if ( $atts['nav_on_hover'] ) 
			$atts['el_class'] .= ' nav-on-hover';

		$options['wrapper_attr'] = array();

		if ( count($options['output_css']) ) {
			$atts['el_class'] .= ' etheme-css';
			$options['wrapper_attr'][] = 'data-css="' . implode(' ', $options['output_css']) . '"';
		}

		if ( count( $options['css'][$options['selectors']['slider']] ) )
        	$options['wrapper_attr'][] = 'style="' . implode(';', $options['css'][$options['selectors']['slider']]) . '"';


		$options['attr'] = array();
		$options['attr'][] = 'data-autoplay="'.esc_attr($atts['slider_autoplay']).'"';
		$options['attr'][] = 'data-speed="' . esc_attr($atts['slider_speed']) . '"';
		$options['attr'][] = 'data-effect="' . esc_attr($atts['transition_effect']) . '"';

		if ( $atts['slider_loop'] ) 
			$options['attr'][] = 'data-loop="true"';
				
		ob_start(); 

		if ( count($options['frontend_css']) ) { ?>
			<style><?php echo implode(' ', $options['frontend_css']); ?></style>
		<?php } ?>

		<div class="swiper-entry et-slider arrows-long-path <?php echo esc_attr($atts['el_class']); ?>" <?php echo implode(' ', $options['wrapper_attr']); ?>>
			<div class="swiper-container" data-centeredSlides="1" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="1" data-lt-slides="1" data-slides-per-view="1" <?php echo implode(' ', $options['attr']); ?>>
				<div class="et-loader swiper-lazy-preloader"></div>
				<!-- Additional required wrapper -->
				<div class="swiper-wrapper">
					<!-- Slides -->
					<?php
						etheme_override_shortcodes();
						echo do_shortcode($content);
						etheme_restore_shortcodes(); 
					?>
				</div>
				<?php if ( in_array( $atts['nav'], array( 'bullets', 'arrows_bullets' ) ) ) { ?>
					<div class="swiper-pagination swiper-nav"></div>
				<?php } 

				if ( in_array( $atts['nav'], array( 'arrows', 'arrows_bullets' ) ) ) { ?>
					<div class="swiper-custom-left swiper-nav"></div>
					<div class="swiper-custom-right swiper-nav"></div>
				<?php } ?>
			</div>
		</div>

		<?php 

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewJs();

		unset($options);
		unset($atts); 
		
		?>

		<?php return ob_get_clean();
	}
}
