/*
 * Thanks FitVids
 */
(function( $ ){
	$.fn.fitVids = function() {
		var div = document.createElement('div');

		return this.each( function() {
			var selectors = [
				"iframe[src*='player.vimeo.com']",
				"iframe[src*='www.youtube.com']",
				"object",
				"embed"
			];

			var $allVideos = $(this).find(selectors.join(','));

			$allVideos.each( function() {
				var $this = $(this);
				if (
					this.tagName.toLowerCase() == 'embed' &&
					$this.parent('object').length ||
					$this.parent('.fluid-width-video-wrapper').length
				) {
					return;
				}

				var height = this.tagName.toLowerCase() == 'object' ? $this.attr('height') : $this.height(),
				aspectRatio = height / $this.width();
				if( ! $this.attr('id') ) {
					var videoID = 'fitvid' + Math.floor( Math.random()*999999 );
					$this.attr('id', videoID);
				}

				$this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
				$this.removeAttr('height').removeAttr('width');
		});
	});
};
})( jQuery );

/*
 * Custom JS - All front-end jQuery
 */
var _gaq = _gaq || [];
( function( $ ) {
	$( 'body' ).removeClass( 'no-js' );

	$( 'a[href*=#]' ).click( function( e ) {
		if ( ! this.hash ) return;
		$( 'body' ).animate( { scrollTop: $(this.hash).offset().top }, 350 );
		e.preventDefault();
	} );

	$.ajaxSetup({
		cache: true
	});

	$("#article").fitVids();

	load_social = function() {
		// Google+
		window.___gcfg = {lang: 'de'};
		$.getScript("//apis.google.com/js/plusone.js");

		// Twitter
		$.getScript("//platform.twitter.com/widgets.js");

		// Facebook
		var permalink = $('.fb-like').data('url');
		$('.fb-like').after('<iframe src="//www.facebook.com/plugins/like.php?href=' + permalink + '&amp;send=false&amp;layout=box_count&amp;width=150&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=60&amp;appId=177681565672418" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:60px;" allowTransparency="true"></iframe>');
	};

	if ( ( $( 'body.single' ).length || $( 'body.page' ).length ) && $('#social-row').is(":visible") )
		setTimeout( load_social, 5000 ); // 5 seconds

	// Google Analytics
	$.getScript( '//www.google-analytics.com/ga.js', function() {
		_gaq.push( ['_setAccount', 'UA-5436438-7'] );
		_gaq.push( ['_gat._anonymizeIp'] );
		_gaq.push( ['_setSiteSpeedSampleRate', 100] );
		_gaq.push( ['_trackPageview'] );

		$('.thanks-link').click( function(e) {
			e.preventDefault();
			var href = $(this).attr('href');
			_gaq.push( ['_trackEvent', 'Links', 'Klick', 'Danke sagen' ] );
			setTimeout(function() { location.href = href; }, 200);
		});
		$('#menu-item-feed').click( function(e) {
			e.preventDefault();
			var href = $('a', this).attr('href');
			_gaq.push( ['_trackEvent', 'Links', 'Klick', 'Feed' ] );
			setTimeout(function() { location.href = href; }, 200);
		});
	} );

	// http://www.hongkiat.com/blog/responsive-web-nav/
	var pull       = $('#pull-navi');
		menu       = $('#page-navigation ul');
		menuHeight = menu.height();

	$(pull).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});

	var persHeader = $( '#header-persistent > div'),
		nav = $('#page-navigation');
		navOffsetTop = nav.offset().top,
		home = $('<h1 />')
			.attr( 'id', 'persistent-home-link' )
			.html('<a href="/">wpGrafie</a>');

	persHeader.append(home).append( nav.clone().attr( 'id', 'pers-page-navigation') );

	$(window).on('scroll', function() {
		if ( $(window).width() < 700 ) {
			persHeader.hide();
			return;
		}

		var scrollTop = $(window).scrollTop();

		if ( scrollTop > navOffsetTop ) {
			persHeader.show();
		} else {
			persHeader.hide();
		}
	} );
} )( jQuery );
