<?php
/**
 * Plugin Name: Slack Testimonials Widget
 * Plugin URI: https://github.com/saurabhsirdixit/slack-testimonials-widget
 * Description: This plugin allows you to send notifications to Slack channels whenever someone add testimonials.
 * Version: 0.0.1
 * Author: Saurabh Dixit
 * Author URI: http://gedex.web.id
 * Text Domain: slack
 * Domain Path: /languages
 * License: GPL v2 or later
 * Requires at least: 3.6
 * Tested up to: 4.3.1
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * Adds new event that send notification to Slack channel
 * when someone sent message through Contact Form 7.
 *
 * @param  array $events
 * @return array
 *
 * @filter slack_get_events
 */
function wp_slack_testimonials_widget_submit( $events ) {
	$events['testimonial_published'] = array(
		'action'      => 'publish_testimonials-widget',
		'description' => __( 'When a Testimonials is published', 'slack' ),
		'default'     => false,
		'message'     => function( $ID, $post ) {

			$excerpt = has_excerpt( $post->ID ) ?
				apply_filters( 'get_the_excerpt', $post->post_excerpt )
				:
				wp_trim_words( strip_shortcodes( $post->post_content ), 55, '&hellip;' );

			return sprintf(
				'New testimonial published: *<%1$s|%2$s>* by *%3$s*' . "\n" .
				'> %4$s',

				get_permalink( $post->ID ),
				get_the_title( $post->ID ),
				get_the_author_meta( 'display_name', $post->post_author ),
				$excerpt
			);
		},
	);

	return $events;
}
add_filter( 'slack_get_events', 'wp_slack_testimonials_widget_submit' );