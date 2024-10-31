<?php

class PostLikerator {
	protected $flip_hooks;

	public function __construct(){
		$this->flip_hooks = new Flip_Hooks();
		
		$this->add_actions();
	}

	protected function add_actions(){
		// load scripts
		$this->flip_hooks->add_action('wp_enqueue_scripts', $this, 'load_scripts');

		// add ajax actions
		$this->flip_hooks->add_action('wp_ajax_post_likerator_ajax', $this, 'post_likerator_ajax');
		$this->flip_hooks->add_action('wp_ajax_nopriv_post_likerator_ajax', $this, 'post_likerator_ajax');
		$this->flip_hooks->add_action('wp_footer', $this, 'create_ajax_nonce');

		$this->flip_hooks->register();
	}

	public function load_scripts(){
		global $post;

		wp_enqueue_script( 'post_likerator_scripts', plugin_dir_url( __FILE__ ) . '../javascript/post-likerator.js', array('jquery'), '1.0.0', true );
		wp_localize_script( 'post_likerator_scripts', 'post_likerator', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'post_id' => $post->ID ) );
	}

	public function post_likerator_ajax(){
		check_ajax_referer( 'post_likerator_nonce', 'security' );
		$post_data = json_decode(stripslashes($_POST['data']));

		if (self::user_has_liked($post_data->post_id)){
			// user already liked; remove all likes for user
			$result = $this->remove_like($post_data->post_id);
		}else{
			// user has not yet liked; add a like for user
			$result = $this->add_like($post_data->post_id);
		}

		$response = array(
			'likes' => self::count_post_likes($post_data->post_id),
			'user_has_liked' => self::user_has_liked($post_data->post_id)
		);

		echo json_encode($response);
		exit();
	}

	protected function add_like($post_id){
		global $wpdb;

		$like_data = array(
			'post_id' => $post_id,
			'user_id' => get_current_user_id(),
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		$wpdb->insert($wpdb->prefix . 'post_likerator_likes', $like_data, array('%d', '%d', '%s'));

		return $wpdb->insert_id;
	}

	protected function remove_like($post_id){
		global $wpdb;
		$current_user_id = get_current_user_id();

		if ($current_user_id > 0){
			// remove based on user id
			$result = $wpdb->delete($wpdb->prefix . 'post_likerator_likes', array( 'post_id' => $post_id, 'user_id' => $current_user_id ), array('%d', '%d'));
		}else{
			// remove based on IP address
			$result = $wpdb->delete($wpdb->prefix . 'post_likerator_likes', array( 'post_id' => $post_id, 'ip_address' => $_SERVER['REMOTE_ADDR'] ), array('%d', '%s'));
		}

		return $result;
	}

	public function create_ajax_nonce(){
		$ajax_nonce = wp_create_nonce( "post_likerator_nonce" );

		?>
		<script type="text/javascript">
			var post_likerator_nonce = '<?php echo $ajax_nonce; ?>';
		</script>
		<?php
	}

	public static function get_post_likes($post_id){
		global $wpdb;

		$sql = $wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "post_likerator_likes` WHERE `post_id` = %d;", $post_id);
		$all_likes = $wpdb->get_results($sql);

		return $all_likes;
	}

	public static function count_post_likes($post_id){
		global $wpdb;

		$sql = $wpdb->prepare("SELECT COUNT(*) AS 'likes' FROM `" . $wpdb->prefix . "post_likerator_likes` WHERE `post_id` = %d;", $post_id);
		$count_likes = $wpdb->get_results($sql);

		return (int) $count_likes[0]->likes;
	}

	public static function user_has_liked($post_id){
		global $wpdb;
		$current_user_id = get_current_user_id();

		if ($current_user_id > 0){
			// check based on user id
			$sql = $wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "post_likerator_likes` WHERE `post_id` = %d AND `user_id` = %d;", $post_id, $current_user_id);
			$result = $wpdb->get_results($sql);
		}else{
			// check based on IP address
			$sql = $wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "post_likerator_likes` WHERE `post_id` = %d AND `ip_address` = %s;", $post_id, $_SERVER['REMOTE_ADDR']);
			$result = $wpdb->get_results($sql);
		}

		return count($result) > 0;
	}

	public static function on_plugin_activation(){
		global $wpdb;

		// create table to store likes
		$table_name = $wpdb->prefix . 'post_likerator_likes';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE `$table_name` (
			`like_id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
			`user_id` bigint(20) unsigned DEFAULT '0',
			`ip_address` varchar(255)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}