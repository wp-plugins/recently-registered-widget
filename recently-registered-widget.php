<?php
/*
Plugin Name: Recently registered widget
Description: List of recently registered users
Author: Tomek
Author URI: http://wp-learning.net
Plugin URI: http://wp-learning.net
Version: 0.2
*/


add_action( 'widgets_init', 'recently_register' );
load_plugin_textdomain( 'recently-registered', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

function recently_register() {
	register_widget( 'WP_Widget_Recently_Registered' );
}

class WP_Widget_Recently_Registered extends WP_Widget {
	function WP_Widget_Recently_Registered() {		$widget_ops = array( 'classname' => 'widget_featured_entries', 'description' => __('List of recently registered users', 'recently-registered') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'recently-registered-widget' );

		$this->WP_Widget( 'recently-registered-widget', __('Recently registered', 'recently-registered'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
        global $wpdb;
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
   	   	$users = $wpdb->get_results( "SELECT ID, user_nicename FROM $wpdb->users ORDER BY ID DESC LIMIT 5" );
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
            echo "<center>" .  __('They registered recently:', 'recently-registered') . "</center><br>";
		    foreach($users as $user) {
				IF(get_the_author_meta('description',$user->ID) == "") {
		          		echo '<div style="height:50px"><div style="padding:5px;float:left">' . get_avatar($user->ID,50) . '</div><div>' . __('Nickname:', 'users') . '<strong> ' . $user->user_nicename . '</strong></div></div><br>';
		           	} else {
		          		echo '<div><div style="padding:5px;float:left">' . get_avatar($user->ID,50) . '</div><div>' . __('Nickname:', 'users') . '<strong> ' . $user->user_nicename . '</strong><br>' . __('Introducing:', 'users') . '<strong> ' . get_the_author_meta('description',$user->ID); '</strong></div><>/div><br>';
		           	}
           	}
			echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('Recently registered', 'recently-registered'));
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}
?>