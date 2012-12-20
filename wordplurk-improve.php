<?php
/*
Plugin Name: WordPlurk improve
Plugin URI: http://www.renn999.twbbs.org/wordplurk-improve
Description: Generates Plurk Updates when a new Post is Published, Useing Official Plurk api, and Setting improve. Orginal Home page <a href="http://blog.bluefur.com/wordplurk">http://blog.bluefur.com/wordplurk</a>
Author: <a href="http://www.renn999.twbbs.org">Renn999</a>, bluefur, Speedboxer.
Version: 3.2
Text Domain: wordplurk-improve
*/

define('WPLURK_DIR',dirname(__FILE__));
if(function_exists('load_plugin_textdomain')){
	$plugin_dir = basename(WPLURK_DIR);
	load_plugin_textdomain( 'wordplurk-improve', false, $plugin_dir . '/language' );
}

require_once(WPLURK_DIR.'/OAuthSimple.php');
require_once(WPLURK_DIR.'/wordplurk-improve_adminpage.php');
require_once(WPLURK_DIR.'/wordplurk-improve_shorturlapi.php');
require_once(WPLURK_DIR.'/wordplurk-improve_plurkapicalls.php');
require_once(WPLURK_DIR.'/wordplurk-improve_plurkcomment.php');

function wordplurk_post_now_published($new_status, $old_status, $post)
{
	$post_id = $post->ID;
	$plurk_template = get_option('wordplurk_template', 0);
	$shurl_api_user = get_option('wordplurk_suapi_user', 0);
	$shurl_api_key = get_option('wordplurk_suapi_key', 0);
	$has_been_plurked = get_post_meta($post_id, 'has_been_plurked', true);
	query_posts('p=' . $post_id);
	$i = json_decode(get_option('wordplurk_login'),true);
	if (isset($i['success'])):
		the_post();
		if(isset($_POST['wordplurk_plurkornot'])):
			add_post_meta($post_id, 'wordplurk_plurkornot', $_POST['wordplurk_plurkornot']);
			$new_status = 'trash';
		else:
			delete_post_meta($post_id, 'wordplurk_plurkornot');
		endif;
		$plurkornot = get_post_meta($post_id, 'wordplurk_plurkornot', true);
		if($new_status == 'publish' && $plurkornot != 1):
			switch(get_option('wordplurk_shorturl_en','1')):
				case 1:
					$post_url = curl_file_get_contents('http://tinyurl.com/api-create.php?url=' . urlencode(get_permalink()));
					break;
				case 2:
					$post_url = 'http://ppt.cc/' . curl_file_get_contents('http://ppt.cc/pttcoder.php?url=' . urlencode(get_permalink()));
					break;
				case 3:
					$post_url = short_googl(get_permalink());
					break;
				case 4:
					$post_url = curl_file_get_contents('http://is.gd/api.php?longurl=' . urlencode(get_permalink()));
					break;
				case 5:
					$post_url = curl_file_get_contents('http://api.bit.ly/v3/shorten?domain=bit.ly&login='.$shurl_api_user.'&apiKey='.$shurl_api_key.'&longUrl='.urlencode(get_permalink()).'&format=txt');
					break;
				case 6:
					$post_url = curl_file_get_contents('http://api.bit.ly/v3/shorten?domain=j.mp&login='.$shurl_api_user.'&apiKey='.$shurl_api_key.'&longUrl='.urlencode(get_permalink()).'&format=txt');
					break;
				case 7:
					$post_url = curl_file_get_contents('http://4fun.tw/gen.php?via=wp&url=' . urlencode(get_permalink()));
					break;
				case 8:
					if(function_exists('wp_get_shortlink'))
						$post_url =  wp_get_shortlink();
					break;
			endswitch;
			$replace = array(
				'%%url%%',
				'%%title%%',
				'%%content%%'
				);
			if(!$post_url)
				$post_url = get_permalink();
			mb_internal_encoding("UTF-8");
			$title = strip_tags(trim(get_the_title()));
			$urllen = strlen($post_url);
			$maxlen = 140 - mb_strlen(str_replace($replace, array('', '',''), trim($plurk_template))) - (($urllen<30)?$urllen:30);
			if (preg_match('/%%content%%/',$plurk_template) && $maxlen > 40):
				$content = strip_tags(trim(get_the_content()));
				$content = str_replace(array("\n","\r"),'',$content);
				$titlelen = mb_strlen($title);
				$contlen = mb_strlen($content);
				if($contlen + $titlelen > $maxlen):
					if($titlelen <= 40):
						$content = mb_substr($content, 0, $maxlen-$titlelen-3).'...';
					elseif($contlen < $maxlen-40):
						$title = mb_substr($title, 0, $maxlen-$contlen-3).'...';
					else:
						$title = mb_substr($title, 0, 37).'...';
						$content = mb_substr($content, 0, $maxlen -43).'...';
					endif;
				endif;
			else:
				$content ='';
				if ( mb_strlen($title) > $maxlen)
					$title = mb_substr($title, 0, $maxlen -3).'...';
			endif;
			$replace_var = array($post_url, $title,$content);
			$i = str_replace($replace, $replace_var, $plurk_template);
		endif;
		if($new_status == 'publish' && $old_status == $new_status && $has_been_plurked == 'yes' && $plurkornot != 1):
			$status_data=array(
				'plurk_id' => base_convert(get_post_meta($post_id, 'plurk_id', true), 36, 10),
				'content' => "$i"
			);
			plurk_update_status($status_data, 'edit');
		elseif($new_status == 'publish' && $old_status != $new_status && $has_been_plurked != 'yes' && $plurkornot != 1):
			$plurk_qualifier = get_option('wordplurk_qualifier', 0);
			$plurk_language_set = get_option('wordplurk_language_set', 0);
			$status_data=array( 
				'qualifier' => "$plurk_qualifier",
				'content' => "$i",
				'no_comments' => '0',
				'lang' => "$plurk_language_set"
			);

			$plurk_return_info = json_decode(plurk_update_status($status_data, 'post'),true);

			add_post_meta($post_id, 'has_been_plurked', 'yes');
			add_post_meta($post_id, 'plurk_id', base_convert($plurk_return_info['plurk_id'], 10, 36));

		elseif($new_status == 'trash' && $has_been_plurked == 'yes'):
			$status_data=array(
				'plurk_id' => base_convert(get_post_meta($post_id, 'plurk_id', true), 36, 10)
			);
			plurk_update_status($status_data, 'trash');
			delete_post_meta($post_id, 'plurk_id');
			delete_post_meta($post_id, 'has_been_plurked');
		endif;
	endif;
}

add_action('transition_post_status', 'wordplurk_post_now_published', 10, 3);


function wordplurk_add_plugin_option(){
	if (function_exists('add_options_page'))
		add_options_page('WordPlurk improve', 'WordPlurk improve', 'administrator', basename(WPLURK_DIR).'/wordplurk-improve.php', 'wordplurk_options_subpanel');
	if( function_exists( 'add_meta_box' )):
		add_meta_box( 'wordplurk_sectionid', __( 'Wordplurk improve', 'wordplurk-improve' ), 'wordplurk_inner_custom_box', 'post' );
	else:
		add_action('dbx_post_advanced', 'wordplurk_old_custom_box' );
	endif;
}

function wordplurk_inner_custom_box() {
	echo '<input type="hidden" name="wordplurk_noncename" id="wordplurk_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<input type="checkbox" name="wordplurk_plurkornot" value="1"';
	if(get_post_meta($_GET['post'], 'wordplurk_plurkornot', true)==1)
		echo ' checked="checked';
	echo '">';
	echo '<label for="wordplurk_plurkornot">' . __("Do not Plurk this post. (if the post hes been plurked, the plurk will be delete.)", 'wordplurk-improve' ). '</label> ';
}

/* Prints the edit form for pre-WordPress 2.5 post/page */
function wordplurk_old_custom_box() {

	echo '<div class="dbx-b-ox-wrapper">' . "\n";
	echo '<fieldset id="wordplurk_fieldsetid" class="dbx-box">' . "\n";
	echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . __( 'Wordplurk improve', 'wordplurk-improve' ) . "</h3></div>"; 
	echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
	wordplurk_inner_custom_box();
	echo "</div></div></fieldset></div>\n";
}

function wordplurk_init(){
	if(function_exists('register_setting')):
		register_setting('wordplurk-options', 'wordplurk_qualifier');
		register_setting('wordplurk-options', 'wordplurk_language_set');
		register_setting('wordplurk-options', 'wordplurk_template');
		register_setting('wordplurk-options', 'wordplurk_shorturl_en');
		register_setting('wordplurk-options', 'wordplurk_Plurk2tw_en');
		register_setting('wordplurk-options', 'wordplurk_login');
		register_setting('wordplurk-options', 'wordplurk_version');
	endif;
}

if(is_admin()){
	add_action('admin_menu', 'wordplurk_add_plugin_option');
	add_action('admin_init', 'wordplurk_init');
}
add_action('admin_notices', 'wordplurk_notice');
add_filter('the_content', 'wordplurkcomment');


function wordplurk_load_jquery() {
	if(!is_admin())
		wp_enqueue_script('jquery', false, array(), false, true);
}

add_action('init', 'wordplurk_load_jquery');

function wordplurk_update() {
	$now_version = 3.2;
	$db_version = get_option('wordplurk_version', 0);
	if($db_version < 3.1):
		if(function_exists('unregister_setting')):
			unregister_setting('wordplurk-options', 'wordplurk_username');
			unregister_setting('wordplurk-options', 'wordplurk_password');
			unregister_setting('wordplurk-options', 'wordplurk_apikey');
			unregister_setting('wordplurk-options', 'wordplurk_cookie_exp_time');
			unregister_setting('wordplurk-options', 'wordplurk_cookie');
			unregister_setting('wordplurk-options', 'wordplurk_cmrt');
		endif;
		if(function_exists('delete_option')):
			delete_option('wordplurk_username');
			delete_option('wordplurk_password');
			delete_option('wordplurk_apikey');
			delete_option('wordplurk_cookie_exp_time');
			delete_option('wordplurk_cookie');
			delete_option('wordplurk_cmrt');
		endif;
		global $wpdb;
		global $table_prefix;
		$post_type = array('post_type' => 'post','numberposts' => -1);
		$my_posts = get_posts($post_type);
		foreach ( $my_posts as $my_post ):
			$post_id = $my_post->ID;
			$has_been_plurked = get_post_meta($post_id, 'has_been_plurked', true);
			$plurkedornot = get_post_meta($post_id, 'wordplurk_plurkornot', true);
			if( $has_been_plurked != 'yes' && $plurkedornot != 1 ):
				add_post_meta($post_id, 'wordplurk_plurkornot', '1');
			endif;
		endforeach;
		$tablename = $table_prefix.'wordplurk_comment_cache';
		$sql = 'SHOW TABLES LIKE \'' . $tablename . '\'';
		$results = $wpdb->query($sql);
		if ($results != 0):
			$sql = 'DROP TABLE `'.$tablename.'`';
			$results = $wpdb->query($sql);
		endif;
	endif;
	if($db_version < 3.2):
		if(function_exists('unregister_setting')):
			unregister_setting('wordplurk-options', 'wordplurk_suapi_user');
			unregister_setting('wordplurk-options', 'wordplurk_suapi_key');
		endif;
		if(function_exists('delete_option')):
			delete_option('wordplurk_suapi_user');
			delete_option('wordplurk_suapi_key');
		endif;
	endif;
	update_option('wordplurk_version', $now_version);
}
add_action('plugins_loaded', 'wordplurk_update');
?>
