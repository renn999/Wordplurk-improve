<?php
function wordplurk_options_subpanel()
{
if(function_exists('curl_init')):
	if(get_option('wordplurk_login') == 'uncheck'){
		$plurk_return_info = json_decode(plurk_login(),true);
		if($plurk_return_info['success_text']== 'ok')
			update_option('wordplurk_login','true');
		else
			update_option('wordplurk_login','false');
	}

	if(get_option('wordplurk_login') == 'false'){
		echo '<div class="updated fade" style="padding: 5px;">' . sprintf(__('Please check your Plurk username and password there is something wrong with pre login', 'wordplurk-improve')) . '</div>';
	}
	?>
	<script type="text/javascript">
	var $j = jQuery.noConflict();
	$j().ready(function(){
		$j('select#shorturl').change(function(){
			$j('tr#select_option').hide();
			$j('div#message').empty();
			switch($j('select#shorturl option:selected').text()){
				case 'bit.ly':
				case 'j.mp':
					$j('div#message').append('<?php _e('If you have bitly pro just select bit.ly or j.mp are the same.', 'wordplurk-improve');?>').show();
					$j('tr#select_option').show();
					break;
				case 'Wordpress init shorturl function':
					$j('div#message').append('<?php _e('Use Wordpress 3.0 wp_get_shortlink function to generate short url.', 'wordplurk-improve');?>').show();
					break;
			}
		});
		$j('select#shorturl').change();
	});
	</script>
	<div class="wrap">
	<h2>WordPlurk <?php _e('Settings', 'wordplurk-improve'); ?></h2>
	<p><?php _e('Please enter your Plurk username and password below. All fields are required.', 'wordplurk-improve'); ?></p>
	<form method="post" action="options.php">
	<?php
	if(function_exists('settings_fields')):
		settings_fields('wordplurk-options');
	else:
		wp_nonce_field('update-options');
	?>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="wordplurk_username, wordplurk_password, wordplurk_apikey,wordplurk_qualifier,wordplurk_language_set,wordplurk_template,wordplurk_shorturl_en,wordplurk_Plurk2tw_en,wordplurk_login,wordplurk_suapi_user,wordplurk_suapi_key,wordplurk_cmrt" />
	<?php endif; ?>
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><label for="wordplurk_username">Plurk <?php _e('Username', 'wordplurk-improve'); ?></label></th>
	<td><input type="text" name="wordplurk_username" value="<?php echo get_option('wordplurk_username'); ?>">
	<br />
	<?php _e('Enter the username of the Plurk account you want to post updates to.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_password">Plurk <?php _e('Password', 'wordplurk-improve'); ?></label></th>
	<td><input type="password" name="wordplurk_password" value="<?php echo get_option('wordplurk_password'); ?>">
	<br />
	<?php _e('Enter the password of the Plurk account entered above.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_apikey">Plurk <?php _e('ApiKey', 'wordplurk-improve'); ?></label></th>
	<td><input type="text" name="wordplurk_apikey" value="<?php echo get_option('wordplurk_apikey'); ?>">
	<br />
	<?php _e('Enter the Api Key of the Plurk account entered above.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_template"><?php _e('Wordplurk Template', 'wordplurk-improve'); ?></label></th>
	<td><input type="text" name="wordplurk_template" value="<?php echo get_option('wordplurk_template','%%url%% - %%title%%'); ?>">
	<br />
	<?php _e('What template do you want to show on Plurk.', 'wordplurk-improve');?><br>
	<i>%%url%%</i> - <?php _e('Post URL Link', 'wordplurk-improve');?><br>
	<i>%%title%%</i> - <?php _e('Post Title', 'wordplurk-improve');?><br>
	<i>%%content%%</i> - <?php _e('Part of the Post', 'wordplurk-improve');?>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_qualifier">Plurk <?php _e('Qualifier', 'wordplurk-improve'); ?></label></th>
	<td><select type="text" name="wordplurk_qualifier">
	<?php
	$plurk_qual=array(
		'loves' => sprintf(__('loves', 'wordplurk-improve')), 
		'likes' => sprintf(__('likes', 'wordplurk-improve')), 
		'shares' => sprintf(__('shares', 'wordplurk-improve')), 
		'gives' => sprintf(__('gives', 'wordplurk-improve')), 
		'hates' => sprintf(__('hates', 'wordplurk-improve')), 
		'wants' => sprintf(__('wants', 'wordplurk-improve')), 
		'has' => sprintf(__('has', 'wordplurk-improve')), 
		'will' => sprintf(__('will', 'wordplurk-improve')), 
		'asks' => sprintf(__('asks', 'wordplurk-improve')), 
		'wishes' => sprintf(__('wishes', 'wordplurk-improve')), 
		'was' => sprintf(__('was', 'wordplurk-improve')), 
		'feels' => sprintf(__('feels', 'wordplurk-improve')), 
		'thinks' => sprintf(__('thinks', 'wordplurk-improve')), 
		'says' => sprintf(__('says', 'wordplurk-improve')), 
		'is' => sprintf(__('is', 'wordplurk-improve')), 
		':' => sprintf(__(':', 'wordplurk-improve')), 
		'freestyle' => sprintf(__('freestyle', 'wordplurk-improve')), 
		'hopes' => sprintf(__('hopes', 'wordplurk-improve')), 
		'needs' => sprintf(__('needs', 'wordplurk-improve')), 
		'wonders' => sprintf(__('wonders', 'wordplurk-improve'))
	);
	foreach($plurk_qual as $key => $val){
		echo "\t\t<option value=\"$key\"";
		echo (get_option('wordplurk_qualifier','shares')==$key)?' selected="selected"':'';
		echo ">".$val."</option>\n";
	}
	unset($plurk_qual);
	?>
	</select>
	<br />
	<?php _e('Select the Qualifier of the Plurk account entered above.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_language_set">Plurk <?php _e('language set', 'wordplurk-improve'); ?></label></th>
	<td><select type="text" name="wordplurk_language_set">
	<?php
	$plurk_lang=array(
		'en' => 'English',
		'pt_BR' => 'Português',
		'cn' => '中文 (中国)',
		'ca' => 'Català',
		'el' => 'Ελληνικά',
		'dk' => 'Dansk',
		'de' => 'Deutsch',
		'es' => 'Español',
		'sv' => 'Svenska',
		'nb' => 'Norsk bokmål',
		'hi' => 'Hindi',
		'ro' => 'Română',
		'hr' => 'Hrvatski',
		'fr' => 'Français',
		'ru' => 'Pусский',
		'it' => 'Italiano ',
		'ja' => '日本語',
		'he' => 'עברית',
		'hu' => 'Magyar',
		'ne' => 'Nederlands',
		'th' => 'ไทย',
		'ta_fp' => 'Filipino',
		'in' => 'Bahasa Indonesia',
		'pl' => 'Polski',
		'ar' => 'العربية',
		'fi' => 'Finnish',
		'tr_ch' => '中文 (繁體中文)',
		'tr' => 'Türkçe',
		'ga' => 'Gaeilge',
		'sk' => 'Slovenský',
		'uk' => 'українська',
		'fa' => 'فارسی'
	);
	foreach($plurk_lang as $key => $val){
		echo "\t\t<option value=\"$key\"";
		echo (get_option('wordplurk_language_set','en')==$key)?' selected="selected"':'';
		echo ">".$val."</option>\n";
	}
	unset($plurk_lang);
	?>
	</select>
	<br />
	<?php _e('Select the language set of the Plurk account entered above.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_shorturl_en"><?php _e('Short url', 'wordplurk-improve'); ?></label></th>
	<td><select type="text" name="wordplurk_shorturl_en" id="shorturl">
	<?php
	$shorturl_api=array(
		'0' => 'Disable',
		'1'	=> 'Tinyurl',
		'2' => 'ppt.cc',
		'3' => 'goo.gl',
		'4' => 'is.gd',
		'5' => 'bit.ly',
		'6' => 'j.mp',
		'7' => '4fun.tw'
	);
	
	if(function_exists('wp_get_shortlink'))
		$shorturl_api['8'] = 'Wordpress init shorturl function';
	
	foreach($shorturl_api as $key => $val){
		echo "\t\t<option value=\"$key\"";
		echo (get_option('wordplurk_shorturl_en','1')==$key)?' selected="selected"':'';
		echo ">".$val."</option>\n";
	}
	unset($shorturl_api);
	?>
	</select>
	<br />
	<?php _e('Using the shorturl service to short post url.', 'wordplurk-improve');?><br />
	<div id='message'></div>
	</td>
	</tr>
	<tr valign="top" id='select_option'>
	<th scope="row"><label for="wordplurk_suapi_user"><?php _e('Short url Username', 'wordplurk-improve'); ?></label></th>
	<td><input type="text" name="wordplurk_suapi_user" value="<?php echo get_option('wordplurk_suapi_user'); ?>">
	<br />
	<?php _e('You need to fill in the username to enable the Short URL service.', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top" id='select_option'>
	<th scope="row"><label for="wordplurk_suapi_key"><?php _e('Short url API key', 'wordplurk-improve'); ?></label></th>
	<td><input type="text" name="wordplurk_suapi_key" value="<?php echo get_option('wordplurk_suapi_key'); ?>">
	<br />
	<?php _e('You need to fill in the API KEY to enable the Short URL service', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_Plurk2tw_en"><?php _e('Post Plurk Responses', 'wordplurk-improve'); ?></label></th>
	<td><input type="checkbox" name="wordplurk_Plurk2tw_en" value="1" <?php checked(true, get_option('wordplurk_Plurk2tw_en','1'));?>>
	<br />
	<?php _e('This select will show the Plurk Responses after the post', 'wordplurk-improve');?></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_cmrt"><?php _e('Plurk Responses cache time', 'wordplurk-improve'); ?></label></th>
	<td><select type="text" name="wordplurk_cmrt">
	<?php
	$cmrt=array(
		'10'	=> '10 min',
		'20'	=> '20 min',
		'30'	=> '30 min',
		'60'	=> '1 hr',
		'120' => '2 hr',
		'360' => '6 hr',
		'720' => '12hr',
		'1440'=> '1 day',
	);
	foreach($cmrt as $key => $val){
		echo "\t\t<option value=\"$key\"";
		echo (get_option('wordplurk_cmrt','10')==$key)?' selected="selected"':'';
		echo ">".$val."</option>\n";
	}
	unset($cmrt);
	?>
	</select>
	<br />
	<?php _e('Because of API calls limit is 50.000 calls pr. day, Plurk Responses cache time have to according to site traffic to make the best setting.', 'wordplurk-improve');?><br />
	</td>
	</tr>
	</table>
	<input type="hidden" name="wordplurk_login" value="uncheck" />
	<p class="submit"><input type="submit" name="submit" value="<?php _e('Save Changes', 'wordplurk-improve') ?>" /></p>
	</form>
	</div>
	<?php
else:
	echo '<div class="error fade" style="padding: 5px;">' . sprintf(__('There are something wrong with the Curl test at your server, please check it was install and enabled, or contact your server admin', 'wordplurk-improve')) . '</div>';
endif;
	
}


function wordplurk_notice()
{
	if (!get_option('wordplurk_username') || !get_option('wordplurk_password') || !get_option('wordplurk_apikey') || !get_option('wordplurk_qualifier') || !get_option('wordplurk_language_set') || !get_option('wordplurk_template'))
	{
		echo '<div class="updated fade" style="padding: 5px;">' . sprintf(__('Please enter your Plurk username and password on the <a href="%1$s" title="WordPlurk Settings">WordPlurk Settings page</a>.', 'wordplurk-improve'), "options-general.php?page=".basename(WPLURK_DIR)."/wordplurk-improve.php") . '</div>';
	}elseif(!get_option('wordplurk_login')){
		echo '<div class="updated fade" style="padding: 5px;">' . sprintf(__('WordPlurk-improve was detected update. Please go on the <a href="%1$s" title="WordPlurk Settings">WordPlurk Settings page</a> and click \'Save Changes\' to finish update', 'wordplurk-improve'), "options-general.php?page=".basename(WPLURK_DIR)."/wordplurk-improve.php") . '</div>';
	}
}
?>
