jQuery(function($) {
	/*tabs layout*/
	otw_shortcode_tabs( jQuery( '#otw-shortcode-preview' ).contents().find('body').find( '.otw-sc-tabs' ) );
	otw_shortcode_tabs( jQuery( '.otw-shortcode-preview iframe' ).contents().find('body').find( '.otw-sc-tabs' ) );

	/*content toggle*/
	otw_shortcode_content_toggle( jQuery( '#otw-shortcode-preview' ).contents().find('body').find('.otw-sc-toggle > .toggle-trigger'), jQuery( '#otw-shortcode-preview' ).contents().find('body').find('.otw-sc-toggle > .toggle-trigger.closed') );
	otw_shortcode_content_toggle( jQuery( '.otw-shortcode-preview iframe' ).contents().find('body').find('.otw-sc-toggle > .toggle-trigger'), jQuery( '.otw-shortcode-preview iframe' ).contents().find('body').find('.otw-sc-toggle > .toggle-trigger.closed') );
});