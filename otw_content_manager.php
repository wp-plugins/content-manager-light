<?php
/**
Plugin Name: Content Manager Light
Plugin URI: http://OTWthemes.com
Description:  Build your custom page layout and fill it with ready to use widets/content. Easy, no coding. 
Author: OTWthemes.com
Version: 1.6

Author URI: http://themeforest.net/user/OTWthemes
*/

load_plugin_textdomain('otw_lcm',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$wp_cm_tmc_items = array(
	'page'              => array( array(), __( 'Pages', 'otw_lcm' ) ),
	'post'              => array( array(), __( 'Posts', 'otw_lcm' ) )
);

$wp_cm_agm_items = array(
	'page'              => array( array(), __( 'Pages', 'otw_lcm' ) ),
	'post'              => array( array(), __( 'Posts', 'otw_lcm' ) )
);

$wp_cm_cs_items = array(
	'page'              => array( array(), __( 'Pages', 'otw_lcm' ) ),
	'post'              => array( array(), __( 'Posts', 'otw_lcm' ) )
);

$otw_lcm_skins = array(

);

$otw_lcm_plugin_url = plugins_url( substr( dirname( __FILE__ ), strlen( dirname( dirname( __FILE__ ) ) ) ) );
$otw_lcm_css_version = '1.1';

$otw_lcm_plugin_options = get_option( 'otw_cm_plugin_options' );

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_lcm_functions.php' );

$otw_lcm_skin = '';

$otw_lcm_skins_path = plugin_dir_path( __FILE__ ).'/skins/';

$otw_lcm_skins = otw_lcm_load_skins( $otw_lcm_skins_path );

if( isset( $otw_lcm_plugin_options['otw_cm_skin'] ) && array_key_exists( $otw_lcm_plugin_options['otw_cm_skin'], $otw_lcm_skins ) ){
	$otw_lcm_skin = $otw_lcm_plugin_options['otw_cm_skin'];
}

//otw components
$otw_lcm_grid_manager_component = false;
$otw_lcm_grid_manager_object = false;
$otw_lcm_shortcode_component = false;
$otw_lcm_form_component = false;
$otw_lcm_validator_component = false;

//load core component functions
@include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

//register grid manager component
otw_register_component( 'otw_grid_manager', dirname( __FILE__ ).'/include/otw_components/otw_grid_manager/', $otw_lcm_plugin_url.'/include/otw_components/otw_grid_manager/' );

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_lcm_plugin_url.'/include/otw_components/otw_form/' );

//register validator component
otw_register_component( 'otw_validator', dirname( __FILE__ ).'/include/otw_components/otw_validator/', $otw_lcm_plugin_url.'/include/otw_components/otw_validator/' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_lcm_plugin_url.'/include/otw_components/otw_shortcode/' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_lcm_init' );
?>