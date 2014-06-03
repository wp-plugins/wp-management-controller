<?php
/**
 * WpController_Widget Class
 */
if(basename($_SERVER['SCRIPT_FILENAME']) == "widget.class.php"):
    exit;
endif;
class WpController_Widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function WpController_Widget() {
        parent::WP_Widget(false, $name = 'WpController', array('description' => 'WpController widget.'));	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	
        extract( $args );
        $instance['title'] = 'WpController';
        $instance['message'] = 'We are happily using <a href="http://wpcontroller.co.uk" target="_blank">WpController</a>';
        $title 		= apply_filters('widget_title', $instance['title']);
        $message 	= $instance['message'];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
								<li><?php echo $message; ?></li>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }
    
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
        $title 		= 'WpController';
        $message	= 'We are happily using <a href="http://wpcontroller.co.uk" target="_blank">WpController</a>';
        echo '<p>'.$message.'</p>';
    }
 
 
} // end class example_widget

$mwp_worker_brand = get_option("mwp_worker_brand");
$worker_brand = 0;    	
if(is_array($mwp_worker_brand)){
    		if($mwp_worker_brand['name'] || $mwp_worker_brand['desc'] || $mwp_worker_brand['author'] || $mwp_worker_brand['author_url']){
    			$worker_brand= 1;
    		} 
}
if(!$worker_brand){
	add_action('widgets_init', create_function('', 'return register_widget("WpController_Widget");'));
}
?>