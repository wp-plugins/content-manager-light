<?php
class OTW_Shortcode_Content_toggle extends OTW_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = true;
	}
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode-general_foundicons', $this->component_url.'css/general_foundicons.css', 'all', 10 );
		$this->add_external_lib( 'css', 'otw-shortcode-social_foundicons', $this->component_url.'css/social_foundicons.css', 'all', 20 );
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );

		
		$this->add_external_lib( 'js', 'otw-shortcode-core', $this->component_url.'js/otw_shortcode_core.js', 'all', 99, array( 'jquery' ) );
		$this->add_external_lib( 'js', 'otw-shortcode', $this->component_url.'js/otw_shortcode.js', 'front', 100 );
		$this->add_external_lib( 'js', 'otw-shortcode_live_preview', $this->component_url.'js/otw_shortcode_live_preview.js', 'live_preview', 200 );
	}
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array(
		
			'opened' => array(
				''           => $this->get_label( 'yes(default)' ),
				'closed'     => $this->get_label( 'no' )
			),
			'default_opened' => '',
			
			'icon_types' => array(
			
				''                      => $this->get_label( 'none (Default)' ),
				'general foundicon-settings'      => $this->get_label( 'Settings' ),
				'general foundicon-heart'         => $this->get_label( 'Heart' ),
				'general foundicon-star'          => $this->get_label( 'Star' ),
				'general foundicon-plus'          => $this->get_label( 'Plus' ),
				'general foundicon-minus'         => $this->get_label( 'Minus' ),
				'general foundicon-checkmark'     => $this->get_label( 'Checkmark' ),
				'general foundicon-remove'        => $this->get_label( 'Remove' ),
				'general foundicon-mail'          => $this->get_label( 'Mail' ),
				'general foundicon-calendar'      => $this->get_label( 'Calendar' ),
				'general foundicon-page'          => $this->get_label( 'Page' ),
				'general foundicon-tools'         => $this->get_label( 'Tools' ),
				'general foundicon-globe'         => $this->get_label( 'Globe' ),
				'general foundicon-cloud'         => $this->get_label( 'Cloud' ),
				'general foundicon-error'         => $this->get_label( 'Error' ),
				'general foundicon-right-arrow'   => $this->get_label( 'Right arrow' ),
				'general foundicon-left-arrow'    => $this->get_label( 'Left arrow' ),
				'general foundicon-up-arrow'      => $this->get_label( 'Up arrow' ),
				'general foundicon-down-arrow'    => $this->get_label( 'Down arrow' ),
				'general foundicon-trash'         => $this->get_label( 'Trash' ),
				'general foundicon-add-doc'       => $this->get_label( 'Add Doc' ),
				'general foundicon-edit'          => $this->get_label( 'Edit' ),
				'general foundicon-lock'          => $this->get_label( 'Lock' ),
				'general foundicon-unlock'        => $this->get_label( 'Unlock' ),
				'general foundicon-refresh'       => $this->get_label( 'Refresh' ),
				'general foundicon-paper-clip'    => $this->get_label( 'Paper clip' ),
				'general foundicon-video'         => $this->get_label( 'Video' ),
				'general foundicon-photo'         => $this->get_label( 'Photo' ),
				'general foundicon-graph'         => $this->get_label( 'Graph' ),
				'general foundicon-idea'          => $this->get_label( 'Idea' ),
				'general foundicon-mic'           => $this->get_label( 'Mic' ),
				'general foundicon-cart'          => $this->get_label( 'Cart' ),
				'general foundicon-address-book'  => $this->get_label( 'Address book' ),
				'general foundicon-compass'       => $this->get_label( 'Compass' ),
				'general foundicon-flag'          => $this->get_label( 'Flag' ),
				'general foundicon-location'      => $this->get_label( 'Location' ),
				'general foundicon-clock'         => $this->get_label( 'Clock' ),
				'general foundicon-folder'        => $this->get_label( 'Folder' ),
				'general foundicon-inbox'         => $this->get_label( 'Inbox' ),
				'general foundicon-website'       => $this->get_label( 'Website' ),
				'general foundicon-smiley'        => $this->get_label( 'Smiley' ),
				'general foundicon-search'        => $this->get_label( 'Search' ),
				'general foundicon-phone'         => $this->get_label( 'Phone' ),
				
				'social foundicon-thumb-up'       => $this->get_label( 'Thumb up' ),
				'social foundicon-thumb-down'     => $this->get_label( 'Thumb down' ),
				'social foundicon-rss'            => $this->get_label( 'Rss' ),
				'social foundicon-facebook'       => $this->get_label( 'Facebook' ),
				'social foundicon-twitter'        => $this->get_label( 'Twitter' ),
				'social foundicon-pinterest'      => $this->get_label( 'Pinterest' ),
				'social foundicon-github'         => $this->get_label( 'Github' ),
				'social foundicon-path'           => $this->get_label( 'Path' ),
				'social foundicon-linkedin'       => $this->get_label( 'LinkedIn' ),
				'social foundicon-dribbble'       => $this->get_label( 'Dribbble' ),
				'social foundicon-stumble-upon'   => $this->get_label( 'Stumble upon' ),
				'social foundicon-behance'        => $this->get_label( 'Behance' ),
				'social foundicon-reddit'         => $this->get_label( 'Reddit' ),
				'social foundicon-google-plus'    => $this->get_label( 'Google plus' ),
				'social foundicon-youtube'        => $this->get_label( 'Youtube' ),
				'social foundicon-vimeo'          => $this->get_label( 'Vimeo' ),
				'social foundicon-clickr'         => $this->get_label( 'Clickr' ),
				'social foundicon-slideshare'     => $this->get_label( 'Slideshare' ),
				'social foundicon-picassa'        => $this->get_label( 'Picassa' ),
				'social foundicon-skype'          => $this->get_label( 'Skype' ),
				'social foundicon-instagram'      => $this->get_label( 'instagram' ),
				'social foundicon-foursquare'     => $this->get_label( 'Foursquare' ),
				'social foundicon-delicious'      => $this->get_label( 'Delicious' ),
				'social foundicon-chat'           => $this->get_label( 'Chat' ),
				'social foundicon-torso'          => $this->get_label( 'Torso' ),
				'social foundicon-tumblr'         => $this->get_label( 'Tumblr' ),
				'social foundicon-video-chat'     => $this->get_label( 'Video chat' ),
				'social foundicon-digg'           => $this->get_label( 'Digg' ),
				'social foundicon-wordpress'      => $this->get_label( 'Wordpress' )
			),
			'default_icon_type' => ''
		);
		
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
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-title', 'label' => $this->get_label( 'Title' ), 'description' => $this->get_label( 'Toggle title.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::text_area( array( 'id' => 'otw-shortcode-element-content', 'label' => $this->get_label( 'Content' ), 'description' => $this->get_label( 'Toggle content.' ), 'parse' => $source )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-opened', 'label' => $this->get_label( 'Opened' ), 'description' => $this->get_label( 'Opened or closed on page load.' ), 'parse' => $source, 'options' => $this->settings['opened'], 'value' => $this->settings['default_opened'] ) );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-icon_type', 'label' => $this->get_label( 'Icon' ), 'description' => $this->get_label( 'Optional foundation icon that is placed before the title.' ), 'parse' => $source, 'options' => $this->settings['icon_types'], 'value' => $this->settings['default_icon_type'] )  );
		
		$html .= OTW_Form::uploader( array( 'id' => 'otw-shortcode-element-icon_url', 'label' => $this->get_label( 'Icon URL' ), 'description' => $this->get_label( 'Url to a custom icon.' ), 'parse' => $source )  );
		
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
		
			$code = '[otw_shortcode_content_toggle';
			
			$code .= $this->format_attribute( 'title', 'title', $attributes, false, '', true );
			
			$code .= $this->format_attribute( 'opened', 'opened', $attributes );
			
			$code .= $this->format_attribute( 'icon_type', 'icon_type', $attributes );
			
			$code .= $this->format_attribute( 'icon_url', 'icon_url', $attributes );
			
			$code .= $this->format_attribute( 'css_class', 'css_class', $attributes, false, '', true  );
			
			$code .= ']';
			
			$code .= strip_tags( $attributes['content'] );
			
			$code .= '[/otw_shortcode_content_toggle]';
		}
		
		return $code;

	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '<div';
		
		/*class attributes*/
		$class = 'otw-sc-toggle';
		
		$class .= $this->format_attribute( '', 'css_class', $attributes, false, $class );
		
		if( strlen( $class ) ){
			$html .= ' class="'.$class.'"';
		}
		/*end class attributes*/
		
		/*style attribute*/
		$style = '';
		
		if( strlen( $style ) ){
			$html .= ' style="'.$style.'"';
		}
		
		$html .= '>';
		
		$html .= '<h3';
		
		$title_class = 'toggle-trigger widget-title';
		
		$title_class .= $this->format_attribute( '', 'opened', $attributes, false, $title_class );
		
		
		if( strlen( $title_class ) ){
			$html .= ' class="'.$title_class.'"';
		}
		
		$html .= '>';
		
		if( $icon_type = $this->format_attribute( '', 'icon_type', $attributes, false, '' ) ){
			$html .= '<i class="'.$icon_type.'"> </i>';
		}
		
		if( $icon_url = $this->format_attribute( '', 'icon_url', $attributes, false, '' ) ){
			$html .= '<img src="'.$icon_url.'" title="icon" alt="icon" />';
		}
		
		if( $title = $this->format_attribute( '', 'title', $attributes, false, '' ) ){
			$html .= $title;
		}
		$html .= '<span class="icon"></span></h3>';
		$html .= '<div class="toggle-content">';
		$html .= '<p>'.nl2br( $content ).'</p>';
		$html .= '</div>';
		
		$html .= '</div>';
		return $html;
	}
}
