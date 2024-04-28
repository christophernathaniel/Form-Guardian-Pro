<?php
/*
Plugin Name: Form Guardian
Description: A plugin to handle forms
Version: 1.0.0
Author: Christopher Nathaniel
Author URI: https://christophernathaniel.co.uk
*/

// if accessed directly, exit
if (!defined('ABSPATH')) {
    exit;
}

// Register Post Type
function form_guardian_post_type()
{
    $args = array(
        'public' => true,
        'label'  => 'Forms',
        'supports' => array('title', 'editor'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'form-guardian'),
    );
    register_post_type('form-guardian', $args);
}
add_action('init', 'form_guardian_post_type');


// Register Shortcode
function form_guardian_shortcode($atts)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'id' => null, // Default to null
    ), $atts, 'form_guardian_shortcode');

    // Get custom page content based on provided page ID
    $custom_page_content = '';
    if ($atts['id']) {
        $page = get_post($atts['id']);
        if ($page) {
            $custom_page_content = apply_filters('the_content', $page->post_content);
        }
    }

    return $custom_page_content;
}
add_shortcode('form_guardian', 'form_guardian_shortcode');

// Usage: [form_guardian page_id="1"]


// Display Shortcode Example
// Function to insert example shortcode into page content in the CMS
function insert_shortcode_example_into_page_editor($content)
{
    global $post;

    // Check if we are on the page editor screen
    if ($post->post_type === 'form-guardian') { // Replace 'your-page-slug' with the slug of your page

        $theContent = '<div class="shortcode-example">
            <p>Below is an example of the custom page shortcode:</p>
            <p>[form_guardian id="' . $post->ID . '"]</p> <!-- Replace "123" with the actual ID of your custom page -->
        </div>';

        echo $theContent;
    }
}
add_filter('edit_form_after_title', 'insert_shortcode_example_into_page_editor');
