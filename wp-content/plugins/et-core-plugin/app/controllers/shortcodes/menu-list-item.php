<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Menu List Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Menu_List_Item extends Shortcodes {

    function hooks() {}

    function menu_list_item_shortcode( $atts ) {

    	if(!function_exists('vc_build_link')) return;

        if( !isset($atts['subtitle_google_fonts']) || empty( $atts['subtitle_google_fonts'] ) ) {
            $atts['subtitle_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
        }

        if( !isset($atts['title_google_fonts']) || empty( $atts['title_google_fonts'] ) ) {
            $atts['title_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
        }

    	$atts = shortcode_atts(array(
    		'class'  => '',
    		'css' => '',
    		'title_link'  => '',
    		'title'  => '',
    		'label' => '',
    		'hover_color' => '',
    		'hover_bg' => '',
    		'padding_top' => '',
    		'padding_right' => '',
    		'padding_bottom' => '',
    		'padding_left' => '',
    		'transform' => '',
    		'spacing' => '',
    		'use_custom_fonts_title' => false,
            'title_custom_tag' => 'h2',
            'title_font_container' => '',
            'title_use_theme_fonts' => false,
            'title_google_fonts' => false,
    		'img' => '',
    		'img_size' => '270x170',
            // icons 
    		'icon' => '',
    		'add_icon' => false,
    		'type' => 'fontawesome',
            'svg' => '',
    		'icon_fontawesome' => '',
    		'icon_openiconic' => '',
    		'icon_typicons' => '',
    		'icon_entypo' => '',
    		'icon_linecons' => '',
    		'icon_monosocial' => '',
            'icon_xstore-icons' => '',
    		'icon_color' => '',
    		'icon_color_hover' => '',
    		'icon_bg_color' => '',
    		'icon_bg_color_hover' => '',
    		'icon_size' => '',
            'icon_border_radius' => '',
    		'position' => '',

            // extra settings
            'is_preview' => false
    	), $atts);

        $options = array();

    	$options['page_link'] = get_permalink();

        $options['item_id'] = rand(1000,9999);

        if ( isset( $atts['title_font_container'] ) ) {
     
            $atts['get_title_tag'] = explode('|', $atts['title_font_container']);

            foreach ($atts['get_title_tag'] as $key) {
                $atts['get_title_tag'] = explode(':', $key);
                if ( $atts['get_title_tag'][0] == 'tag' )
                    $atts['title_custom_tag'] = $atts['get_title_tag'][1];
            }

        }

        // create selectors to make css for them 
        $options['selectors'] = array();
        $options['selectors']['item'] = '.menu-item-'.$options['item_id'];
        $options['selectors']['item_hover'] = $options['selectors']['item'] . ':hover';
        $options['selectors']['item_hover'] .= ', ' . $options['selectors']['item'] . ':active';
        $options['selectors']['item_hover'] .= ', ' . $options['selectors']['item'] . '.current-menu-item';

        $options['selectors']['subtitle_holder'] = $options['selectors']['item'] .' > .subitem-title-holder';
        $options['selectors']['subtitle'] = $options['selectors']['item'] .' .subitem-title-holder  .menu-title';
        $options['selectors']['subtitle_hover'] = $options['selectors']['subtitle_holder'] . ':hover ' . $atts['title_custom_tag']; 
        $options['selectors']['subtitle_hover'] .= ', ' . $options['selectors']['subtitle_holder'] . ':active ' . $atts['title_custom_tag']; 
        $options['selectors']['subtitle_hover'] .= ', ' . $options['selectors']['item'] .'.current-menu-item > .subitem-title-holder ' . $atts['title_custom_tag'];

        $options['selectors']['subtitle_tag'] = $options['selectors']['subtitle_holder'] .' '.$atts['title_custom_tag'];

        $options['selectors']['icon'] = $options['selectors']['subtitle_holder'] . ' > .menu-title i';
        $options['selectors']['icon_hover'] = $options['selectors']['subtitle_holder'] . ':hover > .menu-title i';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['item_hover'] => array(),
            $options['selectors']['subtitle'] => array(),
            $options['selectors']['subtitle_hover'] => array(),
            $options['selectors']['subtitle_tag'] => array(),
            $options['selectors']['icon'] => array(),
            $options['selectors']['icon_hover'] => array(),
        );

        // subtitle styles 
        if ( $atts['padding_top'] != '' ) 
           $options['css'][$options['selectors']['subtitle']][] = 'padding-top:' . $atts['padding_top'];

        if ( $atts['padding_right'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-right:' . $atts['padding_right'];

        if ( $atts['padding_bottom'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-bottom:' . $atts['padding_bottom'];

        if ( $atts['padding_left'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-left:' . $atts['padding_left'];

        // subtitle hover styles 
        if ( $atts['hover_color'] != '' )
            $options['css'][$options['selectors']['subtitle_hover']][] = 'color:'.$atts['hover_color'].' !important';

        // subtitle tag styles 
        if ( $atts['spacing'] != '' ) 
            $options['css'][$options['selectors']['subtitle_tag']][] = 'letter-spacing:' . $atts['spacing'];

        if ( $atts['hover_bg'] != '' )
            $options['css'][$options['selectors']['item_hover']][] = 'background-color:'.$atts['hover_bg'].' !important';

        // icon styles 
        if ( $atts['icon_color'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'color:' . $atts['icon_color'];

        if ( $atts['icon_bg_color'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'background-color:' . $atts['icon_bg_color'];

        if ( $atts['icon_size'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'font-size:' . $atts['icon_size'] . ( is_numeric($atts['icon_size']) ? 'px' : '');

       if ( $atts['icon_border_radius'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'border-radius:' . $atts['icon_border_radius'] . ( is_numeric($atts['icon_border_radius']) ? 'px' : '');

        if ( $atts['icon_color_hover'] != '' ) 
           $options['css'][$options['selectors']['icon_hover']][] = 'color:' . $atts['icon_color_hover'];

        if ( $atts['icon_bg_color_hover'] != '' ) 
           $options['css'][$options['selectors']['icon_hover']][] = 'background-color:' . $atts['icon_bg_color_hover'];

       // create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['icon_hover']] ) ) {
            $options['css'][$options['selectors']['icon']][] = 'transition: inherit';
            $options['output_css'][] = $options['selectors']['icon_hover'] . '{'.implode(';', $options['css'][$options['selectors']['icon_hover']]).'}';
        }

        if ( count( $options['css'][$options['selectors']['icon']] ) )
            $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle_tag']] ) )
            $options['output_css'][] = $options['selectors']['subtitle_tag'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle_tag']]).'}';

        if ( count( $options['css'][$options['selectors']['item_hover']] ) )
            $options['output_css'][] = $options['selectors']['item_hover'] . '{'.implode(';', $options['css'][$options['selectors']['item_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle']] ) )
            $options['output_css'][] = $options['selectors']['subtitle'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle_hover']] ) )
            $options['output_css'][] = $options['selectors']['subtitle_hover'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle_hover']]).'}';

    	$options['link'] = ( '||' === $atts['title_link'] ) ? '' : $atts['title_link'];
        unset($atts['title_link']);
    	$options['link'] = vc_build_link( $options['link'] );
        $options['a_title'] = '';
    	$options['a_href'] = '#';
    	$options['a_target'] = '_self';

    	if ( strlen( $options['link']['url'] ) > 0 ) {
    		$options['a_href'] = $options['link']['url'];
    		$options['a_title'] = $options['link']['title'];
    		$options['a_target'] = strlen( $options['link']['target'] ) > 0 ? $options['link']['target'] : '_self';
    		$atts['class'] .= ($options['page_link'] == $options['a_href']) ? 'current-menu-item' : '';
    	}

        if ( $atts['add_icon'] ) {

            switch ($atts['type']) {
                case 'image':
                    $atts['icon'] .= etheme_get_image($atts['img'], $atts['img_size']);
                    break;
                case 'svg':
                    $atts['icon'] = '<i class="icon-svg">' . rawurldecode( base64_decode( $atts['svg'] ) ) . '</i>';
                    break;
                default:
                    vc_icon_element_fonts_enqueue( $atts['type'] );
                    $atts['icon'] = '<i class="' . ( isset( $atts['icon_' . $atts['type']] ) && $atts['icon_' . $atts['type']] != '' ? esc_attr( $atts['icon_' . $atts['type']] ) : 'fa fa-adjust' ) . '"></i>';
                    break;
            }
        }

    	if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' ) )
    		$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );

        $options['img_holder'] = ($atts['type'] == 'image' && trim($atts['icon']) != '' );

        $atts['class'] .= ' menu-item-'.$options['item_id'];

        ob_start();

        ?>

            <li class="menu-item <?php echo esc_attr($atts['class']); ?>">

            <?php if( ! empty( $atts['title'] ) ) : ?>

                <div class="subitem-title-holder <?php echo ( !empty($atts['label'] ) ) ? 'item-has-label menu-label-' . $atts['label'] : ''; ?>">

                    <?php if ( $options['img_holder'] ) { ?>

                        <div class="nav-item-image type-img position-<?php echo esc_attr($atts['position']); ?>">
            
                    <?php } ?>

                            <a href="<?php echo esc_url($options['a_href']); ?>" class="menu-title et-column-title <?php echo esc_attr($atts['transform']); ?>" title="<?php echo esc_attr($options['a_title']); ?>" target="<?php echo esc_attr($options['a_target']); ?>"> 

                            <?php if ($atts['type'] != 'image' && trim($atts['icon']) != '' ) 
                                echo $atts['icon'];

                                echo parent::getHeading('title', $atts);

                                if ( !empty($atts['label']) ) {
                                    switch ($atts['label']) {
                                        case 'hot':
                                            $options['label_text'] = esc_html__('Hot', 'xstore-core');
                                        break;
                                        case 'sale':
                                            $options['label_text'] = esc_html__('Sale', 'xstore-core');
                                        break;
                                        default:
                                            $options['label_text'] = esc_html__('New', 'xstore-core');
                                        break;
                                    }
                                    echo '<span class="label-text">'.$options['label_text'].'</span>';
                                }

                            ?>

                            </a>
            
                        <?php 
                        // if image then show under title
                        if ( $options['img_holder'] ) 
                            echo $atts['icon'] . 
                            '</div>'; // .nav-item-image 
                        ?>
                </div> <?php // .subitem-title-holder ?>

            <?php endif; // ! empty( $atts['title'] ?>

            </li>

        <?php 

        if ( $atts['is_preview'] ) {
            echo parent::initPreviewCss($options['output_css']);
            echo parent::initPreviewJs();
        }
        else {
            parent::initCss($options['output_css']);
        }

        unset($options);
        unset($atts);

        return ob_get_clean();
    }
}
