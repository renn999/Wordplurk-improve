<?php
function wordplurkcomment($content){
	$post_id=get_the_ID();
	$plurk_id=get_post_meta($post_id, 'plurk_id', true);
	if(get_option('wordplurk_Plurk2tw_en','1') > 0 && $plurk_id && is_single()):
		if(get_option('wordplurk_Plurk2tw_en','1') == 2):
			$plurk_code = '<iframe width=\'100%\' height=\'200px\' src="http://www.plurk.com/m/p/'.$plurk_id.'"></iframe>';
		else:
			$getdata = array(
				'api_key' => get_option('wordplurk_apikey'), 
				'plurk_id' => base_convert($plurk_id,36,10)
			);
			$results = plurk_update_status($getdata,'responses');
			$resp = json_decode($results,true);
			unset($plurk_code);
			$plurk_code  = <<<EOF
<style type="text/css">
div.wpc, div.wpc img, div.wpc div, div#plurk_box table, div#plurk_box tr, div#plurk_box td {
float:none;
margin: 0;
padding: 0;
border: 0;
outline: 0;
font-weight: normal;
font-style: normal;
font-size: 100%;
font-family: inherit;
vertical-align: sub;
line-height: 20px!important;
}
</style>
EOF;
//clear CSS
			$plurk_code .= <<<EOF
<style type="text/css">
div.wpc { background-color: #FFFFFF;border:1px solid #888888!important; font-size:14px!important; width: 100%;}
div.wpc div.wpc_head {padding: 5px!important;}
div#plurk_box_head{background-color: #8DB6D3; color:white; font-size:12px; padding:0px 10px;}
div#plurk_box_body{overflow-y:auto;height:200px;padding:5px;}
div#plurk_box_foot{background-color:#EEEEEE;text-align:right;margin:auto;padding:0px 5px;}
</style>
EOF;
			$rsp_count = count($resp['responses']);
			if($rsp_count > 0 ):
				$plurk_code .= <<<EOF
<script type="text/javascript">
var \$q = jQuery.noConflict();
\$q().ready(function(){
  \$q('span#plurk_box_open').hide();
  \$q('span#plurk_box_open').click(function(){
  	\$q('span#plurk_box_open').hide();
    \$q('span#plurk_box_close').show();
    \$q('div#plurk_box').slideDown();
  });
  \$q('span#plurk_box_close').click(function(){
  	\$q('span#plurk_box_close').hide();
    \$q('span#plurk_box_open').show();
    \$q('div#plurk_box').slideUp();
  });
});
</script>
EOF;
				$plurk_code .= <<<EOF
<style type="text/css">
span#plurk_box_close a:hover{cursor:pointer;}
span#plurk_box_open a:hover{cursor:pointer;}
a.name{color:#111;font-weight:bold;text-decoration:none;}
a.name:hover{text-decoration:underline;color:#111;}
.td_qual{white-space:nowrap!important;width:0;overflow:hidden;}
.td_qual{*width:1%;}
.td_cnt{padding:2px 5px 2px 0;width:100%;white-space:normal!important;}
.td_cnt{*width:99%;}
.qualifier{padding:0 3px 0 3px!important;color:white;margin:0 3px 0 4px!important;}
.q_is{background-color:#E57C43;}
.q_says{background-color:#E2560B;}
.q_feels,.q_needs{border-right:1px solid #304F09;border-bottom:1px solid #304F09;}
.q_needs{background-color:#7A9A37;}
.q_hopes{background-color:#e05be9;}
.q_feels{background-color:#2D83BE;}
.q_thinks{border-right:1px solid #254A64;border-bottom:1px solid #254A64;background-color:#689CC1;}
.q_wants{background-color:#8DB241;}
.q_wishes{background-color:#5BB017;}
.q_has{background-color:#777;}
.q_loves{border-right:1px solid #334E09;border-bottom:1px solid #334E09;background-color:#B20C0C;}
.q_hates{border-right:1px solid #444;border-bottom:1px solid #444;background-color:#111;}
.q_asks{border-right:1px solid #6A2C6F;border-bottom:1px solid #6A2C6F;background-color:#8361bc;}
.q_will{background-color:#B46DB9;}
.q_was{background-color:#525252;}
.q_had{background-color:#8C8C8C;}
.q_likes{background-color:#CB2728;}
.q_shares{background-color:#A74949;}
.q_gives{background-color:#620E0E;}
.q_wonders{background-color:#2e4e9e;}
.q_wants,.q_has,.q_wishes{border-right:1px solid #334C08;border-bottom:1px solid #334C08;}
.q_is,.q_says{border-right:1px solid #994215;border-bottom:1px solid #994215;}
.q_will,.q_asks,.q_hopes{border-right:1px solid #6A2C6F;border-bottom:1px solid #6A2C6F;}
.q_gives,.q_likes,.q_was,.q_wonders{border-right:1px solid #313131;border-bottom:1px solid #313131;}
.q_shares,.q_had{border-right:1px solid #454545;border-bottom:1px solid #454545;}
</style>
EOF;
				$plurk_code .= '<div class=\'wpc\'><div class="wpc_head">';
				$plurk_code .= sprintf(_n("this post has %d plurk response, ", "this post has %d plurk responses, ", $rsp_count, 'wordplurk-improve'), $rsp_count).sprintf(__("<a href=\"http://www.plurk.com/p/%s\">click here</a> to plurk page", 'wordplurk-improve'), $plurk_id).'<span id="plurk_box_open"><a>[＋]</a></span><span id="plurk_box_close"><a>[－]</a></span></div>';
				$plurk_code .= '<div id="plurk_box"><div id=plurk_box_head>最近的 plurk 回應：</div><div id="plurk_box_body">';
				foreach($resp['responses'] as $resps):
					if($resp['friends']["{$resps['user_id']}"]['has_profile_image'] == 1 && $resp['friends']["{$resps['user_id']}"]['avatar'] == 0):
						$userimg='http://avatars.plurk.com/'.$resps['user_id'].'-small.gif ';
					elseif($resp['friends']["{$resps['user_id']}"]['has_profile_image'] == 1 && $resp['friends']["{$resps['user_id']}"]['avatar'] != 0):
						$userimg='http://avatars.plurk.com/'.$resps['user_id'].'-small'.$resp['friends']["{$resps['user_id']}"]['avatar'].'.gif';
					else:
						$userimg='http://www.plurk.com/static/default_small.gif';
					endif;
					$plurk_code .= '<table><tr><td><a href="http://www.plurk.com/'.$resp['friends']["{$resps['user_id']}"]['nick_name'].'"><img src="'.$userimg.'" valign="middle" height="20px" width="20px"></a></td><td class="td_qual" valign="top"><a class="name" href="http://www.plurk.com/'.$resp['friends']["{$resps['user_id']}"]['nick_name'].'">'.$resp['friends']["{$resps['user_id']}"]['display_name'].'</a><span class="qualifier q_'.$resps['qualifier'].'">'.$resps['qualifier_translated'].'</span></td><td class="td_cnt" valign="top">'.$resps['content'].'</td></tr></table>';
				endforeach;
				$plurk_code .= '</div><div id="plurk_box_foot"><div>powered by <a href="http://wordpress.org/extend/plugins/wordplurk-improve/">wordplurk improve</a></div></div></div></div>';
			else:
				$plurk_code .= '<div class=\'wpc\'><div class="wpc_head">'.sprintf(__("This post doesn't have any plurk response,", 'wordplurk-improve')). sprintf(__("<a href=\"http://www.plurk.com/p/%s\">click here</a> to plurk page", 'wordplurk-improve'), $plurk_id).'</div></div>';
			endif;
		endif;
		return $content.$plurk_code;
	else:
		return $content;
	endif;
}

?>
