/*
 * Custom JS - All front-end jQuery
 */
( function( $ ) {
	$( 'body' ).removeClass( 'no-js' );

	$( 'a[href*=#]' ).click( function( e ) {
		$( 'body' ).animate( { scrollTop: $(this.hash).offset().top }, 350 );
		e.preventDefault();
	} );

	$.ajaxSetup({
		cache: true
	});

	load_social = function() {
		// Google+
		window.___gcfg = {lang: 'de'};
		$.getScript("//apis.google.com/js/plusone.js");

		// Twitter
		$.getScript("//platform.twitter.com/widgets.js");

		// Facebook
		var permalink = $('.fb-like').data('url');
		$('.fb-like').after('<iframe src="//www.facebook.com/plugins/like.php?href=' + permalink + '&amp;send=false&amp;layout=box_count&amp;width=150&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=60&amp;appId=177681565672418" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:60px;" allowTransparency="true"></iframe>');
	}

	if ( ( $( 'body.single' ).length || $( 'body.page' ).length ) && $('#social-row').is(":visible") )
		setTimeout( load_social, 4000 ); // 4 seconds

	// Google Analytics
	$.getScript( '//www.google-analytics.com/ga.js', function() {
		var t = _gat._createTracker( 'UA-5436438-7' );
		_gat._anonymizeIp();
		t._trackPageview();
	} );
} )( jQuery );
