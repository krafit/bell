<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package krafit_bell
 */

// Redirect all the traffic to the original Post / Episode.
		$podcast_permalink = get_post_meta($post->ID, 'wprss_item_permalink', true);

		header('Location: ' . $podcast_permalink);

