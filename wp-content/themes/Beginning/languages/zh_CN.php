<?php
/**
 * Gravatar 头像使用中国服务器
 */
function Bing_gravatar_cn( $url ){
	$gravatar_url = array(
		'0.gravatar.com',
		'1.gravatar.com',
		'2.gravatar.com'
	);
	return str_replace( $gravatar_url, 'cn.gravatar.com', $url );
}
add_filter( 'get_avatar_url', 'Bing_gravatar_cn', 4 );

/**
 * 禁止半角符号自动转换
 */
add_filter( 'run_wptexturize', '__return_false', 12 );

/**
 * 替换 Google API 为 360 CDN
 */
function Bing_google_apis_replace_useso( $src ){
	$google = array(
		'https://fonts.googleapis.com/',
		'https://ajax.googleapis.com/',
		'//fonts.googleapis.com/',
		'//ajax.googleapis.com/'
	);
	$useso = array(
		'http://fonts.useso.com/',
		'http://ajax.useso.com/',
		'//fonts.useso.com/',
		'//ajax.useso.com/'
	);
	return str_replace( $google, $useso, $src );
}
add_filter( 'style_loader_src', 'Bing_google_apis_replace_useso', 16 );
add_filter( 'script_loader_src', 'Bing_google_apis_replace_useso', 16 );

/**
 * WordPress Emoji 表情无法使用的问题
 * Emoji 使用 MaxCDN。
 *
 * @link http://www.endskin.com/emoji-error/
 */
function Bing_emoji_url_maxcdn(){
	return set_url_scheme( '//twemoji.maxcdn.com/72x72/' );
}
add_filter( 'emoji_url', 'Bing_emoji_url_maxcdn', 8 );

/**
 * 删除 MaxCDN 消失的表情
 *
 * 因为 MaxCDN 莫名其妙的删除了两个表情，导致出现图片 404 的情况，所以暂时删除了这两个表情。
 */
function Bing_remove_die_smileys(){
	global $wpsmiliestrans;

	$remove_smileys = array(
		"\xf0\x9f\x99\x82",
		"\xf0\x9f\x99\x81"
	);

	foreach( $wpsmiliestrans as $key => $smiley )
		if( in_array( $smiley, $remove_smileys ) )
			unset( $wpsmiliestrans[$key] );
}
add_action( 'init', 'Bing_remove_die_smileys', 8 );

/**
 * 双核浏览器使用 Webkit 内核
 */
function Bing_browser_webkit(){
	echo '<meta name="renderer" content="webkit">';
}
add_action( 'wp_head', 'Bing_browser_webkit', 4 );

/**
 * 禁止百度转码网页
 */
function Bing_disable_baidu_transform(){
	$disables = array( 'transform', 'siteapp' );
	foreach( $disables as $disable ) echo '<meta http-equiv="Cache-Control" content="no-' . $disable . '" />';
}
add_action( 'wp_head', 'Bing_disable_baidu_transform', 4 );

//End of page.
