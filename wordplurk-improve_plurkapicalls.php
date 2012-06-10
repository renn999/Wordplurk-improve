<?php
function plurk_login()
{
	$login_data=array(
		'api_key' => get_option('wordplurk_apikey'), 
		'username' => get_option('wordplurk_username'),
		'password' => get_option('wordplurk_password'),
		'no_data' => 1
	);
	
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER , 1);
	curl_setopt($curl, CURLOPT_ENCODING , 'gzip'); 
	curl_setopt($curl, CURLOPT_URL, 'http://www.plurk.com/API/Users/login');
	curl_setopt($curl, CURLOPT_POST , true );
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($login_data));
	
	$login_report = curl_exec($curl);
	preg_match_all('|Set-Cookie: (.*);|U', $login_report, $results);
	preg_match('|expires=(.*);|U', $login_report, $exp_time);
	preg_match('|\{.*\}$|U', $login_report, $login_report_json);
	$cookies = implode(';', $results[1]);
	update_option('wordplurk_cookie_exp_time', strtotime($exp_time[1]));
	update_option('wordplurk_cookie', $cookies);
	curl_close($curl);
	unset($exp_time, $results, $login_report, $cookies, $login_data, $login_report);
	return $login_report_json[0];
}

function plurk_update_status($new_status, $post_state)
{
	
	if($_SERVER['REQUEST_TIME'] > get_option('wordplurk_cookie_exp_time', 0) || get_option('wordplurk_login') != 'true'){
		plurk_login();
	}
	$curl = curl_init();

	$cookies = get_option('wordplurk_cookie');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER , 1);
	curl_setopt($curl, CURLOPT_ENCODING , 'gzip');
	curl_setopt($curl, CURLOPT_COOKIE , $cookies);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	switch($post_state):
		case 'post':
			curl_setopt($curl, CURLOPT_URL, 'http://www.plurk.com/API/Timeline/plurkAdd');
			break;
		case 'edit':
			curl_setopt($curl, CURLOPT_URL, 'http://www.plurk.com/API/Timeline/plurkEdit');
			break;
		case 'trash':
			curl_setopt($curl, CURLOPT_URL, 'http://www.plurk.com/API/Timeline/plurkDelete');
			break;
		case 'responses':
			curl_setopt($curl, CURLOPT_URL, 'http://www.plurk.com/API/Responses/get');
	endswitch;
	
	curl_setopt($curl, CURLOPT_POST , true );
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($new_status));
	$plurk_post_info = curl_exec($curl);
	curl_close($curl);
	unset($cookies);
	return $plurk_post_info;

}
?>
