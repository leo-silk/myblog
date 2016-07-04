<?php
/**
 * 在线更新
 */
function Bing_theme_auto_update( $update ){
	static $update_data;
	if( !isset( $update_data ) ){
		$theme_version = wp_get_theme()->get( 'Version' );
		$delete_update_content = true;
		$options = array(
			'timeout' => defined( 'DOING_CRON' ) && DOING_CRON ? 30 : 5,
			'body'    => array(
				'url'        => home_url(),
				'name'       => get_bloginfo( 'name' ),
				'version'    => $theme_version,
				'db_version' => get_option( get_stylesheet() . '_db_version' ),
				'wp_version' => $GLOBALS['wp_version'],
				'locale'     => get_locale(),

				//统计一下大家是否有更改主题文件夹名字的习惯 ㄟ(≧◇≦)ㄏ
				'statistics_310_stylesheet' => get_stylesheet(),
				'statistics_310_template'   => get_template(),
				'statistics_310_slug'       => THEME_SLUG,

				'statistics_310_stylesheet_changed' => get_stylesheet() != THEME_SLUG,
				'statistics_310_template_changed'   => get_template()   != THEME_SLUG
			)
		);
		$response = wp_remote_post( THEME_API_URL . '/update-check/', $options );
		if( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) return $update;
		$update_data = false;
		$result = json_decode( wp_remote_retrieve_body( $response ) );
		if( empty( $result->version ) ) return $update;
		if( version_compare( $result->version, $theme_version, '>' ) ){
			$update_data = array(
				'theme'       => get_stylesheet(),
				'new_version' => $result->version,
				'url'         => isset( $result->url )     ? $result->url     : '',
				'package'     => isset( $result->package ) ? $result->package : ''
			);
			if( !empty( $result->update_content ) ){
				set_site_transient( THEME_SLUG . '_update_content', $result->update_content );
				$delete_update_content = false;
			}
		}
		if( $delete_update_content ) delete_site_transient( THEME_SLUG . '_update_content' );
	}
	if( $update_data ) $update->response[get_stylesheet()] = $update_data;
	return $update;
}
add_filter( 'pre_set_site_transient_update_themes', 'Bing_theme_auto_update' );

/**
 * 启用主题时强制检测更新
 */
function Bing_theme_activate_update_check(){
	$last_update = get_site_transient( 'update_themes' );
	if( isset( $last_update->last_checked ) ){
		unset( $last_update->last_checked );
		set_site_transient( 'update_themes', $last_update );
	}
	wp_update_themes();
}
add_action( 'after_switch_theme', 'Bing_theme_activate_update_check' );

/**
 * 保存主题版本
 */
function Bing_save_theme_version(){
	$version    = get_option( THEME_SLUG . '_version' );
	$db_version = get_option( get_stylesheet() . '_db_version', 0 );

	if( !$db_version ){
		if( $version ){
			//把 3.0 及之前版本存储的版本号，转换成新的数据库版本号
			$db_version = Bing_translate_theme_db_version( $version );
			add_option( get_stylesheet() . '_db_version', $db_version );
		}else{
			do_action( 'start_theme' );
		}
	}

	if( $db_version == THEME_DB_VERSION )
		return;

	Bing_upgrade_theme();

	if( $version )
		do_action( 'theme_update', $version, wp_get_theme()->get( 'Version' ) );
}
add_action( 'init', 'Bing_save_theme_version', 16 );

/**
 * 根据主题版本号计算对应的数据库版本号
 *
 * 只适用于计算主题 3.0 以及之前版本的数据库版本号，否则一律返回 3.0 主题的数据库版本号。
 */
function Bing_translate_theme_db_version( $version ){
	$old_versions = array(
		'1.0',
		'1.1',
		'1.2',
		'1.2.1',
		'1.3',
		'1.3.1',
		'2.0',
		'2.0.1',
		'2.1',
		'3.0'
	);

	$db_version = 1;
	foreach( $old_versions as $old_version ){
		if( version_compare( $version, $old_version, '<=' ) ){
			if( !in_array( $version, $old_versions ) )
				--$db_version;

			break;
		}

		++$db_version;
	}

	return $db_version;
}

/**
 * 主题更新统计
 */
function Bing_theme_update_statistics( $form, $to ){
	if( get_option( 'blog_public' ) != '0' ) wp_remote_post( THEME_API_URL . '/update/', array( 'body' => array(
		'url'        => home_url(),
		'name'       => get_bloginfo( 'name' ),
		'form'       => $form,
		'to'         => $to,
		'version'    => wp_get_theme()->get( 'Version' ),
		'wp_version' => $GLOBALS['wp_version'],
		'locale'     => get_locale()
	) ) );
}
add_action( 'theme_update', 'Bing_theme_update_statistics', 18, 2 );

/**
 * 获取新版本更新内容
 */
function Bing_update_content(){
	return get_site_transient( THEME_SLUG . '_update_content' );
}

/**
 * 主题更新时删除新版本更新内容
 */
function Bing_theme_update_clear_content(){
	delete_site_transient( THEME_SLUG . '_update_content' );
}
add_action( 'theme_update', 'Bing_theme_update_clear_content' );

//End of page.
