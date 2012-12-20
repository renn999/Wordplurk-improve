=== WordPlurk improve ===
Contributors: renn999, bluefur, Speedboxer
Donate link: http://www.renn999.twbbs.org/wordplurk-improve
Tags: plurk
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 3.2

WordPlurk improve is Base on 'WordPlurk', and add more settings and functions.

== Description ==
Generates Plurk Updates when a new Post is Published, Useing Official Plurk api, and Setting improve. Orginal Home page <a href="http://blog.bluefur.com/wordplurk">http://blog.bluefur.com/wordplurk</a>
Post Plurk Responses can show after the post.

** Github page **
https://github.com/renn999/Wordplurk-improve

** Bug Report **
https://github.com/renn999/Wordplurk-improve/issues

If you want to report bug or add new language. Please open a new issue using github. :-)

== Installation ==

1. Download wordplurk improve.
2. Unzip and put 'wordplurk-improve' into `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Set your WordPlurk-improve. (Settings > WordPlurk)

== Configuration ==

The available options are located at Settings > WordPlurk.

== Known Issues ==

* User can design Post Plurk Responses CSS.
* Only one Plurk account is supported (not one account per author).
from http://plugins.svn.wordpress.org/wordplurk/trunk/readme.txt

== Frequently Asked Questions ==

= Q: Why is there a error report "Fatal error: Call to undefined function curl_init() in [path]/wordplurk-improve.php on line 20" =

A: It is means that your server not support php curl please contect your adminstrator or install it.

== Changelog ==

= 3.2 =
* (FIX) jquery loading function. (wordpress plugin admin email to me: "Repository Guideline Violation in your WordPress Plugin")
* (ADD) show Oauthorize Details at setting page.
* (FIX) "Fatal error: Allowed memory size...", when low memory at option submit will faild.

= 3.1 =
* (FIX) small bug fix. I forget this is php....(doh)

= 3.0 =
* (NEW) Add Romanian language. ( thanks Alexander Ovsov "pivnyukip<AT>gmail.com" )
* (FIX) Upgrade plurk API using version 2.0.
* (FIX) drop dummy setting.
* (FIX) iFrame embed add for choies.

= 2.2.1 =
* (FIX) The count of words might be wrong!

= 2.2 =
* (FIX) file_get_contents() function might occurred a Warning on some server, so I ues curl replace it.

= 2.1 =
* (NEW) New tag at Wordplurk Template '%%content%%'. It will capture front part of the article to plurk.(Thanks Lakatos for giving such ideal.)

= 2.0 =
* (NEW) CURL Pre-test.
* (FIX) short url api doen't match will return permalink.
* (FIX) responses count doesn't match.
* (FIX) some css in different browser.
* (NEW) Add shorturl service using wordpress 3.0 wp_get_shortlink function to generate short url. It means it can use other plugin has filter get_shortlink to generate short url.

= 1.10 =
* (FIX) This plugin might let jQuery Image Lazy Load WP jQuery Image Lazy Load inactive.
* (FIX) Post Plurk Responses styles make better.
* (FIX) Post Plurk Responses cache time setting field add.
* (FIX) Post Plurk Responses cache time problem re-fix.
* (FIX) Post show 'array' at top.

= 1.9 =
* (FIX) Some Post Plurk Responses CSS inheritance problem fix.
* (FIX) If plurk del, Post Plurk Responses will show error message.
* (FIX) Post Plurk Responses update time problem.

= 1.8 =
* (NEW) Add some shorturl service. bit.ly j.mp 4fun.tw...,etc. (bit.ly and j.mp need bit.ly api key)
* (REMOVE) the Plurk<sup>2</sup> Plug to append above the comment. Becouse it request speed is too slow.
* (ADD) Post Plurk Responses. This select will show the Plurk Responses after the post.

= 1.7 =
* (FIX) Not only Tinyurl for short url support, but also ppt.cc and goo.gl, bit.ly will be supprot latter.
* (NEW) before the publish, editor can decide the post plurk or not.

= 1.6 =
* (FIX) Chang some code that saving cookie in mysql, and the frequency of api calls won't so much.

= 1.5 =
* (FIX) When saving the wordplurk improve setting at "Wordpress mu", the Option page will return "Error! Options page not found"(thanks for Peter)

= 1.4 =
* (FIX) When POST move to Trash, the plurk will be remove, too. The post form trash to publish The plurk will be repost again,But be care of anti-flood-same-content,
* (FIX) When when Wordplurk-improve setting update will pre-check the username, password, api-key.
* (FIX) The Plurk<sup>2</sup> Plug conflict with DISQUS Commenting systems.(thanks for Cahya)

= 1.3 =
* (FIX) Fix del cookie.txt file problem.
* (NEw) Add the Plurk<sup>2</sup> Plug to append above the comment.

= 1.2 =
* (FIX) When the change is complete, the corresponding PLurk page will be updated by the way. But, it doesn't work on old verson of Wordplurk post page


= 1.1 =
* (NEW) multiple Language User interface support
* (NEW) Enable or disable tinnyurl support
* (FIX) some bugs

= 1.0.2.1 =
* (NEW) First Release Useing Plurk Official API
* (NEW) Add Some Settings in Settings Page
