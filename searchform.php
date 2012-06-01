<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<p>
		<input type="search" value="<?php the_search_query(); ?>" name="s" id="search-input" placeholder="Keywords eingeben" />
		<?php /* <input type="submit" value="Suchen" id="searchform-submit" /> */ ?>
	</p>
</form>
