<?php
/*
Plugin Name: Recently registered widget
Description: List of recently registered users
Author: Tomek
Author URI: http://wp-learning.net
Plugin URI: http://wp-learning.net
Version: 1.1
*/


add_action( 'widgets_init', 'recently_register' );
load_plugin_textdomain( 'recently-registered', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

function recently_register() {
	register_widget( 'WP_Widget_Recently_Registered' );
}

class WP_Widget_Recently_Registered extends WP_Widget {
	function WP_Widget_Recently_Registered() {
		$widget_ops = array( 'classname' => 'widget_featured_entries', 'description' => __('List of recently registered users', 'recently-registered') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'recently-registered-widget' );

		$this->WP_Widget( 'recently-registered-widget', __('Recently registered', 'recently-registered'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
        global $wpdb;
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
 		$limit = $instance['limit'];
		$avatar = $instance['avatar'] ? 'true' : 'false';
		$avatar_align = empty($instance['avatar_align']) ? '' : $instance['avatar_align'];
		$fullname = $instance['fullname'] ? 'true' : 'false';
		$nickname = $instance['nickname'] ? 'true' : 'false';
		$email = $instance['email'] ? 'true' : 'false';
		$clickable_email = $instance['clickable_email'] ? 'true' : 'false';
 		$url = $instance['url'] ? 'true' : 'false';
 		$clickable_url = $instance['clickable_url'] ? 'true' : 'false';
 		$registered = $instance['registered'] ? 'true' : 'false';
 		$role = $instance['role'] ? 'true' : 'false';
  		$post = $instance['post'] ? 'true' : 'false';
  		$comments = $instance['comments'] ? 'true' : 'false';
		$bio = $instance['bio'] ? 'true' : 'false';
	   	$users = $wpdb->get_results( "SELECT ID, user_nicename, user_email, user_url, user_registered FROM $wpdb->users ORDER BY ID DESC LIMIT $limit" );
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
            echo "<center>" .  __('They registered recently:', 'recently-registered') . "</center><br>";
		    foreach($users as $user) {
						$user_role = implode(', ',get_userdata($user->ID)->roles);
						if ($user_role == 'administrator') {
							$roles = __('Administrator', 'recently-registered');
						} elseif ($user_role == 'editor') {
							$roles = __('Editor', 'recently-registered');
						} elseif ($user_role == 'author') {
							$roles = __('Author', 'recently-registered');
						} elseif ($user_role == 'contributor') {
							$roles = __('Contributor', 'recently-registered');
						} elseif ($user_role == 'subscriber') {
							$roles = __('Subscriber', 'recently-registered');
						}
						if($avatar == 'true') {
							$show_avatar = '<div style="float:left">' . get_avatar($user->ID,50) . '</div>';
						}
						if($fullname == 'true') {
							if(get_userdata($user->ID)->first_name == '' || get_userdata($user->ID)->last_name == '') {
								$show_fullname = __('Full name:', 'recently-registered') . '<strong> ' . __('Not set', 'recently-registered') . '</strong><br>';
							} else {
								$show_fullname = __('Full name:', 'recently-registered') . '<strong> ' . get_userdata($user->ID)->first_name . ' ' . get_userdata($user->ID)->last_name . '</strong><br>';
							}
						}
						if($nickname == 'true') {
							$show_nickname = __('Nickname:', 'recently-registered') . '<strong> ' . ucfirst($user->user_nicename) . '</strong><br>';
						}
						if($email == 'true') {
							$show_email = __('E-mail:', 'recently-registered') . '<strong> ' . $user->user_email . '</a></strong><br>';
						}
						if($email == 'true' && $clickable_email == 'true') {
							$show_email = __('E-mail:', 'recently-registered') . '<strong> <a style="color:black;text-decoration:none" href="mailto:' . $user->user_email . '">' . $user->user_email . '</a></strong><br>';
						}
						if($url == 'true') {
							if($user->user_url == '') {
								$show_url = __('Website:', 'recently-registered') . '<strong> ' . __('Not set', 'recently-registered') . '</strong><br>';
							} else {
								$show_url = __('Website:', 'recently-registered') . '<strong> ' . $user->user_url . '</strong><br>';
							}
						}
						if($url == 'true' && $clickable_url == 'true' && $user->user_url != '') {
							$show_url = __('Website:', 'recently-registered') . '<strong> <a style="color:black;text-decoration:none" target="_blank" href="' . $user->user_url . '">' . $user->user_url . '</a></strong><br>';
						}
						if($registered == 'true') {
							$show_registered = __('Joined:', 'recently-registered') . '<strong> ' . $user->user_registered . '</strong><br>';
						}
						if($role == 'true') {
							$show_role = __('Role:', 'recently-registered') . '<strong> ' . $roles . '</strong><br>';
						}
						if($post == 'true') {
							$show_post = __('Posts number:', 'recently-registered') . '<strong> ' . count_user_posts($user->ID) . '</strong><br>';
						}
						if($comments == 'true') {
							$show_comments = __('Comments number:', 'recently-registered') . '<strong> ' . $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE user_id = "' . $user->ID . '"') . '</strong><br>';
						}
						if($bio == 'true') {
							if(get_the_author_meta('description',$user->ID) == '') {
								$show_bio = __('Biography:', 'recently-registered') . '<strong> ' . __('Not set', 'recently-registered') . '</strong><br>';
							} else {
								$show_bio = __('Biography:', 'recently-registered') . '<strong> ' . get_the_author_meta('description',$user->ID) . '</strong><br>';
							}
						}
						if($avatar_align == 'left') {
							echo '<br><div style="float:right;width:100%"><div style="float:left;height:45px;padding:5px">'.$show_avatar.'</div>' . $show_fullname . $show_nickname . $show_email . $show_url . $show_registered . $show_role . $show_post . $show_comments . $show_bio.'</div><hr>';
						} else {
							echo '<br><div style="float:right;width:100%"><div style="float:right;height:45px;padding:5px">'.$show_avatar.'</div>' . $show_fullname . $show_nickname . $show_email . $show_url . $show_registered . $show_role . $show_post . $show_comments . $show_bio.'</div><hr>';
						}
					}
			echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['avatar'] = $new_instance['avatar'];
		$instance['avatar_align'] = $new_instance['avatar_align'];
		$instance['fullname'] = $new_instance['fullname'];
		$instance['nickname'] = $new_instance['nickname'];
		$instance['email'] = $new_instance['email'];
		$instance['clickable_email'] = $new_instance['clickable_email'];
		$instance['url'] = $new_instance['url'];
		$instance['clickable_url'] = $new_instance['clickable_url'];
		$instance['registered'] = $new_instance['registered'];
		$instance['role'] = $new_instance['role'];
		$instance['post'] = $new_instance['post'];
		$instance['comments'] = $new_instance['comments'];
		$instance['bio'] = $new_instance['bio'];
		return $instance;
	}

	function form( $instance ) {
		$avatar_align = $instance["avatar_align"];
		$defaults = array('title' => __('Recently registered', 'recently-registered'),'limit' => '5','avatar' => 'on','fullname' => 'off','nickname' => 'on','email' => 'off','clickable_email' => 'off','url' => 'off','clickable_url' => 'off','registered' => 'off','role' => 'off','post' => 'off','comments' => 'off','bio' => 'on');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
	<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'recently-registered'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /> 
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of users displayed:', 'recently-registered'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
		</p>
		<p>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['avatar'], 'on'); ?> id="<?php echo $this->get_field_id('avatar'); ?>" name="<?php echo $this->get_field_name('avatar'); ?>" /> 
			<label for="<?php echo $this->get_field_id('avatar'); ?>"><?php _e('Show avatar', 'recently-registered'); ?></label></span>
			<span style="padding-left: 30px; display:block"><label for="<?php echo $this->get_field_id('avatar_align'); ?>"><?php _e('Avatar align:', 'recently-registered'); ?>
				<select class='widefat' id="<?php echo $this->get_field_id('avatar_align'); ?>" name="<?php echo $this->get_field_name('avatar_align'); ?>">
					<option value='left'<?php echo ($avatar_align=='left')?'selected':''; ?>><?php _e('Left', 'recently-registered'); ?></option>
					<option value='right'<?php echo ($avatar_align=='right')?'selected':''; ?>><?php _e('Right', 'recently-registered'); ?></option> 
				</select>
			</label></span>

			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['fullname'], 'on'); ?> id="<?php echo $this->get_field_id('fullname'); ?>" name="<?php echo $this->get_field_name('fullname'); ?>" /> 
			<label for="<?php echo $this->get_field_id('fullname'); ?>"><?php _e('Show full name', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['nickname'], 'on'); ?> id="<?php echo $this->get_field_id('nickname'); ?>" name="<?php echo $this->get_field_name('nickname'); ?>" /> 
			<label for="<?php echo $this->get_field_id('nickname'); ?>"><?php _e('Show nickname', 'recently-registered'); ?></label></span>
			<input class="checkbox" type="checkbox" <?php checked($instance['email'], 'on'); ?> id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" /> 
			<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Show e-mail address', 'recently-registered'); ?></label>
			<span style="padding-left: 30px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['clickable_email'], 'on'); ?> id="<?php echo $this->get_field_id('clickable_email'); ?>" name="<?php echo $this->get_field_name('clickable_email'); ?>" /> 
			<label for="<?php echo $this->get_field_id('clickable_email'); ?>"><?php _e('Be clickable', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['url'], 'on'); ?> id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" /> 
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Show website', 'recently-registered'); ?></label></span>
			<span style="padding-left: 30px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['clickable_url'], 'on'); ?> id="<?php echo $this->get_field_id('clickable_url'); ?>" name="<?php echo $this->get_field_name('clickable_url'); ?>" /> 
			<label for="<?php echo $this->get_field_id('clickable_url'); ?>"><?php _e('Be clickable', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['registered'], 'on'); ?> id="<?php echo $this->get_field_id('registered'); ?>" name="<?php echo $this->get_field_name('registered'); ?>" /> 
			<label for="<?php echo $this->get_field_id('registered'); ?>"><?php _e('Show registered date', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['role'], 'on'); ?> id="<?php echo $this->get_field_id('role'); ?>" name="<?php echo $this->get_field_name('role'); ?>" /> 
			<label for="<?php echo $this->get_field_id('role'); ?>"><?php _e('Show role', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['post'], 'on'); ?> id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>" /> 
			<label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('Show all posts number', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['comments'], 'on'); ?> id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" /> 
			<label for="<?php echo $this->get_field_id('comments'); ?>"><?php _e('Show all comments number', 'recently-registered'); ?></label></span>
			<span style="padding-left: 0px; display:block"><input class="checkbox" type="checkbox" <?php checked($instance['bio'], 'on'); ?> id="<?php echo $this->get_field_id('bio'); ?>" name="<?php echo $this->get_field_name('bio'); ?>" /> 
			<label for="<?php echo $this->get_field_id('bio'); ?>"><?php _e('Show biography', 'recently-registered'); ?></label></span>
		</p>
	<?php
	}
}
?>