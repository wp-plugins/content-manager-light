(function(){
	
	tinymce.PluginManager.requireLangPack( otw_shortcode_component.tinymce_button_key );
	
	tinymce.create('tinymce.plugins.OTWSBMPlugin', {
	
		init : function(ed, url) {
			
			ed.addCommand('otwShortCode', function() {
				
				otw_shortcode_component.open_drowpdown_menu( jQuery( '#content_' + otw_shortcode_component.tinymce_button_key ).parent() );
				otw_shortcode_component.insert_code = function( shortcode_object ){
					
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, shortcode_object.shortcode_code );
					tb_remove();
				}
			});
			
			// Register example button
			ed.addButton( otw_shortcode_component.tinymce_button_key, {
				
				title : 'Insert ShortCode',
				cmd : 'otwShortCode',
				image : url + '/../images/otw-sbm-icon.png'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive( otw_shortcode_component.tinymce_button_key, n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return { 
				longname : 'OTW Shortcode Component',
				author : 'OTWthemes.com',
				authorurl : 'http://themeforest.net/user/OTWthemes',
				infourl : 'http://OTWthemes.com',
				version : "1.0"
			}
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add( otw_shortcode_component.tinymce_button_key, tinymce.plugins.OTWSBMPlugin);
	
})();