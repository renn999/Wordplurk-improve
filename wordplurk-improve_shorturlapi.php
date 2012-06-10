<?php
function short_googl($url){
	/*
	API Google URL Shortner
	http://marcusnunes.com/api-goo.gl.php
	*/
	 
	//$url = $_GET['url'];						//URL to short with goo.gl
	//if(!$url){$url = 'http://twitter.com/';}	//example
	 
	$curl_googl = curl_init(); 
	curl_setopt($curl_googl, CURLOPT_URL, 'http://goo.gl/api/url');   //goo.gl api url
	curl_setopt($curl_googl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($curl_googl, CURLOPT_POST, 1); 
	curl_setopt($curl_googl, CURLOPT_POSTFIELDS, 'user=toolbar@google.com&url='.urlencode($url).'&auth_token='.googlToken($url)); 
	 
	$saida = curl_exec($curl_googl); 
	curl_close($curl);
	 
	if($saida){
		$json = json_decode($saida);
		return ''.$json->short_url;    //result
	}
}
//goo.gl token
function googlToken($b){
	$i = tke($b);
	$i = $i >> 2 & 1073741823;
	$i = $i >> 4 & 67108800 | $i & 63;
	$i = $i >> 4 & 4193280 | $i & 1023;
	$i = $i >> 4 & 245760 | $i & 16383;
	$j = "7";
	$h = tkf($b);
	$k = ($i >> 2 & 15) << 4 | $h & 15;
	$k |= ($i >> 6 & 15) << 12 | ($h >> 8 & 15) << 8;
	$k |= ($i >> 10 & 15) << 20 | ($h >> 16 & 15) << 16;
	$k |= ($i >> 14 & 15) << 28 | ($h >> 24 & 15) << 24;
	$j .= tkd($k);
	return $j;
}
 
function tkc(){
	$l = 0;
	foreach (func_get_args() as $val) {
		$val &= 4294967295;
		$val += $val > 2147483647 ? -4294967296 : ($val < -2147483647 ? 4294967296 : 0);
		$l   += $val;
		$l   += $l > 2147483647 ? -4294967296 : ($l < -2147483647 ? 4294967296 : 0);
	}
	return $l;
}
 
function tkd($l){
	$l = $l > 0 ? $l : $l + 4294967296;
	$m = "$l";  //must be a string
	$o = 0;
	$n = false;
	for($p = strlen($m) - 1; $p >= 0; --$p){
		$q = $m[$p];
		if($n){
			$q *= 2;
			$o += floor($q / 10) + $q % 10;
		} else {
			$o += $q;
		}
		$n = !$n;
	}
	$m = $o % 10;
	$o = 0;
	if($m != 0){
		$o = 10 - $m;
		if(strlen($l) % 2 == 1){
			if ($o % 2 == 1){
				$o += 9;
			}
			$o /= 2;
		}
	}
	return "$o$l";
}
 
function tke($l){
	$m = 5381;
	for($o = 0; $o < strlen($l); $o++){
		$m = tkc($m << 5, $m, ord($l[$o]));
	}
	return $m;
}
 
function tkf($l){
	$m = 0;
	for($o = 0; $o < strlen($l); $o++){
		$m = tkc(ord($l[$o]), $m << 6, $m << 16, -$m);
	}
	return $m;
}

function curl_file_get_contents($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
?>
