<?php
/**
 * Theme setup and resource enqueuing.
 *
 * @package PropertyTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function property_theme_setup() {
    // Add dynamic title tag support for SEO
    add_theme_support( 'title-tag' );

    // Enable featured images
    add_theme_support( 'post-thumbnails' );

    // Register primary nav menu
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'property-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'property_theme_setup' );

function property_theme_enqueue_assets() {
    // Google Fonts: Open Sans
    wp_enqueue_style( 'google-font-open-sans', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap', array(), null );
    
    // Bootstrap 5 CSS
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
    
    // Font Awesome 6 (FIXED: Changed all.min.min.css to all.min.css)
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
    
    // Theme Main Stylesheet
    wp_enqueue_style( 'property-theme-style', get_stylesheet_uri(), array( 'bootstrap-css' ), '1.0' );
    
    // Bootstrap 5 JS Bundle
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true );
}
add_action( 'wp_enqueue_scripts', 'property_theme_enqueue_assets' );

/* Bootstrap 5 Nav Walker for WP Menus */
function property_theme_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'property_theme_nav_menu_link_attributes', 10, 3 );


/*Register Custom Post Type: Jobs*/

function custom_register_jobs_cpt() {
    $labels = array(
        'name'               => __( 'Jobs', 'my-custom-theme' ),
        'singular_name'      => __( 'Job', 'my-custom-theme' ),
        'menu_name'          => __( 'Jobs', 'my-custom-theme' ),
        'all_items'          => __( 'All Jobs', 'my-custom-theme' ),
        'add_new_item'       => __( 'Add New Job', 'my-custom-theme' ),
        'add_new'            => __( 'Add New', 'my-custom-theme' ),
        'new_item'           => __( 'New Job', 'my-custom-theme' ),
        'edit_item'          => __( 'Edit Job', 'my-custom-theme' ),
        'update_item'        => __( 'Update Job', 'my-custom-theme' ),
        'view_item'          => __( 'View Job', 'my-custom-theme' ),
        'view_items'         => __( 'View Jobs', 'my-custom-theme' ),
    );

    $supports = array(
        'title',        
        'editor',      
        'excerpt',     
        'author',    
        'thumbnail',
        'revisions',
        'custom-fields'
    );

    $args = array(
        'label'               => __( 'Jobs', 'my-custom-theme' ),
        'description'         => __( 'Post type Jobs', 'my-custom-theme' ),
        'labels'              => $labels,
        'supports'            => $supports,
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-businessperson',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type( 'job', $args ); 
}
add_action( 'init', 'custom_register_jobs_cpt', 0 );


/* Add Meta Box for Custom Fields (Job Title, Salary, Location) */
function custom_jobs_add_meta_box() {
    add_meta_box(
        'jobs_details_meta_box',         
        __( 'Job Details', 'textdomain' ),  
        'custom_jobs_render_meta_box',    // Callback function to render fields
        'job',        
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'custom_jobs_add_meta_box' );

/* Render Meta Box Fields in Admin Panel */
function custom_jobs_render_meta_box( $post ) {
    // Add nonce for security
    wp_nonce_field( 'custom_jobs_save_meta', 'custom_jobs_meta_nonce' );

    // Retrieve existing field values if present
    $job_title = get_post_meta( $post->ID, '_job_title', true );
    $salary    = get_post_meta( $post->ID, '_job_salary', true );
    $location  = get_post_meta( $post->ID, '_job_location', true );
    ?>
    <style>
        .job-meta-field { margin-bottom: 15px; }
        .job-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .job-meta-field input { width: 100%; max-width: 400px; }
    </style>

    <div class="job-meta-field">
        <label for="job_title"><?php _e( 'Job Title', 'textdomain' ); ?></label>
        <input type="text" id="job_title" name="job_title" value="<?php echo esc_attr( $job_title ); ?>" />
    </div>

    <div class="job-meta-field">
        <label for="job_salary"><?php _e( 'Salary', 'textdomain' ); ?></label>
        <input type="text" id="job_salary" name="job_salary" value="<?php echo esc_attr( $salary ); ?>" placeholder="e.g., $80,000 / year" />
    </div>

    <div class="job-meta-field">
        <label for="job_location"><?php _e( 'Location', 'textdomain' ); ?></label>
        <input type="text" id="job_location" name="job_location" value="<?php echo esc_attr( $location ); ?>" placeholder="e.g., New York, NY / Remote" />
    </div>
    <?php
}

/* Save Custom Field Meta Data */
function custom_jobs_save_meta_box_data( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['custom_jobs_meta_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['custom_jobs_meta_nonce'], 'custom_jobs_save_meta' ) ) {
        return;
    }

    // Stop auto-saves from overwriting data
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check user permissions
    if ( isset( $_POST['post_type'] ) && 'job' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Sanitize and save/update fields
    if ( isset( $_POST['job_title'] ) ) {
        update_post_meta( $post_id, '_job_title', sanitize_text_field( $_POST['job_title'] ) );
    }

    if ( isset( $_POST['job_salary'] ) ) {
        update_post_meta( $post_id, '_job_salary', sanitize_text_field( $_POST['job_salary'] ) );
    }

    if ( isset( $_POST['job_location'] ) ) {
        update_post_meta( $post_id, '_job_location', sanitize_text_field( $_POST['job_location'] ) );
    }
}
add_action( 'save_post_job', 'custom_jobs_save_meta_box_data' );