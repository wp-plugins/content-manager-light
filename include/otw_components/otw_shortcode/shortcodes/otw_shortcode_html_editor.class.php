<?php
class OTW_Shortcode_Html_Editor extends OTW_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = true;
		
		parent::__construct();
		
		if( is_admin() ){
			add_action('admin_footer', array( $this, 'otw_shortcode_html_area_editor' ) );
		}else{
			add_action('wp_footer', array( $this, 'otw_shortcode_html_area_editor' ) );
		}
	}
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );
	}
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
	}
	
	
	public function otw_shortcode_html_area_editor(){
	
		if( is_admin() ){
			echo OTW_Form::html_area( array( 'id' => 'otw-shortcode-element-content_tmce', 'label' => 'Content', 'format' => 'tmce' )  );
		}elseif( $this->init_in_front && otw_is_grid_manager_content() ){
			echo OTW_Form::html_area( array( 'id' => 'otw-shortcode-element-content_tmce', 'label' => 'Content', 'format' => 'tmce' )  );
		}
	}
	
	/**
	 * Shortcode admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
		}
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-title', 'label' => $this->get_label( 'Title' ), 'description' => $this->get_label( 'Optional title.' ), 'parse' => $source )  );
		
		$ivalue = '';
		
		if( isset( $source['otw-shortcode-element-content'] ) ){
			$ivalue = $source['otw-shortcode-element-content'];
		}
		
		$html .= OTW_Form::html_area( array( 'id' => 'otw-shortcode-element-content', 'parse' => $source, 'format' => 'tmce_holder' )  );
		//$html .= OTW_Form::html_area( array( 'id' => 'otw-shortcode-element-content', 'label' => $this->get_label( 'Content' ), 'description' => $this->get_label( 'Content text.' ), 'parse' => $source )  );
		
		return $html;
	}
	
	/**
	 * Shortcode admin interface custom options
	 */
	public function build_shortcode_editor_custom_options(){
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
		}
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-css_class', 'label' => $this->get_label( 'CSS Class' ), 'description' => $this->get_label( 'If you\'d like to style this element separately enter a name here. A CSS class with this name will be available for you to style this particular element..' ), 'parse' => $source )  );
		
		return $html;
	}
	
	/** build shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_html_editor';
			
			$code .= $this->format_attribute( 'title', 'title', $attributes, false, '', true );
			
			$code .= $this->format_attribute( 'css_class', 'css_class', $attributes, false, '', true  );
			
			$code .= ']';
			
			$code .= $this->format_attribute( '', 'content', $attributes );
			
			$code .= '[/otw_shortcode_html_editor]';
		}
		
		return $code;

	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '<div';
		
		/*class attributes*/
		$class = 'otw-widget-text';
		
		$class .= $this->format_attribute( '', 'css_class', $attributes, false, $class );
		
		if( strlen( $class ) ){
			$html .= ' class="'.$class.'"';
		}
		/*end class attributes*/
		
		$html .= '>';
		
		if( $title = $this->format_attribute( '', 'title', $attributes, false, '' ) ){
			$html .= '<h3 class="widget-title">'.$title.'</h3>';
		}
		
		$html .= '<div class="text-content">';
		
		ob_start();
		$html .= do_shortcode( nl2br( $content ) );
		$html .= ob_get_contents();
		ob_end_clean();
		
		$html .= '</div>';
		
		$html .= '</div>';
		return $html;
		
	}
}
