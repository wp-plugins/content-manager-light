<?php
/**
 * Process otw cm actions
 *
 */
if( isset( $_POST['otw_lcm_action'] ) ){
	
	require_once( ABSPATH . WPINC . '/pluggable.php' );
	
	switch( $_POST['otw_lcm_action'] ){
		
		case 'manage_otw_lcm_skin':
				global $otw_lcm_skins, $otw_lcm_skins_path, $validate_messages;
				
				$validate_messages = array();
				
				$valid_page = true;
				
				if( !isset( $_POST['otw_skin_title'] ) || !strlen( trim( $_POST['otw_skin_title'] ) ) ){
					$valid_page = false;
					$validate_messages[] = __( 'Please type valid skin name', 'otw_lcm' );
				}elseif( !otw_lcm_valid_skin_name( $_POST['otw_skin_title'] ) ){
					$valid_page = false;
					$validate_messages[] = __( 'Please type valid skin name', 'otw_lcm' );
				}elseif( isset( $_POST['otw_skin_edit'] ) && !$_POST['otw_skin_edit'] && array_key_exists( $_POST['otw_skin_title'], $otw_lcm_skins ) ){
					$valid_page = false;
					$validate_messages[] = __( 'The skin with same name already exists', 'otw_lcm' );
				}
				
				if( $valid_page ){
					
					
					$fp = @fopen( $otw_lcm_skins_path.$_POST['otw_skin_title'].'.css', 'w' );
					
					if( $fp ){
						fwrite( $fp, otw_stripslashes( $_POST['otw_skin_content'] ) );
						fclose( $fp );
					}else{
						$valid_page = false;
						$validate_messages[] = __( 'Error, can not safe the skin file. Check folder perimitions.', 'otw_lcm' );
					}
					wp_redirect( 'admin.php?page=otw-lcm-skins&message=1' );
				}
			break;
		case 'otw_lcm_settings_action':
				
				global $wp_cm_tmc_items, $wp_cm_agm_items, $otw_lcm_skins, $wp_cm_cs_items;
				
				$options = array();
				
				$options['shortcode_editor_button_for'] = array();
				
				foreach( $wp_cm_tmc_items as $wp_item_type => $wpItem ){
					if( isset( $_POST['otw_cm_editor_shortcodes'] ) && is_array( $_POST['otw_cm_editor_shortcodes'] ) && isset( $_POST['otw_cm_editor_shortcodes'][ $wp_item_type ] ) ){
						$options['shortcode_editor_button_for'][ $wp_item_type ] = $_POST['otw_cm_editor_shortcodes'][ $wp_item_type ];
					}else{
						$options['shortcode_editor_button_for'][ $wp_item_type ] = 0;
					}
				}
				
				foreach( $wp_cm_agm_items as $wp_item_type => $wpItem ){
					if( isset( $_POST['otw_cm_grid'] ) && is_array( $_POST['otw_cm_grid'] ) && isset( $_POST['otw_cm_grid'][ $wp_item_type ] ) ){
						$options['grid_for'][ $wp_item_type ] = $_POST['otw_cm_grid'][ $wp_item_type ];
					}else{
						$options['grid_for'][ $wp_item_type ] = 0;
					}
				}
				
				foreach( $wp_cm_cs_items as $wp_item_type => $wpItem ){
					if( isset( $_POST['otw_cm_cs'] ) && is_array( $_POST['otw_cm_cs'] ) && isset( $_POST['otw_cm_cs'][ $wp_item_type ] ) ){
						$options['cs_for'][ $wp_item_type ] = $_POST['otw_cm_cs'][ $wp_item_type ];
					}else{
						$options['cs_for'][ $wp_item_type ] = 0;
					}
				}
				
				$options['otw_cm_skin'] = '';
				if( isset( $_POST['otw_cm_skin'] ) && array_key_exists( $_POST['otw_cm_skin'], $otw_lcm_skins ) ){
					$options['otw_cm_skin'] = $_POST['otw_cm_skin'];
				}
				
				$options['otw_cm_grid_previews'] = '';
				if( isset( $_POST['otw_cm_grid_previews'] ) ){
					$options['otw_cm_grid_previews'] = $_POST['otw_cm_grid_previews'];
				}
				
				update_option( 'otw_cm_plugin_options', $options );
				wp_redirect( admin_url( 'admin.php?page=otw-lcm-settings&message=1' ) );
			break;
	}
}
?>