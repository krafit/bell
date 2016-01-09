<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package krafit_bell
 */

if ( ! function_exists( 'krafit_bell_episode_meta' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function krafit_bell_episode_meta() {

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		$time_string
	);

	$byline = sprintf(
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_the_author_meta( 'user_url' ) ) . '" target="_blank">' . esc_html( get_the_author() ) . '</a></span>'
	);
	
	// We'll need a link to link to the feed source.
		global $wp_query;
			$post = $wp_query->post;
			$podcast_permalink = get_post_meta($post->ID, 'wprss_item_permalink', true);
		wp_reset_query();

	echo '<div class="raw"><div class="column third no-margin episode-link"><a href="' . esc_url( $podcast_permalink ) . '" rel="bookmark" target="_blank">Zur Episode</a></div><div class="column third no-margin episode-date">' . $posted_on . '</div><div class="column third no-margin episode-author">' . $byline . '</div></div>'; // WPCS: XSS OK.
}
endif;

if ( ! function_exists( 'krafit_bell_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function krafit_bell_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'krafit_bell' ) );
		if ( $categories_list && krafit_bell_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'krafit_bell' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'krafit_bell' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'krafit_bell' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'krafit_bell' ), esc_html__( '1 Comment', 'krafit_bell' ), esc_html__( '% Comments', 'krafit_bell' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'krafit_bell' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function krafit_bell_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'krafit_bell_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'krafit_bell_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so krafit_bell_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so krafit_bell_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in krafit_bell_categorized_blog.
 */
function krafit_bell_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'krafit_bell_categories' );
}
add_action( 'edit_category', 'krafit_bell_category_transient_flusher' );
add_action( 'save_post',     'krafit_bell_category_transient_flusher' );
