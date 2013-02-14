		<div class="row" id="author-box">
			<h2 class="section-title"><span>Der Autor</span></h2>
			<?php
			$avatar		= get_avatar( get_the_author_meta( 'ID' ) , 100 );
			$name		= get_the_author_meta( 'display_name' );
			$desc		= get_the_author_meta( 'user_description' );
			$social		= array();
			get_the_author_meta( 'googleplus' ) ? $social[] = '<a data-icon="g" class="icon g no-link-style" href="' . esc_url( get_the_author_meta( 'googleplus' ) ) . '">Google+</a>' : '';
			get_the_author_meta( 'twitter' ) ? $social[] = '<a data-icon="t" class="icon t no-link-style" href="' . esc_url( get_the_author_meta( 'twitter' ) ) . '">Twitter</a>' : '';
			get_the_author_meta( 'facebook' ) ? $social[] = '<a data-icon="f" class="icon f no-link-style" href="' . esc_url( get_the_author_meta( 'facebook' ) ) . '">Facebook</a>' : '';
			$website	= get_the_author_meta( 'user_url' );
			?>
			<div class="author-box">
				<?php echo $avatar; ?>
				<h5><?php echo $name; ?></h5>
				<p><?php echo $desc; ?></p>
				<?php
				if ( ! empty( $social ) ) {
					$social_list = '';
					foreach ( $social as $i => $link )
						$social_list .= sprintf( '<li>%s</li>', $link );

					printf( '<ul class="social-list">%s</ul>', $social_list );
				}
				?>
			</div>
		</div>
