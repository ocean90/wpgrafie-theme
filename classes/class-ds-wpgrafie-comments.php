<?php
class DS_wpGrafie_Comments extends DS_wpGrafie_Theme {
	/**
	 * Callback for wp_list_comments.
	 *
	 * @access public
	 * @static
	 * @param mixed $comment
	 * @param mixed $args
	 * @param mixed $depth
	 * @return void
	 */
	public static function callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$GLOBALS['comment_depth'] = $depth;

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
					<div id="comment-<?php comment_ID(); ?>">
						<p>
						<span class="comment-meta"><?php comment_date(); ?>:</span>
						<span class="comment-author"><?php printf( '<span class="author-name">%s</span>', get_comment_author_link() ) ?></span>
						</p>
					</div>
			<?php
				break;
			default :
				$is_by_author = false;
					if( $comment->comment_author_email == get_the_author_meta( 'email' ) )
						$is_by_author = true;
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
					<article id="comment-<?php comment_ID(); ?>">
						<header class="comment-author">
							<?php
							printf(
								'<span class="author-name">%s %s</span>',
								get_avatar( $comment, 64, get_bloginfo('template_url').'/img/default_avatar.png' ),
								get_comment_author_link()
							);

							if( $is_by_author ) :
							?>
							<span class="author-tag"> (Autor)</span>
							<?php
							endif;

							if ( $comment->comment_approved == 0 ) :
							?>
							<span class="comment-awaiting-moderation"> (Der Kommentar wird derzeit geprüft.)</span>
							<?php
							endif;
							?>
							<a class="comment-permalink" title="<?php echo get_comment_date(); ?>" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>">vor <?php echo human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ); ?></a>
						</header>
						<div class="comment-text">
							<?php comment_text() ?>
						</div>
						<footer class="comment-meta clearfix" >
							<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' =>  'Antworten' ) ) ); ?>
						</footer>
					</article>
				<?php
		endswitch;
	}
}
