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
        'label'  => 'Form Guardian',
        'supports' => array('title', 'editor'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'form-guardian'),
    );
    register_post_type('form-guardian', $args);
}
add_action('init', 'form_guardian_post_type');


// Disable Visual Editor
function disable_editor($default)
{
    global $post;

    if ($post->post_type === 'form-guardian') {
        return false;
    }

    return $default;
}
add_filter('user_can_richedit', 'disable_editor');




// Register Shortcode
function form_guardian_shortcode($atts)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'id' => null, // Default to null
    ), $atts, 'form_guardian_shortcode');

    // Get custom page content based on provided page ID
    $form_content = '';
    if ($atts['id']) {
        $page = get_post($atts['id']);
        if ($page) {
            $form_content = apply_filters('the_content', $page->post_content);
        }
    }

    $theme = get_post_meta($atts['id'], '_form_guardian_theme', true);


    return '<div class="form-guardian form-guardian-theme-' . $theme . '"><form action="' . esc_url(home_url("/form-guardian_submission/")) . '" method="post" id="form_submit_' . $atts['id'] . '">'
        . '<input type="hidden" name="form_guardian_submission" value="1"><input type="hidden" name="form_id" value="' . $atts['id'] . '">'
        . $form_content . '<div class="form_guardian--footer"><input type="submit" name="submit_form" value="Submit" /></div></form></div>';
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
            <p>[form_guardian id="' . $post->ID . '"]</p>
        </div>';

        echo $theContent;
    }
}
add_filter('edit_form_after_title', 'insert_shortcode_example_into_page_editor');











// Add meta box for 'form-guardian' custom post type
function add_form_guardian_meta_box()
{
    add_meta_box(
        'form_guardian_metabox',
        'Form Guardian Settings',
        'render_form_guardian_meta_box',
        'form-guardian',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes_form-guardian', 'add_form_guardian_meta_box');



// Callback function to render meta box
function render_form_guardian_meta_box($post)
{
    // Retrieve existing meta values
    $selected_page_id = get_post_meta($post->ID, '_form_guardian_page_id', true);
    $theme = get_post_meta($post->ID, '_form_guardian_theme', true);
    $to_email = get_post_meta($post->ID, '_form_guardian_to_email', true);
    $from_email = get_post_meta($post->ID, '_form_guardian_from_email', true);
    $subject_email = get_post_meta($post->ID, '_form_guardian_subject', true);
    $message_body = get_post_meta($post->ID, '_form_guardian_message', true);
    // Add nonce field for security
    wp_nonce_field('form_guardian_save_meta_box', 'form_guardian_meta_box_nonce');
?>

    <label for="form_guardian_theme">Select a Theme:</label><br>


    <select id="form_guardian_theme" name="form_guardian_theme">
        <option value="no-theme" <?php if ($theme == 'no-theme') {
                                        echo 'selected';
                                    } ?>>No Theme</option>
        <option value="basic" <?php if ($theme == 'basic') {
                                    echo 'selected';
                                } ?>>Basic Theme</option>
    </select>


    <label for="form_guardian_page_id">Select Thank You Page:</label><br>


    <?php wp_dropdown_pages(array(
        'name'             => 'form_guardian_page_id',
        'id'               => 'form_guardian_page_id',
        'selected'         => $selected_page_id,
        'show_option_none' => __('&mdash; Select a page &mdash;'),
    )); ?>
    <br><br>

    <label for="form_guardian_from_email">From</label><br>
    <input type="email" name="form_guardian_from_email" id="form_guardian_from_email" value="<?php echo esc_attr($from_email); ?>" />


    <label for="form_guardian_to_email">To</label><br>
    <input type="email" name="form_guardian_to_email" id="form_guardian_to_email" value="<?php echo esc_attr($to_email); ?>" /><br><br>


    <label for="form_guardian_subject">Subject</label><br>
    <input type="text" name="form_guardian_subject" id="form_guardian_subject" value="<?php echo esc_attr($subject_email); ?>" /><br><br>

    <label for="form_guardian_message">Subject</label><br>
    <textarea type="email" name="form_guardian_message" id="form_guardian_message" value="<?php echo esc_attr($message_body); ?>"><?php echo esc_attr($message_body); ?></textarea>
<?php
}




// Save meta box data
function save_form_guardian_meta_box($post_id)
{
    // Check if nonce is set
    if (!isset($_POST['form_guardian_meta_box_nonce']) || !wp_verify_nonce($_POST['form_guardian_meta_box_nonce'], 'form_guardian_save_meta_box')) {
        return;
    }

    // Check user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save meta box data
    if (isset($_POST['form_guardian_page_id'])) {
        update_post_meta($post_id, '_form_guardian_page_id', intval($_POST['form_guardian_page_id']));
    }

    if (isset($_POST['form_guardian_theme'])) {
        update_post_meta($post_id, '_form_guardian_theme', sanitize_text_field($_POST['form_guardian_theme']));
    }

    if (isset($_POST['form_guardian_to_email'])) {
        update_post_meta($post_id, '_form_guardian_to_email', sanitize_email($_POST['form_guardian_to_email']));
    }

    if (isset($_POST['form_guardian_from_email'])) {
        update_post_meta($post_id, '_form_guardian_from_email', sanitize_email($_POST['form_guardian_from_email']));
    }

    if (isset($_POST['form_guardian_subject'])) {
        update_post_meta($post_id, '_form_guardian_subject', sanitize_text_field($_POST['form_guardian_subject']));
    }

    if (isset($_POST['form_guardian_message'])) {
        update_post_meta($post_id, '_form_guardian_message', sanitize_text_field($_POST['form_guardian_message']));
    }
}
add_action('save_post_form-guardian', 'save_form_guardian_meta_box');





// Register custom endpoint
function register_form_guardian_submission_endpoint()
{
    add_rewrite_rule('^form-guardian_submission/?$', 'index.php?form_guardian_submission=1', 'top');
}
add_action('init', 'register_form_guardian_submission_endpoint');

// Handle form submission for the custom endpoint
add_action('template_redirect', 'handle_form_guardian_submission');
function handle_form_guardian_submission()
{

    if (isset($_POST['form_guardian_submission'])) {
        $form_guardian_submission_value = $_POST['form_guardian_submission'];
        var_dump($form_guardian_submission_value);
    }

    if (isset($_POST['form_guardian_submission'])) {
        // Handle form submission
        if (isset($_POST['submit_form'])) {
            // Process form submission
            $form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0; // Assuming the form ID is submitted along with the form

            // Construct email message
            $email_message = "Form ID: $form_id\n\n"; // Include the form ID in the email message

            // Iterate through all submitted form fields and include them in the email message
            foreach ($_POST as $field_name => $field_value) {
                // Exclude special fields like 'submit_form', 'form_id', and 'form_guardian_submission'
                if ($field_name !== 'submit_form' && $field_name !== 'form_id' && $field_name !== 'form_guardian_submission') {
                    // Sanitize field name and value
                    $sanitized_field_name = sanitize_text_field($field_name);
                    $sanitized_field_value = sanitize_text_field($field_value);

                    // Include field name and value in the email message
                    $email_message .= "$sanitized_field_name: $sanitized_field_value\n";
                }
            }

            // Send email
            $to_email = get_post_meta($form_id, '_form_guardian_to_email', true);
            $subject = get_post_meta($form_id, '_form_guardian_subject', true);
            $sent = wp_mail($to_email, $subject, $email_message);

            // Check if email was sent successfully
            if ($sent) {
                // Redirect after successful form submission
                wp_redirect(home_url('/thank-you/')); // Redirect to thank you page
                exit;
            } else {
                // Redirect after failed form submission
                wp_redirect(home_url('/error/')); // Redirect to error page
                exit;
            }
        }
    }
}



// Dynamically Enqueue CSS Based on Selected Theme
add_action('wp_enqueue_scripts', 'enqueue_all_form_css');
function enqueue_all_form_css()
{
    // Query for all form posts
    $form_posts = get_posts(array(
        'post_type' => 'form-guardian', // Assuming 'form-guardian' is your custom post type
        'posts_per_page' => -1, // Get all posts
    ));

    // Loop through each form post
    foreach ($form_posts as $form_post) {
        // Get the theme value for the current form
        $theme = get_post_meta($form_post->ID, '_form_guardian_theme', true);

        // Enqueue the corresponding CSS file
        enqueue_form_css($theme);
    }
}



// ENQUEUE CSS file based on the selected theme
function enqueue_form_css($theme)
{
    // Define the CSS file path based on the selected theme
    $css_file_path = plugin_dir_url(__FILE__) . 'themes/' . $theme . '.css';

    // Enqueue the CSS file
    wp_enqueue_style('form_theme_' . $theme, $css_file_path, array(), filemtime(plugin_dir_path(__FILE__) . 'themes/' . $theme . '.css'));
}

// Enqueue Admin CSS
add_action('admin_enqueue_scripts', 'enqueue_admin_css');
function enqueue_admin_css()
{
    // Define the admin CSS file path
    $admin_css_file_path = plugin_dir_url(__FILE__) . 'admin.css';

    // Enqueue the admin CSS file
    wp_enqueue_style('admin_css', $admin_css_file_path);
}




// ENQUEUE Javascript
add_action('admin_enqueue_scripts', 'enqueue_admin_js');
function enqueue_admin_js($hook)
{
    // Enqueue JavaScript only on the edit screen of the 'form-guardian' post type
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        global $post;
        if ($post && 'form-guardian' === $post->post_type) {
            // Define the JavaScript file path
            $admin_js_file_path = plugin_dir_url(__FILE__) . 'admin.js';

            // Enqueue the JavaScript file
            wp_enqueue_script('admin_js', $admin_js_file_path, array('jquery'), null, true);
        }
    }
}
