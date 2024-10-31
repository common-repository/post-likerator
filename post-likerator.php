<?php
/*
Plugin Name: Post Likerator
Description: Simple like/unlike function for posts. No dislikes. Bring your own CSS.
Version: 1.0.0
Author: Flipeleven
Author URI: http://flipeleven.com
Text Domain: post_likerator
*/



if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once(plugin_dir_path( __FILE__ ) . 'library/main.php');

register_activation_hook(__FILE__, array('PostLikerator', 'on_plugin_activation'));

function post_likerator_run(){
	$post_likerator = new PostLikerator();
}

post_likerator_run();


function post_likerator($post_id = false, $echo = true){
	global $post;

	if (empty($post_id)){
		$post_id = $post->ID;
	}

	if ($post_id == 0){
		return false;
	}

	// get current like count
	$likes = PostLikerator::count_post_likes($post_id);

	// see if current user has liked the post
	$has_liked = PostLikerator::user_has_liked($post_id);

	$button = '<button class="post-likerator-button';

	if ($has_liked){
		$button .= ' user-has-liked';
	}

	$button .= '" data-post-id="'.$post_id.'">';
	$button .= '<span class="post-likerator-icon"></span>';
	$button .= '<span class="post-likerator-count">'.$likes.'</span>';
	$button .= '</button>';

	if ($echo === false){
		return $button;
	}else{
		echo $button;
		return true;
	}
}