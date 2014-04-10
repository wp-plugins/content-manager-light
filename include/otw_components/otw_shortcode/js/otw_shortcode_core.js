function otw_shortcode_tabs( selectors ){
	
	for( var cS = 0; cS < selectors.size(); cS++ ){
		
		var selector = jQuery( selectors[cS] );
		
		var links = selector.find( 'ul.ui-tabs-nav>li>a' );
		
		var active_tab = 0;
		
		for( var cA = 0; cA < links.length; cA++ ){
		
			if( jQuery( links[cA] ).parent().hasClass( 'ui-tabs-active ui-state-active' ) ){
			
				active_tab = cA;
				break;
			}
		}
		for( var cA = 0; cA < links.length; cA++ ){
			
			if( active_tab == cA ){
				jQuery( links[cA] ).parent().addClass( 'ui-tabs-active ui-state-active' );
				selector.find( jQuery( links[cA] ).attr( 'href' ) ).show();
			}else{
				jQuery( links[cA] ).parent().removeClass( 'ui-tabs-active ui-state-active' );
				selector.find( jQuery( links[cA] ).attr( 'href' ) ).hide();
			};
		};
		
		selector.find( 'ul.ui-tabs-nav>li>a' ).click( function( event ){
			
			event.preventDefault();
			jQuery(this).parent().siblings().removeClass("ui-tabs-active ui-state-active");
			jQuery( this ).parent().addClass("ui-tabs-active ui-state-active");
			var tab = jQuery(this).attr("href");
			jQuery( this ).parent().parent().parent().children(".ui-widget-content").not(tab).hide();
			jQuery( this ).parent().parent().parent().children(tab).show();
		} );
	};
};
function otw_shortcode_content_toggle( selector, closed ){

	selector.unbind( 'click' ); 
	selector.click(function (){
		jQuery(this).toggleClass('closed').next('.toggle-content').slideToggle(350);
	});
	closed.next('.toggle-content').hide();
};

