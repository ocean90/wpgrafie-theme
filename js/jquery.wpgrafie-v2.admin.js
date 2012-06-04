( function( $ ) {
	$( '#gist_update' ).click( function( e ) {
		e.preventDefault();

		$('#gist-ajax-loading').css('visibility', 'visible');

		data = {
			'action' : 'gist',
			'_ajax_nonce' : $('#schnipsel_gist_nonce').val(),
			'post_id' : $('#post_ID').val(),
			'gist_id' : $('#gist_id').val()
		};

		$.post( ajaxurl, data,
			function( res ) {
				res = $.parseJSON( res );

				if ( res.error ) {
					$('#gist-ajax-loading').css('visibility', 'hidden');
					alert( 'Gist ID "' + res.id + '": ' + res.error );
					return;
				}

				if ( res.files ) {
					$('#gist_files').empty();
					$.each( res.files, function( key, val ) {
						$('#gist_files').append('<li><code>{' + val + '}</code></li>');
					});
					$('#gist-ajax-loading').css('visibility', 'hidden');
				}

			}
		)
	});
} )( jQuery );
