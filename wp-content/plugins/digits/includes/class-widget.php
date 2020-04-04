<?php


function persian_digits_wp(){
	$widget_options = Digits_IR_Widget();
	echo '<div class="rss-widget">';
		wp_widget_rss_output(array(
			'url' => 'https://digits-wp.ir/rss.php',
			'title' => 'آخرین اخبار و اطلاعیه های افزونه Digits',
			'meta' => array( 'target' => '_new' ),
			'items' => $widget_options['posts_number'],
            		'show_summary' => 1, 
            		'show_author' => 0, 
           		'show_date' => 1 
		));
	?>
		<div style="border-top: 1px solid #e7e7e7; padding-top: 12px !important; font-size: 12px;">
		
			<a href="https://digits-wp.ir" target="_new" title="خانه">وب سایت پشتیبان افزونه Digits</a> 
			

 		</div>
	<?php
	echo "</div>";
}
function persian_digits_wpshow() {
	
	wp_add_dashboard_widget('digits_ir_rss', 'آخرین اخبار و اطلاعیه های افزونه Digits', 'persian_digits_wp', 'wp98_widset_pw' );

}
function Digits_IR_Widget() {	
	$defaults = array( 'posts_number' => 3 );
	if ( ( !$options = get_option( 'digits_ir_rss' ) ) || !is_array($options) )
		$options = array();
	return array_merge( $defaults, $options );
}
function wp98_widset_pw() {
	$options = Digits_IR_Widget();
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'digits_ir_rss' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'digits_ir_rss', $options );
	}
?>
 
		<p>
			<label for="posts_number">تعداد نوشته های قابل نمایش در ابزارک افزونه Digits:
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 3; $i <= 20; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>
 
<?php
 }
add_action('wp_dashboard_setup', 'persian_digits_wpshow' );

?>