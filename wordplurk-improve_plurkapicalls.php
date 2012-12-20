<?php
function plurk_oauth($reoauth=false)
{
	$oauthObject = new OAuthSimple();
	$output = 'Authorizing...';
	$signatures = array( 'consumer_key'     => 'FC6urgTMstvz',
		                   'shared_secret'    => '5Ra7byBYx4AScsxpVWJXk8wcyNngMSDv');

	if (!isset($_GET['oauth_verifier']) || $reoauth ) {
		$url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$j = explode('?',$url,2);
		parse_str($j[1],$k);
		echo $output;
		$result = $oauthObject->sign(array(
		    'path'      =>'http://www.plurk.com/OAuth/request_token',
		    'parameters'=> array(
		        'oauth_callback'=> $j[0].'?page='.$k['page']
		    ),
		    'signatures'=> $signatures));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
		$r = curl_exec($ch);
		curl_close($ch);

		parse_str($r, $returned_items);
		$i = array(
			'request_token' => $returned_items['oauth_token'],
			'request_token_secret' => $returned_items['oauth_token_secret']
		);
		update_option('wordplurk_login',json_encode($i));

		$result = $oauthObject->sign(array(
		    'path'      =>' http://www.plurk.com/OAuth/authorize',
		    'parameters'=> array(
		        'oauth_token' => $i['request_token'],
		        'deviceid' => 'Wordpress'
		    ),
		    'signatures'=> $signatures));

		echo "<SCRIPT LANGUAGE=\"JavaScript\">window.location=\"$result[signed_url]\";</script>";
		unset($oauthObject);
		exit;
	} else {
		$i = json_decode(get_option('wordplurk_login', Null),true);
		$signatures['oauth_secret'] = $i['request_token_secret'];
		$signatures['oauth_token'] = $_GET['oauth_token'];
		
		$result = $oauthObject->sign(array(
		    'path'      => 'http://www.plurk.com/OAuth/access_token',
		    'parameters'=> array(
		        'oauth_verifier' => $_GET['oauth_verifier'],
		        'oauth_token'    => $_GET['oauth_token']),
		    'signatures'=> $signatures));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
		$r = curl_exec($ch);

		parse_str($r, $returned_items);        
		$access_token = $returned_items['oauth_token'];
		$access_token_secret = $returned_items['oauth_token_secret'];
		
		$signatures['oauth_token'] = $access_token;
		$signatures['oauth_secret'] = $access_token_secret;
		$signatures['success'] = 'yes';
		update_option('wordplurk_login',json_encode($signatures));
	}
}

function plurk_update_status( $new_status, $post_state )
{
	$baseurl = 'http://www.plurk.com/APP/';
	switch($post_state):
		case 'post':
			$request_url = $baseurl.'Timeline/plurkAdd';
			break;
		case 'edit':
			$request_url = $baseurl.'Timeline/plurkEdit';
			break;
		case 'trash':
			$request_url = $baseurl.'Timeline/plurkDelete';
			break;
		case 'responses':
			$request_url = $baseurl.'Responses/get';
			break;
		case 'check':
			$request_url = $baseurl.'checkToken';
			break;
		case 'curruser':
			$request_url = $baseurl.'Users/currUser';
	endswitch;
	$oauthObject = new OAuthSimple();
	$signatures = json_decode(get_option('wordplurk_login'),true);
	$result = $oauthObject->sign(array(
	    'path'      => $request_url,
	    'parameters'=> $new_status,
	    'signatures'=> $signatures));
	unset($oauthObject);
	return curl_file_get_contents($result['signed_url']);
}
?>
