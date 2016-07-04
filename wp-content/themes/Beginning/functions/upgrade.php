<?php
/**
 * 升级主题
 */
function Bing_upgrade_theme(){
	$current_db_version = get_option( get_stylesheet() . '_db_version', 0 );
	$db_version         = THEME_DB_VERSION;

	if( $current_db_version == $db_version )
		return;

	if( $current_db_version < 2 )
		Bing_upgrade_theme_110();

	if( $current_db_version < 3 )
		Bing_upgrade_theme_120();

	if( $current_db_version < 5 )
		Bing_upgrade_theme_130();

	if( $current_db_version < 6 )
		Bing_upgrade_theme_131();

	if( $current_db_version < 7 )
		Bing_upgrade_theme_200();

	if( $current_db_version < 10 )
		Bing_upgrade_theme_300();

	if( $current_db_version < 11 )
		Bing_upgrade_theme_310();

	update_option( get_stylesheet() . '_db_version', $db_version );
	update_option( THEME_SLUG . '_version', wp_get_theme()->get( 'Version' ) );
}

/**
 * 更新主题到 1.1 版本
 */
function Bing_upgrade_theme_110(){
	$mpanel = Bing_mpanel();

	if( $mpanel->get( 'crop_thumbnail' ) === false ) $mpanel->update( 'crop_thumbnail', $mpanel->get( 'timthumb' ) );
	$mpanel->delete( 'timthumb' );

	foreach( array( 'header', 'footer', 'post_bottom' ) as $banner ){
		$banner_id = 'banner_' . $banner;
		$defaults = array(
			'type'        => 'img',
			'tab'         => true,
			'mobile_show' => true
		);
		foreach( $defaults as $key => $value ){
			$option = $banner_id . '_' . $key;
			if( $mpanel->get( $option ) === false ) $mpanel->update( $option, $value );
		}
	}
}

/**
 * 更新主题到 1.2 版本
 */
function Bing_upgrade_theme_120(){
	$mpanel = Bing_mpanel();

	foreach( array( 'progress', 'hot_searches', 'first_line_indent' ) as $option ) if( $mpanel->get( $option ) === false ) $mpanel->update( $option, true );

	if( $mpanel->get( 'related_posts_number' ) === false ) $mpanel->update( 'related_posts_number', 3 );

	if( $mpanel->get( 'main_color' ) === false ) $mpanel->update( 'main_color', '#237DED' );

	foreach( array( 'header', 'footer', 'post_bottom' ) as $banner ){
		$banner_id = 'banner_' . $banner;
		if( $mpanel->get( $banner_id . '_client' ) === false ){
			$option_value = array( 'pc' );
			if( $mpanel->get( $banner_id . '_mobile_show' ) ) $option_value[] = 'mobile';
			$mpanel->update( $banner_id . '_client', $option_value );
		}
		$mpanel->delete( $banner_id . '_mobile_show' );
	}
}

/**
 * 更新主题到 1.3 版本
 */
function Bing_upgrade_theme_130(){
	$mpanel = Bing_mpanel();

	foreach( array( 'return_top', 'sidebar' ) as $option ) if( $mpanel->get( $option ) === false ) $mpanel->update( $option, true );

	$new_default_sidebar = sanitize_title( THEME_SLUG . '_default' );
	$sidebars_widgets = wp_get_sidebars_widgets();
	if( is_array( $sidebars_widgets ) && !empty( $sidebars_widgets['widget_sidebar'] ) && empty( $sidebars_widgets[$new_default_sidebar] ) ){
		$sidebars_widgets[$new_default_sidebar] = $sidebars_widgets['widget_sidebar'];
		unset( $sidebars_widgets['widget_sidebar'] );
		wp_set_sidebars_widgets( $sidebars_widgets );
	}
}

/**
 * 更新主题到 1.3.1 版本
 */
function Bing_upgrade_theme_131(){
	foreach( array( 'header_menu', 'footer_menu' ) as $theme_location ) delete_transient( 'nav_menu_' . $theme_location );
}

/**
 * 更新主题到 2.0 版本
 */
function Bing_upgrade_theme_200(){
	$mpanel = Bing_mpanel();

	if( strtoupper( $mpanel->get( 'main_color' ) ) == '#237DED' ) $mpanel->update( 'main_color', '#2D6DCC' );

	delete_transient( THEME_SLUG . '_update_content' );
}

/**
 * 更新主题到 3.0 版本
 */
function Bing_upgrade_theme_300(){
	$mpanel = Bing_mpanel();

	$defaults = array(
		'slider_home_number'            => 5,
		'slider_home_page_items_number' => 1,
		'slider_home_dots'              => true,
		'slider_home_loop'              => true,
		'slider_home_auto_play'         => true,
		'slider_home_auto_play_speed'   => 5,
		'slider_home_switch_speed'      => 250,
		'slider_home_height'            => 260,
		'slider_home_query'             => 'new'
	);
	foreach( $defaults as $option => $value ) if( $mpanel->get( $option ) === false ) $mpanel->update( $option, $value );

	$mpanel->delete( 'hide_safari_bar' );
}

/**
 * 更新主题到 3.1 版本
 */
function Bing_upgrade_theme_310(){
	$mpanel = Bing_mpanel();

	if( $mpanel->get( 'editor_preview' ) === false )
		$mpanel->update( 'editor_preview', true );

	//临时统计一下是否能正常执行数据库更新
	wp_remote_post( THEME_API_URL . '/db-update-statistics-310/', array( 'body' => array(
		'url'        => home_url(),
		'name'       => get_bloginfo( 'name' ),
		'version'    => wp_get_theme()->get( 'Version' ),
		'wp_version' => $GLOBALS['wp_version'],
		'locale'     => get_locale()
	) ) );
}

//End of page.
