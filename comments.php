<section id="comments-section">
	<?php
	if ( have_comments() ) :
	?>
	<div class="row">
		<div class="span8">
			<div class="row">
				<h3 id="comments-title" class="icon" data-icon="c">Eure Meinungen</h3>
			</div>

			<ol id="comments-list" class="row">
				<?php
				wp_list_comments( array(
					'callback' => 'DS_wpGrafie_Comments::callback',
					'type'     => 'comment'
				) );
				?>
			</ol>
		</div>
		<div class="span4 spanempty">
		</div>
	</div>
	<?php
	endif;
	?>
	<div class="row">
		<div class="span8">
			<?php
			$commenter = wp_get_current_commenter();
			$fields =  array(
				'author' => '<p class="comment-form-author">' . '<label for="author">Dein Name: <span class="required">*</span></label> <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
				'email'  => '<p class="comment-form-email"><label for="email">Deine E-Mail Adresse: <span class="required">*</span></label> <input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
				'url'    => '<p class="comment-form-url"><label for="url">Deine Webseite:</label>' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
			);
			comment_form( array(
				'fields'               => $fields,
				'title_reply'          => 'Auch deine Meinung ist mir wichtig',
				'cancel_reply_link'    => 'Abbrechen',
				'comment_notes_before' => '<p class="comment-notes">Hey! Hier kannst du deine Meinung, Feedback oder Ergänzungen teilen. Bleib dabei bitte höflich.<br/>Achte auch darauf, dass jeglicher Spam(versuch) kommentarlos gelöscht wird. Notwendige Felder sind mit einem <span class="required">*</span> markiert. Deine E-Mail Adresse wird nicht veröffentlicht.</p>',
				'comment_field'        => '<p class="comment-form-comment"><label for="comment">Dein Kommentar: <span class="required">*</span></label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
				'comment_notes_after'  => false,
				'cancel_reply_link'    => '(Antworten abbrechen)'

			) );
			?>
		</div>
		<div class="span3 spanempty">
		</div>
	</div>
</section>
