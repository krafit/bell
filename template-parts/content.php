<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package krafit_bell
 */

$categories = get_the_category();
	foreach ( $categories as $category ) { 
    	$image = get_term_meta( $category->term_id, 'image', true );
	}
	
// We'll need a link to link to the feed source.
	$podcast_permalink = get_post_meta($post->ID, 'wprss_item_permalink', true);


?>



<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( $podcast_permalink ) . '" rel="bookmark" target="_blank">', '</a></h2>' );
			}
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php	
			if ( ! empty( $image ) ) {
	    		echo '<a href="' . esc_url( $podcast_permalink ) . '" rel="bookmark" target="_blank"><img src="' . esc_url( wp_get_attachment_url( $image) ) . '" class="episode-cover alignleft"></a>';
			}
		?>
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->
	
	<footer class="entry-footer">
			<?php krafit_bell_episode_meta(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
