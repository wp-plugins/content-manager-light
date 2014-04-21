<?php
class OTW_Shortcode extends OTW_Component{
	
	
	/**
	 *  List with all available shortcodes
	 */
	public $shortcodes = array();
	
	/**
	 *  List settings for all shortcodes
	 */
	public $shortcode_settings = array();
	
	/**
	 * Key of tinymce shortcodes button
	 */
	public $tinymce_button_key = 'otwshortcodebtn';
	
	/**
	 * Tinymce button active for
	 */
	
	public $editor_button_active_for = array(
		'page' => true,
		'post' => true
	);
	
	/**
	 *
	 */
	private $default_external_libs = array();
	
	/**
	 * construct
	 */
	public function __construct(){
		
	}
	
	public function apply_shortcode_settings(){
		
		foreach( $this->shortcodes as $shortcode_key => $shortcode_settings ){
			
			if( !is_array( $shortcode_settings['children'] ) || !count( $shortcode_settings['children'] ) ){
				$this->shortcodes[ $shortcode_key ]['object']->apply_settings();
			}
		}
	}
	
	public function include_shortcodes(){
		
		$this->add_default_external_lib( 'css', 'otw-shortcode-preview', $this->component_url.'css/otw_shortcode_preview.css', 'live_preview', 300 );
		$this->add_default_external_lib( 'css', 'otw-font-familyopen-sans-condensed-light', 'http://fonts.googleapis.com/css?family=Open+Sans+Condensed:light&v1', 'admin', 0 );
		
		
		include_once( $this->component_path.'shortcodes/otw_shortcodes.class.php' );
		
		foreach( $this->shortcodes as $shortcode_key => $shortcode_settings ){
		
			if( !is_array( $shortcode_settings['children'] ) || !count( $shortcode_settings['children'] ) ){
			
				if( !class_exists( 'OTW_ShortCode_'.$shortcode_key ) ){
					if( isset( $shortcode_settings['path'] ) ){
						include_once( $shortcode_settings['path'].'shortcodes/otw_shortcode_'.$shortcode_key.'.class.php' );
					}else{
						include_once( $this->component_path.'shortcodes/otw_shortcode_'.$shortcode_key.'.class.php' );
					}
				}
				$class_name = 'OTW_ShortCode_'.$shortcode_key;
				$this->shortcodes[ $shortcode_key ]['object'] = new $class_name;
				$this->shortcodes[ $shortcode_key ]['object']->labels = $this->labels;
				$this->shortcodes[ $shortcode_key ]['object']->mode = $this->mode;
				
				if( isset( $shortcode_settings['url'] ) ){
					$this->shortcodes[ $shortcode_key ]['object']->component_url = $shortcode_settings['url'];
				}else{
					$this->shortcodes[ $shortcode_key ]['object']->component_url = $this->component_url;
				}
				
				if( isset( $shortcode_settings['path'] ) ){
					$this->shortcodes[ $shortcode_key ]['object']->component_path = $shortcode_settings['path'];
				}else{
					$this->shortcodes[ $shortcode_key ]['object']->component_path = $this->component_path;
				}
				
				$this->shortcodes[ $shortcode_key ]['object']->external_libs = $this->default_external_libs;
				
				$this->shortcodes[ $shortcode_key ]['object']->register_external_libs();
			
			}
		}
		$this->add_registered_libs();
	}
	
	public function add_registered_libs(){
	
		foreach( $this->shortcodes as $shortcode_key => $shortcode_settings ){
			
			if( !is_array( $shortcode_settings['children'] ) || !count( $shortcode_settings['children'] ) ){
				foreach( $this->shortcodes[ $shortcode_key ]['object']->external_libs as $lib_array ){
					$this->add_external_lib( $lib_array['type'], $lib_array['name'], $lib_array['path'], $lib_array['int'], $lib_array['order'], $lib_array['deps'] );
				}
			}
		}
		
	}
	
	public function register_shortcodes(){
	
		if( count( $this->shortcodes ) ){
			uasort( $this->shortcodes, array( $this, 'sort_shortcodes' ) );
		}
		
		foreach( $this->shortcodes as $shortcode_key => $shortcode_data ){
			add_shortcode( 'otw_shortcode_'.$shortcode_key, array( &$this->shortcodes[ $shortcode_key ]['object'], 'display_shortcode' ) );
		}
	}
	
	/**
	 *  Init 
	 */
	public function init(){
		
		$this->include_shortcodes();
		
		$this->apply_shortcode_settings();
		
		$this->register_shortcodes();
		
		if( is_admin() ){
			wp_enqueue_script('otw_shortcode_admin', $this->component_url.'js/otw_shortcode_admin.js' , array( 'jquery' ), '1.1' );
			wp_enqueue_style( 'otw_shortcode_admin', $this->component_url.'css/otw_shortcode_admin.css', array( ), '1.1' );
			
			add_action( 'admin_footer', array( &$this, 'load_admin_js' ) );
			
			add_action( 'wp_ajax_otw_shortcode_editor_dialog', array( &$this, 'build_shortcode_editor_dialog' ) );
			add_action( 'wp_ajax_otw_shortcode_get_code', array( &$this, 'get_code' ) );
			add_action( 'wp_ajax_otw_shortcode_live_preview', array( &$this, 'live_preview' ) );
			add_action( 'wp_ajax_otw_shortcode_live_reload', array( &$this, 'live_reload' ) );
			add_action( 'wp_ajax_otw_shortcode_preview_shortcodes', array( &$this, 'preview_shortcodes' ) );
			
		}
		
		parent::init();
	}
	
	/**
	 *  Add admin js
	 *
	 */
	public function load_admin_js(){
	
		$js  = "<script type=\"text/javascript\">";
		$js .= "otw_shortcode_component = new otw_shortcode_object();";
		$js .= "otw_shortcode_component.shortcodes = ".json_encode( $this->shortcodes ).";";
		$js .= "otw_shortcode_component.labels = ".json_encode( $this->labels ).";";
		$js .= "</script>";
		
		echo $js;
	}
	
	/**
	 * Short code editor dialog interface
	 */
	public function build_shortcode_editor_dialog(){
		
		$shortcode = '';
		if( isset( $_GET['shortcode'] ) && array_key_exists( $_GET['shortcode'], $this->shortcodes ) ){
			
			$shortcode = $this->shortcodes[ $_GET['shortcode'] ];
			
			$content  = "\n<div style=\"min-height:100%; position:relative; background-color: #fff;\">";
			$content .= "\n<div class=\"clear\" id=\"otw-shortcode-editor-buttons\">
					<div class=\"alignleft\">
						<input type=\"button\" accesskey=\"C\" value=\"".$this->get_label('Cancel')."\" name=\"cancel\" class=\"button\" id=\"otw-shortcode-btn-cancel\">
					</div>
					<div class=\"alignright\">
						<input type=\"button\" accesskey=\"I\" value=\"".$this->get_label('Insert')."\" name=\"insert\" class=\"button-primary\" id=\"otw-shortcode-btn-insert\">
					</div>
					<div class=\"clear\"></div>
					</div>";
			$content .= "<table cellspacing=\"2\" cellpadding=\"0\" class=\"otw-shortcode-editor-body\">";
			$content .= "<tr>";
			$content .= "<td valign=\"top\"><h3 class=\"otw_editor_section_title\">".$this->get_label('Options')."</h3></td>";
			$content .= "<th class=\"otw_empty_head\">&nbsp;</th>";
			
			if( $this->shortcodes[ $_GET['shortcode'] ]['object']->has_custom_options ){
				$content .= "<td  valign=\"top\" rowspan=\"4\">";
			}else{
				$content .= "<td  valign=\"top\" rowspan=\"2\">";
			}
			
			if( $this->shortcodes[ $_GET['shortcode'] ]['object']->has_preview ){
			
				$content .= "<div class=\"otw-shortcode-editor-preview-container\">
								
								<div class=\"otw-shortcode-editor-preview-wrapper\">
								<h3>".$this->get_label('Preview')."</h3>
								<div class=\"otw-shortcode-editor-preview\">
								</div>
								</div>
						</div>";
			}else{
				$content .= "&nbsp;";
			}
			$content .= "\n</td>";
			$content .= "\n</tr>";
			$content .= "<tr>";
			$content .= "<td class=\"otw-shortcode-editor-fields\" valign=\"top\">";
			$content .= $this->shortcodes[ $_GET['shortcode'] ]['object']->build_shortcode_editor_options();
			$content .= "</td>";
			$content .= "<td>&nbsp;</td>";
			$content .= "</tr>";
			
			if( $this->shortcodes[ $_GET['shortcode'] ]['object']->has_custom_options ){
				$content .= "<tr>";
					$content .= "<th>".$this->get_label('Custom Options')."</th>";
					$content .= "<th class=\"otw_empty_head\">&nbsp;</th>";
				$content .= "</tr>";
				$content .= "<tr>";
					$content .= "<td class=\"otw-shortcode-editor-fields\" valign=\"top\">";
					$content .= $this->shortcodes[ $_GET['shortcode'] ]['object']->build_shortcode_editor_custom_options();
					$content .= "</td>";
					$content .= "<td>&nbsp;</td>";
				$content .= "</tr>";
			}
			$content .= "</table>";
			
			$content .= "\n<div class=\"clear\" id=\"otw-shortcode-editor-buttons-bottom\">
						<div class=\"alignleft\">
							<input type=\"button\" accesskey=\"C\" value=\"".$this->get_label('Cancel')."\" name=\"cancel\" class=\"button\" id=\"otw-shortcode-btn-cancel-bottom\">
						</div>
						<div class=\"alignright\">
							<input type=\"button\" accesskey=\"I\" value=\"".$this->get_label('Insert')."\" name=\"insert\" class=\"button-primary\" id=\"otw-shortcode-btn-insert-bottom\">
						</div>
						<div class=\"clear\"></div>
					</div>";
			$content .= "\n</div>";
			echo $content;
			die;
		}else{
			wp_die( $this->get_label('Invalid shortcode') );
		}
		
	}
	
	/** Shortcodes preview
	 *
	 */
	public function preview_shortcodes(){
	
		$result = array();
		if( isset( $_POST['shortcode'] ) )
		{
			$result['shortcodes'] = $_POST['shortcode'];
			foreach( $result['shortcodes'] as $shortcode_key => $shortcode )
			{
				$result['shortcodes'][ $shortcode_key ]['preview'] = '';
				if( isset( $this->shortcodes[ $shortcode['shortcode_type'] ] ) ){
					foreach( $this->shortcodes[ $shortcode['shortcode_type'] ]['object']->external_libs as $lib ){
						
						$register = false;
						switch( $lib['int'] ){
							
							case 'live_preview':
									$register = true;
								break;
							case 'all':
									$register = true;
								break;
						}
						if( $register ){
						
							switch( $lib['type'] ){
								
								case 'js':
										$result['shortcodes'][ $shortcode_key ]['preview'] .= '<script type="text/javascript" src="'.( esc_url( $lib['path'] ) ).'"></script>';
									break;
								case 'css':
										$result['shortcodes'][ $shortcode_key ]['preview'] .= '<link rel="stylesheet" type="text/css" href="'.( esc_url( $lib['path'] ) ).'" />';
									break;
							}
						}
					}
				}
				
				//$result['shortcodes'][ $shortcode_key ]['preview'] .= '<div style="text-align: center;">';
				$result['shortcodes'][ $shortcode_key ]['preview'] .= '<div class="otw_live_preview_wapper">';
				
				$result['shortcodes'][ $shortcode_key ]['preview'] .= do_shortcode( stripslashes( $shortcode['code'] ) );
				$result['shortcodes'][ $shortcode_key ]['preview'] .= '</div>';
			}
		}
		
		echo json_encode( $result );
		die;
	}
	/** Shortcode live reload
	 *
	 */
	public function live_reload(){
		
		if( isset( $_POST['shortcode'] ) ){
			
			foreach( $_POST['shortcode'] as $post_key => $post_value ){
				$_POST['shortcode_object']['otw-shortcode-element-'.$post_key] = $post_value;
			}
		}
		$this->build_shortcode_editor_dialog();
		die;
	}
	
	/** Shortcode live preview
	 *
	 */
	public function live_preview(){
		
		global $post;
		
		if( !$post && isset( $_POST['post'] ) ){
			$post = get_post( $_POST['post'] );
		}
		
		if( isset( $_POST['shortcode'] ) ){
			
			echo '<div class="otw_live_preview_shortcode">';
			$attributes = $_POST['shortcode'];
			
			if( isset( $attributes['shortcode_type'] ) && array_key_exists( $attributes['shortcode_type'], $this->shortcodes ) ){
				
				foreach( $this->shortcodes[ $attributes['shortcode_type'] ]['object']->external_libs as $lib ){
					
					$register = false;
					switch( $lib['int'] ){
						
						case 'live_preview':
								$register = true;
							break;
						case 'all':
								$register = true;
							break;
					}
					if( $register ){
					
						switch( $lib['type'] ){
							
							case 'js':
									echo '<script type="text/javascript" src="'.( esc_url( $lib['path'] ) ).'"></script>';
								break;
							case 'css':
									echo '<link rel="stylesheet" type="text/css" href="'.( esc_url( $lib['path'] ) ).'" />';
								break;
						}
					}
				}
				if( $shortcode = $this->shortcodes[ $attributes['shortcode_type'] ]['object']->build_shortcode_code( $attributes ) ){
					echo do_shortcode( stripslashes( $shortcode ) );
				}
			}
		}
		die;
	}
	
	/** Get shortcode by given params from editor interace
	 *
	 */
	public function get_code(){
		
		$response = array();
		$response['code'] = '';
		
		$attributes = otw_stripslashes( $_POST );
		
		if( isset( $attributes['shortcode_type'] ) && array_key_exists( $attributes['shortcode_type'], $this->shortcodes ) ){
			
			if( $shortcode = $this->shortcodes[  $attributes['shortcode_type'] ]['object']->build_shortcode_code( $attributes ) ){
				$response['code'] = $shortcode;
			}
			if( $shortcode_attributes = $this->shortcodes[  $attributes['shortcode_type'] ]['object']->get_shortcode_attributes( $attributes ) ){
				$response['shortcode_attributes'] = $shortcode_attributes;
			}
			if( $this->shortcodes[  $attributes['shortcode_type'] ]['object']->has_error ){
				foreach( $this->shortcodes[  $attributes['shortcode_type'] ]['object']->errors as $error ){
					$this->add_error( $error );
				}
			}
		}else{
			$this->add_error( $this->get_label( 'Invalid shortcode' ) );
		}
		
		$response['has_error'] = $this->has_error;
		$response['errors'] = $this->errors;
		
		echo json_encode( $response );
		die;
	}
	
	/** Sort shortcodes basedn on order field
	 *
	 */
	public function sort_shortcodes( $a, $b ){
		if( $a['order'] > $b['order'] ){
			return 1;
		}
		elseif( $a['order'] < $b['order'] ){
			return -1;
		}
		
		return 0;
	}
	
	
	/**
	 * add default external lib
	 * @type js/css
	 * @name name
	 * @path url
	 * @int front/admin/all
	 * @deps depends
	 */
	public function add_default_external_lib( $type, $name, $path, $int, $order, $deps = array() ){
		
		$this->default_external_libs[] = array( 'type' => $type, 'name' => $name, 'path' => $path, 'int' => $int, 'order' => $order, 'deps' => $deps );
	}
}
?>