<?php
class DS_wpGrafie_Theme_Widgets extends DS_wpGrafie_Theme {
	private static $sidebars;

	/**
	 * Initialize class.
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {
		self::$sidebars = array(
			'sidebar' => array(
					'name' => 'Sidebar',
					'description' => 'Standard Sidebar'
			),
			'sidebar_home' => array(
					'name' => 'Home Sidebar',
					'description' => 'Home Sidebar'
			),
			'sidebar_pages' => array(
					'name' => 'Page Sidebar',
					'description' => 'Seiten Sidebar'
			),
			'sidebar_posts' => array(
					'name' => 'Post Sidebar',
					'description' => 'Artikel Sidebar'
			),
			'sidebar_post_format_aside' => array(
					'name' => 'Post Formate Aside Sidebar',
					'description' => 'Artikel Aside Sidebar'
			),
			'sidebar_post_format_gallery' => array(
					'name' => 'Post Formate Gallery Sidebar',
					'description' => 'Artikel Gallery Sidebar'
			),
			'sidebar_archive' => array(
					'name' => 'Archive Sidebar',
					'description' => 'Archiv Widget'
			)
		);

		add_action( 'widgets_init', array( __CLASS__, 'init_widgets' ) );
	}

	public static function init_widgets() {
		self::generate_sidebars();

		register_widget( 'wpgrafie_Post_Tags_Widget' );
		register_widget( 'wpgrafie_Post_Infos_Widget' );
		register_widget( 'wpgrafie_Related_Posts_By_Cat_Widget' );
		register_widget( 'wpgrafie_Banner' );
	}

	private function generate_sidebars() {
		$sidebars = self::$sidebars;
		foreach ( $sidebars as $sidebar => $data ) {
			register_sidebar( array(
					'name' => $data['name'],
					'id' => $sidebar,
					'description' => $data['description'],
					'before_widget' => '<div id="%1$s" class="%2$s widget-box">',
					'after_widget' => '</div>',
					'before_title' => '<h6>',
					'after_title' => '</h6>'
				)
			);
		}
	}
}

class wpgrafie_Banner extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_banner', 'description' => 'Banner für die Sidebar' );
		parent::__construct( 'wpgrafie_banner', 'Banner', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$link = empty( $instance['link'] ) ? '' : esc_url( $instance['link'] );
		$image_src = empty( $instance['image_src'] ) ? '' : esc_url( $instance['image_src'] );

		if ( empty( $image_src ) )
			return;

		$banner = sprintf(
			'<img src="%s" title="%s" class="banner-image" />',
			$image_src,
			$title
		);

		if ( ! empty( $link ) )
			$banner = sprintf(
				'<a href="%s" class="banner-link">%s</a>',
				$link,
				$banner
			);

		echo $before_widget;
		echo $before_title . 'Empfehlung' . $after_title;
		echo $banner;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['link'] = esc_url_raw( $new_instance['link'] );
		$instance['image_src'] = esc_url_raw( $new_instance['image_src'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'link' => '', 'image_src' => '' ) );

		$title = esc_attr( $instance['title'] );
		$link = esc_attr( $instance['link'] );
		$image_src = esc_attr( $instance['image_src'] );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Titel
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>">Link
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'image_src' ); ?>">Grafik
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_src' ); ?>" name="<?php echo $this->get_field_name( 'image_src' ); ?>" type="text" value="<?php echo $image_src; ?>" />
			</label>
		</p>
		<?php
	}

}

class wpgrafie_Post_Tags_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_current_post_tags', 'description' => 'Zeigt die Schlagwörter zum aktuellen Beitrag an.' );
		parent::__construct( 'wpgrafie_post_tags', 'Verwendete Schlagwörter', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

		$current_object = get_queried_object();
		if ( ! empty( $current_object->post_type ) && $current_object->post_type == 'post')
			$tags = get_the_term_list( $current_object->ID, 'post_tag', '<ul><li>', '</li><li>', '</li></ul>' );
		else
			return;

		if ( empty( $tags ) )
			return;

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo $tags;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags( $instance['title'] );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'themetrust' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<?php
	}

}

class wpgrafie_Post_Infos_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_current_post_infos', 'description' => 'Zeigt Informationen über den Artikel an' );
		parent::__construct( 'wpgrafie_post_infos', 'Artikelinformationen', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$current_object = get_queried_object();

		if ( empty( $current_object ) )
			return;

		$info = '';
		//$info .= sprintf( '<p>%s <span><a href="%s">%s</a></span></p>', 'Von', get_author_posts_url( $current_object->post_author ), get_userdata( $current_object->post_author )->display_name );
		$info .= sprintf( '<p>%s <span class="t">%s</span></p>', 'Am', get_the_date() );
		$info .= sprintf( '<p>%s <span class="t">%s</span></p>', 'Thema', get_the_term_list( $current_object->ID, 'category', '', ', ', '' ) );
		$info .= sprintf( '<p>%s <span class="t"><a href="#comments">%s</a></span></p>', 'Mit', sprintf( _n( '%d Kommentar', '%d Kommentaren', $current_object->comment_count) , $current_object->comment_count ) );
		$info .= sprintf( '<p>&infin; <span class="t"><a href="%s">%s</a></span></p>', esc_url( wp_get_shortlink() ), 'Kurzlink' );
		$info .= sprintf( '<p><span class="icon twitter"></span> <span class="t"><a href="%s" target="_blank">%s</a></span></p>', esc_url( 'https://twitter.com/intent/tweet?text=' . urlencode( $current_object->post_title . ' -' ) . '&url='. urlencode( get_permalink( $current_object->ID ) ) .'&via=ocean90' ), 'Auf Twitter teilen' );
		$info .= sprintf( '<p><span class="icon facebook"></span> <span class="t"><a href="%s" target="_blank">%s</a></span></p>', esc_url( 'https://www.facebook.com/sharer.php?u='. urlencode( get_permalink( $current_object->ID ) ) .'&t=' . urlencode( $current_object->post_title ) ), 'Auf Facebook teilen' );
		$info .= sprintf( '<p><span class="icon google"></span> <span class="t"><g:plusone size="small" annotation="inline" width="200"></g:plusone></span></p>' );

		if ( empty( $info ) )
			return;

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo $info;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags( $instance['title'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Titel:
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<?php
	}

}

class wpgrafie_Related_Posts_By_Cat_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'wpgrafie_related_posts_by_cat', 'description' => 'Zeigt ähnliche Artikel aus der selben Kategorie an.' );
		parent::__construct( 'wpgrafie_related_posts_by_cat', 'Ähnliche Artikel', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$number = ( empty( $instance['number'] ) || ! is_numeric( $instance['number'] ) ) ? 3 : $instance['number'];

		$current_object = get_queried_object();
		$terms = get_the_terms( $current_object->ID, 'category' );

		if ( empty( $terms ) || is_wp_error( $terms ) )
			return;

		$category__in = array();
		foreach ($terms as $term)
			$category__in[] = $term->term_id;

		$args = array(
			'post_type' => 'post',
			'order' => ( mt_rand( 0, 1 ) == 1 ) ? 'DESC' : 'ASC',
			'orderby' => 'rand',
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish',
			'post__not_in' => array( $current_object->ID ),
			'posts_per_page' => $number,
			'category__in' => $category__in
		);
		$posts = new WP_Query( $args );

		if ( get_post_format() == 'gallery' ) {
			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;
			echo '<p>Im Moment sind leider noch keine weiteren Gallerien vorhanden. Weitere werden aber folgen!</p>';
			echo $after_widget;
			return;
		}


		if ( ! $posts->have_posts() )
			return;

		$output = '';
		while ( $posts->have_posts() ) :
			$posts->the_post();
			$output .= '<li>';
			$output .= '<a href="' . get_permalink() . '">';
			if( has_post_thumbnail() )
				$output .= get_the_post_thumbnail( null, 'related_post_thumb', array( 'class' => 'related_post_thumb', 'alt' => '') );
			else
				$output .= '<span class="img"></span>';
			$output .= '<span>'. get_the_title() . '</span>';
			$output .= '</a>';
			$output .= '</li>';
		endwhile;

		wp_reset_postdata();

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo '<ul>' . $output . '</ul>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$new_instance['number'] = absint( intval( $new_instance['number'] ) );
		$instance['number'] = ( empty( $new_instance['number'] ) || ! is_numeric( $new_instance['number'] ) ) ? 3 : $new_instance['number'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'number' => 3 ) );
		$title = strip_tags( $instance['title'] );
		$number = absint( intval( $instance['number'] ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Titel
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Anzahl
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
			</label>
		</p>
		<?php
	}

}

?>
