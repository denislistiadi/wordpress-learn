<?php

/*
Plugin Name: Plugin action Test
Description: Plugin action test description
Author: test name
Version: 1.0
 */

function post_footer($content) {
    $blog_name = get_bloginfo( 'name' );

    $blog = get_bloginfo();
    error_log($blog);

    if (is_single()) {
        $content .= '<p>Terima kasih sudah membaca di' . esc_html($blog) . '</p>';
    }

    return $content;
}

add_filter( 'the_content', 'post_footer' );
