/*
 http://www.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress
 
 
 $attribs['data-nonce']			= $nonce;
 $attribs['data-post_id']		= $GLOBALS['post']->ID;
 $attribs['data-value-wadm']		= $wadm_id;
 $attribs['data-value-lang']		= $useLanguage;
 $attribs['data-value-defUrl']	= $defaulturl;
 $attribs['data-value-domain']	= useDomain;
 
*/
jQuery(document).ready(function($) {
	$(".ohmyprints_hook a").on('click',function ( event ) {
		event.preventDefault();
/*		post_id = jQuery(this).attr("data-post_id")
		nonce = jQuery(this).attr("data-nonce")
		wadm = jQuery(this).attr("data-value-lang")
		lang = jQuery(this).attr("data-value-lang")*/
		
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : myAjax.ajaxurl,
			data : {
					action: "clicked_for_sale", 
					post_id : jQuery(this).attr("data-post_id"), 
					nonce: jQuery(this).attr("data-nonce"), 
					wadm: jQuery(this).attr("data-value-wadm"),
					lang: jQuery(this).attr("data-value-lang"),
					defUrl: jQuery(this).attr("data-value-defUrl"),
					domain: jQuery(this).attr("data-value-domain")
			},
			success: function(response) {
				if(response.type == "success") {
					window.open( response.url );
	
				}
				else {
					alert("Oops.. don't know what, but something went wrong..")
				}
			},
			error: function( response ) {
				alert('ajax error');
			}
		})   

	});
	
});
