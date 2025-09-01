<?php
/*
Plugin Name: Test Plugin
Plugin URI: http://www.google.com
Description: Test Plugin Description
Version: 1.0
Author: Test name
Author URI: http://www.google.com
 */

if (! defined('ABSPATH')) exit;

function on_greeting($atts) {
    // $atts = shortcode_atts( ['first_name' => 'Dunia', 'last_name' => 'World'], $atts );

    $first_name = esc_html($atts['first_name']);
    $last_name = $atts['last_name'];

    return '<p>' . 'Hello ' . $first_name . ' ' .  $last_name . '!';
}

add_shortcode( 'greeting', 'on_greeting' );


