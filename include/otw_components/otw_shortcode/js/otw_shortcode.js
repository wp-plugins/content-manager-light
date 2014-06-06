jQuery( document ).ready( function(){

	//tabs
	otw_shortcode_tabs( jQuery( '.otw-sc-tabs' ) );
	
	//content toggle
	otw_shortcode_content_toggle( jQuery('.toggle-trigger'), jQuery('.toggle-trigger.closed') );
	
});