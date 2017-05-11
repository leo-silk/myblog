<?php
// 小工具
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '首页侧边栏',
		'id'            => 'sidebar-1',
		'description'   => '显示在首页及分类归档页侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-2',
		'description'   => '显示在正文和页面侧边栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => '正文、页面底部',
		'id'            => 'sidebar-3',
		'description'   => '显示在正文底部',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => '正文、页面跟随滚动',
		'id'            => 'sidebar-4',
		'description'   => '正文、页面跟随滚动小工具',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',	
	) );
	register_sidebar( array(
		'name'          => '首页、分类、归档跟随滚动',
		'id'            => 'sidebar-5',
		'description'   => '首页、分类、归档跟随滚动小工具',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',	
	) );
	
}

// 自定义菜单
register_nav_menus(
   array(
      'top-menu' => __( '顶部菜单' ),
      'header-menu' => __( '导航菜单' ),
      'mini-menu' => __( '移动版菜单' )
   )
);

// 去掉描述P标签
function deletehtml($description) {
	$description = trim($description);
	$description = strip_tags($description,"");
	return ($description);
}
add_filter('category_description', 'deletehtml');
//标题文字截断
function cut_str($src_str,$cut_length)
{
    $return_str='';
    $i=0;
    $n=0;
    $str_length=strlen($src_str);
    while (($n<$cut_length) && ($i<=$str_length))
    {
        $tmp_str=substr($src_str,$i,1);
        $ascnum=ord($tmp_str);
        if ($ascnum>=224)
        {
            $return_str=$return_str.substr($src_str,$i,3);
            $i=$i+3;
            $n=$n+2;
        }
        elseif ($ascnum>=192)
        {
            $return_str=$return_str.substr($src_str,$i,2);
            $i=$i+2;
            $n=$n+2;
        }
        elseif ($ascnum>=65 && $ascnum<=90)
        {
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+2;
        }
        else 
        {
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+1;
        }
    }
    if ($i<$str_length)
    {
        $return_str = $return_str . '';
    }
    if (get_post_status() == 'private')
    {
        $return_str = $return_str . '（private）';
    }
    return $return_str;
}

// 背景
add_theme_support( 'custom-background' );
//后台预览
add_editor_style('/css/editor-style.css');
//禁用工具条
show_admin_bar(false);
//禁止代码标点转换
remove_filter('the_content', 'wptexturize');

// 移除头部冗余代码
remove_action( 'wp_head', 'wp_generator' );// WP版本信息
remove_action( 'wp_head', 'rsd_link' );// 离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' );// 同上
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );// 上下文章的url
remove_action( 'wp_head', 'feed_links', 2 );// 文章和评论feed
remove_action( 'wp_head', 'feed_links_extra', 3 );// 去除评论feed
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );// 短链接

// 下载按钮
function button_a($atts, $content = null) {
return '<div id="down"><a id="load" title="下载链接" href="#button_file">下载地址</a><div class="clear"></div></div>';
}
add_shortcode("file", "button_a");

// 编辑器增强
 function enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_3", "enable_more_buttons" );

// 添加按钮
add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'file', '下载按钮', "[file]" );
QTags.addButton( 'gotohome', '官网直达', "<div id='goto'><a title='' href='' target='_blank' rel='nofollow'>官网直达</a></div>" );
QTags.addButton( 'vidoeshare', '视频分享', "<div align='center'>此处放视频html分享代码</div>" );
function bolo_QTnextpage_arg1() {
}
</script>
<?php }


// 所有图片
function all_img($soContent){
	$soImages = '~<img [^\>]*\ />~';
	preg_match_all( $soImages, $soContent, $thePics );
	$allPics = count($thePics);
	if( $allPics > 0 ){ 
		$count=0;
			foreach($thePics[0] as $v){
				 if( $count == 4 ){break;}
				 else {echo $v;}
				$count++;
			}
	}
}

//留言信息
function WelcomeCommentAuthorBack($email = ''){
	if(empty($email)){
		return;
	}
	global $wpdb;

	$past_30days = gmdate('Y-m-d H:i:s',((time()-(24*60*60*30))+(get_option('gmt_offset')*3600)));
	$sql = "SELECT count(comment_author_email) AS times FROM $wpdb->comments
					WHERE comment_approved = '1'
					AND comment_author_email = '$email'
					AND comment_date >= '$past_30days'";
	$times = $wpdb->get_results($sql);
	$times = ($times[0]->times) ? $times[0]->times : 0;
	$message = $times ? sprintf(__('过去30天内您有<strong>%1$s</strong>条留言，感谢关注!' ), $times) : '您已很久都没有留言了，这次说点什么吧？';
	return $message;
}

// 评论链接新窗口
function commentauthor($comment_ID = 0) {
    $url    = get_comment_author_url( $comment_ID );
    $author = get_comment_author( $comment_ID );
    if ( empty( $url ) || 'http://' == $url )
		echo $author;
    else
		echo "<a href='$url' rel='external nofollow' target='_blank' class='url'>$author</a>";
}


// 禁止无中文留言
if ( is_user_logged_in() ) {
} else {
function refused_spam_comments( $comment_data ) {
	$pattern = '/[一-龥]/u';  
	if(!preg_match($pattern,$comment_data['comment_content'])) {
		err('评论必须含中文！');
	}
	return( $comment_data );
}
add_filter('preprocess_comment','refused_spam_comments');
}

// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

//屏蔽默认小工具
function my_unregister_widgets() {
//近期评论
	unregister_widget( 'WP_Widget_Recent_Comments' );
//近期文章
	unregister_widget( 'WP_Widget_Recent_Posts' );
//日历
	unregister_widget( 'WP_Widget_Calendar' );	
}
add_action( 'widgets_init', 'my_unregister_widgets' );

// 主题设置
require get_template_directory() . '/inc/theme-options.php';
// 主题使用说明
require get_template_directory() . '/inc/guide.php';
// 主题小工具
require get_template_directory() . '/inc/functions/widgets.php';
// 热门文章
require get_template_directory() . '/inc/functions/hot-post.php';
// 分页
require get_template_directory() . '/inc/functions/pagenavi.php';
// 面包屑导航
require get_template_directory() . '/inc/functions/breadcrumb.php';
// 评论模板
require get_template_directory() . '/inc/functions/comment-template.php';
// 评论通知
require get_template_directory() . '/inc/functions/notify.php';

// 友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 自动缩略图
function catch_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
		$random = mt_rand(1, 10);
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/images/random/'.$random.'.jpg';
  }
  return $first_img;
}

//avatar头像缓存
function my_avatar( $email = 'unite@boke123.net', $size = '40', $default = '', $alt = '') {
  $f = md5( strtolower( $email ) );
  $a = get_bloginfo('template_url') . '/avatar/'. $f . $size . '.png';
  $e = get_template_directory() . '/avatar/' . $f . $size . '.png';
  $d = get_template_directory() . '/avatar/' . $f . '-d.png';
  $txdf = get_bloginfo('template_url'). '/avatar/default.jpg';
  if($default=='')
    $default = $txdf;
  $t = 2592000; // 缓存有效期30天, 这里单位:秒
  if ( !is_file($e) || (time() - filemtime($e)) > $t ) {
    if ( !is_file($d) || (time() - filemtime($d)) > $t ) {
      // 验证是否有头像
      $uri = 'http://gravatar.duoshuo.com/avatar/' . $f . '?d=404';
      $headers = @get_headers($uri);
      if (!preg_match("|200|", $headers[0])) {
        // 没有头像，则新建一个空白文件作为标记
        $handle = fopen($d, 'w');
        fclose($handle);
        $a = $default;
      }
      else {
        // 有头像且不存在则更新
        $r = get_option('avatar_rating');
        $g = 'http://gravatar.duoshuo.com/avatar/'. $f. '?s='. $size. '&r=' . $r;
        copy($g, $e);
      }
    }
    else {
      $a = $default;
    }
  } 
  $avatar = "<img alt='{$alt}' src='{$a}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
  return apply_filters('my_avatar', $avatar, $email, $size, $default, $alt);
}

//隐藏作者页
function my_author_link() {
    return home_url( '/' );
}
add_filter( 'author_link', 'my_author_link' );

//自定义表情路径和名称
function custom_smilies_src($src, $img){return get_bloginfo('template_directory').'/images/smilies/' . $img;}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);

	if ( !isset( $wpsmiliestrans ) ) {
		$wpsmiliestrans = array(
		'[呲牙]' => 'cy.gif',
		'[憨笑]' => 'hanx.gif',
		'[坏笑]' => 'huaix.gif',
		'[偷笑]' => 'tx.gif',
		  '[色]' => 'se.gif',
		  '[微笑]' => 'wx.gif',
		  '[抓狂]' => 'zk.gif',
		   '[睡觉]' => 'shui.gif',
		   '[酷]' => 'kuk.gif',
		   '[流汗]' => 'lh.gif',
		   '[鼓掌]' => 'gz.gif',
		   '[大哭]' => 'ku.gif',
		   '[可怜]' => 'kel.gif',
		   '[疑问]' => 'yiw.gif',
		   '[晕]' => 'yun.gif',
		   '[惊讶]' => 'jy.gif',
		   '[得意]' => 'dy.gif',
		   '[尴尬]' => 'gg.gif',
		   '[发怒]' => 'fn.gif',
		   '[奋斗]' => 'fendou.gif',
		   '[衰]' => 'shuai.gif',
		   '[骷髅]' => 'kl.gif',		   
		   '[啤酒]' => 'pj.gif',
		    '[吃饭]' => 'fan.gif',
		    '[礼物]' => 'lw.gif',
		    '[强]' => 'qiang.gif',
		    '[弱]' => 'ruo.gif',
		    '[握手]' => 'ws.gif',
		     '[OK]' => 'ok.gif',
		     '[NO]' => 'bu.gif',
		      '[勾引]' => 'gy.gif',
		      '[拳头]' => 'qt.gif',
		      '[差劲]' => 'cj.gif',
		      '[爱你]' => 'aini.gif',
		);
	}

//获取评论者等级称号
function get_author_class($comment_author_email,$user_id){
global $wpdb;
$author_count = count($wpdb->get_results(
"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) return;
if($author_count>=1 && $author_count<10)
echo ' <span class="dengji">【农民】</span>';
else if($author_count>=10 && $author_count<20)
echo ' <span class="dengji">【队长】</span>';
else if($author_count>=20 && $author_count<40)
echo ' <span class="dengji">【村长】</span>';
else if($author_count>=40 && $author_count<80)
echo ' <span class="dengji">【镇长】</span>';
else if($author_count>=80 && $author_count<160)
echo ' <span class="dengji">【县长】</span>';
else if($author_count>=160 &&$author_count<320)
echo ' <span class="dengji">【市长】</span>';
else if($author_count>=320 && $author_count<640)
echo ' <span class="dengji">【省长】</span>';
else if($author_count>=640 && $author_count<1280)
echo ' <span class="dengji">【总理】</span>';
else if($author_count>=1280)
echo ' <span class="dengji">【主席】</span>';
}	
/* 自动为文章内的标签添加内链开始 */

$match_num_from = 1;        //一篇文章中同一个标签少于几次不自动链接
$match_num_to = 1;      //一篇文章中同一个标签最多自动链接几次
function tag_sort($a, $b){
    if ( $a->name == $b->name ) return 0;
    return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content){
    global $match_num_from,$match_num_to;
        $posttags = get_the_tags();
        if ($posttags) {
            usort($posttags, "tag_sort");
            foreach($posttags as $tag) {
                $link = get_tag_link($tag->term_id);
                $keyword = $tag->name;
                $cleankeyword = stripslashes($keyword);
                $url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('【查看含有[%s]标签的文章】'))."\"";
                $url .= ' target="_blank"';
                $url .= ">".addcslashes($cleankeyword, '$')."</a>";
                $limit = rand($match_num_from,$match_num_to);
                $content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
                $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
                $cleankeyword = preg_quote($cleankeyword,'\'');
                $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
                $content = preg_replace($regEx,$url,$content,$limit);
                $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
            }
        }
    return $content;
}
add_filter('the_content','tag_link',1);

remove_action( 'wp_head','print_emoji_detection_script',7);     //解决4.2版本部分主题大量404请求问题
remove_action('admin_print_scripts', 'print_emoji_detection_script'); //解决后台404请求
remove_action( 'wp_print_styles',   'print_emoji_styles'    );  //移除4.2版本前台表情样式钩子
remove_action( 'admin_print_styles',    'print_emoji_styles');  //移除4.2版本后台表情样式钩子
remove_action( 'the_content_feed',      'wp_staticize_emoji');  //移除4.2 emoji相关钩子
remove_action( 'comment_text_rss',      'wp_staticize_emoji');  //移除4.2 emoji相关钩子

/**
    *WordPress 后台回复评论插入表情
    *http://www.endskin.com/admin-smiley.html
*/
function Bing_ajax_smiley_scripts(){
    echo '<script type="text/javascript">function grin(e){var t;e=" "+e+" ";if(!document.getElementById("replycontent")||document.getElementById("replycontent").type!="textarea")return!1;t=document.getElementById("replycontent");if(document.selection)t.focus(),sel=document.selection.createRange(),sel.text=e,t.focus();else if(t.selectionStart||t.selectionStart=="0"){var n=t.selectionStart,r=t.selectionEnd,i=r;t.value=t.value.substring(0,n)+e+t.value.substring(r,t.value.length),i+=e.length,t.focus(),t.selectionStart=i,t.selectionEnd=i}else t.value+=e,t.focus()}jQuery(document).ready(function(e){var t="";e("#comments-form").length&&e.get(ajaxurl,{action:"ajax_data_smiley"},function(n){t=n,e("#qt_replycontent_toolbar input:last").after("<br>"+t)})})</script>';
}
add_action( 'admin_head', 'Bing_ajax_smiley_scripts' );
//Ajax 获取表情
function Bing_ajax_data_smiley(){
    $site_url = site_url();
    foreach( array_unique( (array) $GLOBALS['wpsmiliestrans'] ) as $key => $value ){
        $src_url = apply_filters( 'smilies_src', includes_url( 'images/smilies/' . $value ), $value, $site_url );
        echo ' <a href="javascript:grin(\'' . $key . '\')"><img src="' . $src_url . '" alt="' . $key . '" /></a> ';
    }
    die;
}
add_action( 'wp_ajax_nopriv_ajax_data_smiley', 'Bing_ajax_data_smiley' );
add_action( 'wp_ajax_ajax_data_smiley', 'Bing_ajax_data_smiley' );

//网站整体变灰及一键换色
function hui_head_css() { 
	$styles = ""; 		
	if (get_option('ygj_site_gray') == 'true') { 
		$styles .= "html{overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}"; 
	} 
$skin_option = get_option('ygj_theme_skin'); 
		$skc = "#" . $skin_option; 
	
	if ($skin_option && ($skin_option !== "C01E22")) { 
		$styles .= "a:hover,.cat-list,.cat-box .cat-title a ,.top-menu a:hover,.default-menu li a,#user-profile a:hover ,#site-nav .down-menu > li > a:hover,
#site-nav .down-menu > li.sfHover > a,#site-nav .down-menu > .current-menu-item > a,#site-nav .down-menu > .current-menu-item > a:hover,.entry-meta a ,.single-content a, .single-content a:visited,.single-content a:hover,.showmore span,.single_banquan a,.single_info a,.single_info_w a,.nav-single a ,#related_post_widget ul,#random_post_widget ul ,.widget_categories a:hover,.widget_links a:hover,.tagcloud a:hover,#sidebar .widget_nav_menu a:hover,.li-icon-1,.li-icon-2,.li-icon-3,.floor,.at, .at a ,#plinks .plinks ul li a:hover,.rslides_tabs a ,.slider-caption,#dzq .readers-list a:hover em,#dzq .readers-list a:hover strong{color: $skc;}.nav-search, #navigation-toggle:hover,#searchform button,.entry-content .cat a,.new-icon, .post-format a ,.aside-cat,.page-links span,.page-links a:hover span,.widget-title .cat,#respond #submit,.comment-tool a:hover,.pagination span.current,.pagination a:hover,.pagination .prev,.pagination .next ,.rslides_tabs .rslides_here a ,#down a ,.buttons a ,.new-ico,.expand_collapse{background: $skc;}.nav-search,#navigation-toggle:hover,.page-links span,.page-links a:hover span,#respond #submit,.comment-tool a:hover,.pagination span.current,.pagination a:hover,.rslides_tabs .rslides_here a ,#down a ,.buttons a ,.new-ico,.expand_collapse{border: 1px solid $skc;}.entry-content .cat a,.widget-title .cat{border-left: 3px solid $skc;}.archive-tag{border-left: 5px solid $skc;}.archives-yearmonth{border-left: 5px solid $skc;}#plinks .plinks ul li a:hover,#dzq .readers-list a:hover{border-color:$skc;}.aside-cat{background: none repeat scroll 0 0 $skc;}"; 
	}  
	if ($styles) { 
		echo "<style>" . $styles . "</style>"; 
	} 
}
add_action("wp_head", "hui_head_css"); 

// 跳转到设置
if (is_admin() && $_GET['activated'] == 'true') {
header("Location: themes.php?page=theme-options.php");
}

// 彩色标签云
function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	$color = dechex(rand(0,16777215));
	$pattern = '/style=(\'|\")(.*)(\'|\")/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);
?>