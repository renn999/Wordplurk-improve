<?php
function wordplurk_options_subpanel()
{
if(function_exists('curl_init')):
	$i = json_decode(get_option('wordplurk_login'),true);
	if (!isset($i['success']) || isset($_POST['wordplurk_reoauth']))
		plurk_oauth(isset($_POST['wordplurk_reoauth']));
	unset($i);
	if(isset($_POST['submit_option'])):
		unset($_POST['submit_option']);
		foreach( $_POST as $key => $value ):
			update_option($key,$value);
		endforeach;
	endif;
	?>
	<div class="wrap">
	<h2>WordPlurk <?php _e('Settings', 'wordplurk-improve'); ?></h2>
	<p><?php _e('Please set your Wordplurk-improve below. All fields are required.', 'wordplurk-improve'); ?></p>
	<div style=''><?php
	_e("Oauthorize Details:", 'wordplurk-improve');
	?><pre><?php
		$i = json_decode(plurk_update_status(array(),'check'),True);
		if(isset($i['user_id'])):
			$j = json_decode(plurk_update_status(array(),'curruser'),true);
			_e('User:', 'wordplurk-improve');
			echo $j['nick_name']."\n";
			_e('Issue Time:','wordplurk-improve');
			echo $i['issued']."\n";
		else:
			_e('There is something wrong with aouthorize data, please reoauthorize.','wordplurk-improve');
		endif;
		unset($i,$j);
	?></pre>
	<form method="post" action="">
	<input type="submit" name="wordplurk_reoauth" value="<?php _e('ReOauthorize', 'wordplurk-improve') ?>" />
	</form>
	</div>
	<form method="post" action="">
	<table class="form-table">
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
		//'5' => 'bit.ly',
		//'6' => 'j.mp',
		'7' => '4fun.tw'
	);
	
	if(function_exists('wp_get_shortlink'))
		$shorturl_api['8'] = sprintf(__('Wordpress init shorturl function', 'wordplurk-improve'));
	
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
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="wordplurk_Plurk2tw_en"><?php _e('Post Plurk Responses', 'wordplurk-improve'); ?></label></th>
	<td>
	<select type="text" name="wordplurk_Plurk2tw_en">
	<?php
	$Plurk2tw_en=array(
		'0' => 'Disable',
		'1'	=> 'Default Type',
		'2' => 'iframe embed',
	);
	
	foreach($Plurk2tw_en as $key => $val){
		echo "\t\t<option value=\"$key\"";
		echo (get_option('wordplurk_Plurk2tw_en','1')==$key)?' selected="selected"':'';
		echo ">".$val."</option>\n";
	}
	unset($Plurk2tw_en);
	?>
	</select>
	<br />
	<?php _e('This select will show the Plurk Responses after the post', 'wordplurk-improve');?></td>
	</tr>
	</table>
	<p class="submit"><input type="submit" name='submit_option' value="<?php _e('Save Changes', 'wordplurk-improve') ?>" /></p>
	</form>
	</div>
	<?php
else:
	echo '<div class="error fade" style="padding: 5px;">' . sprintf(__('There are something wrong with the Curl test at your server, please check it was install and enabled, or contact your server admin', 'wordplurk-improve')) . '</div>';
endif;
	
}


function wordplurk_notice()
{
	$i = json_decode(get_option('wordplurk_login'),true);
	if (!isset($i['success']) && !isset($_GET['oauth_verifier'])){
		echo '<div class="updated fade" style="padding: 5px;">' . sprintf(__('Please authorize Wordplurk-improve on the <a href="%1$s" title="WordPlurk Settings">WordPlurk Settings page</a>.', 'wordplurk-improve'), "options-general.php?page=".basename(WPLURK_DIR)."/wordplurk-improve.php") . '</div>';
	}elseif (!get_option('wordplurk_qualifier') || !get_option('wordplurk_language_set') || !get_option('wordplurk_template')){
		echo '<div class="updated fade" style="padding: 5px;">' . sprintf(__('Please set your Wordplurk-improve Settings on the <a href="%1$s" title="WordPlurk Settings">WordPlurk Settings page</a>.', 'wordplurk-improve'), "options-general.php?page=".basename(WPLURK_DIR)."/wordplurk-improve.php") . '</div>';
	}
}
?>
