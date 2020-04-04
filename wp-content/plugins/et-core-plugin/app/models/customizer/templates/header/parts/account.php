<?php
    /**
     * The template for displaying header account block
     *
     * @since   1.4.0
     * @version 1.0.4
     * last changes in 1.5.5
     */
 ?>

<?php 

    global $et_builder_globals;

	$element_options = array();
	$element_options['account_style_et-desktop'] = Kirki::get_option( 'account_style_et-desktop' );
	$element_options['account_style_et-desktop'] = apply_filters('account_style', $element_options['account_style_et-desktop']);

	$element_options['account_dropdown_position_et-desktop'] =  Kirki::get_option( 'account_dropdown_position_et-desktop' );

	$element_options['wrapper_class'] = '';

    if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['wrapper_class'] .= ' justify-content-inherit';
    }
    else {
    	$element_options['wrapper_class'] .= ' login-link';
    }

    $element_options['wrapper_class'] .= ' account-' . $element_options['account_style_et-desktop'];
    $element_options['wrapper_class'] .= ( $element_options['account_dropdown_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['account_dropdown_position_et-desktop'] : '';
    $element_options['wrapper_class'] .= ' et-content-dropdown';
    $element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' );

    $element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
    $element_options['attributes'] = array();
    if ( $element_options['is_customize_preview'] ) 
        $element_options['attributes'] = array(
            'data-title="' . esc_html__( 'Account', 'xstore-core' ) . '"',
            'data-element="account"'
        ); 

?>	

<div class="et_element et_b_header-account flex align-items-center <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo header_account_callback(); ?>
</div>

<?php unset($element_options); ?>