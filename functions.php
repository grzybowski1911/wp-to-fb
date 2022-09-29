<?php 

// inject to functions php of theme

// callback function 

function wp_to_fb_meta_box_callback( $post ) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'saving_wp_fb_data', 'wp_to_fb_nonce' );
    $value = get_post_meta( $post->ID, '_wp_to_fb_data_meta_key', true );
    echo 
    '
        <textarea style="width:100%" id="wp_to_fb_data" name="wp_to_fb_data">' . esc_attr( $value ) . '</textarea>
        <input type="button" name="send-data" id="send-data" value="Send">
    ';
}

add_action( 'add_meta_boxes', 'wp_to_fb_meta_box' );

// save data in meta box 

function wp_to_fb_save_postdata( $post_id ) {
    $post = get_post( $post_id );
    if($post->post_type == 'wp-cpt') {
        if ( ! isset( $_POST['wp_to_fb_nonce'] ) ) {
            return $post_id;
        }
        if ( ! wp_verify_nonce( $_POST['wp_to_fb_nonce'], 'saving_wp_fb_data'  )) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post' ) ) {
            return $post_id;
        }
        $clean_data = sanitize_text_field( $_POST['wp_to_fb_data'] );
        update_post_meta($post_id, '_wp_to_fb_data_meta_key', $clean_data );
    }
}
add_action( 'save_post', 'wp_to_fb_save_postdata' );

// add function to ajax call 
// may not use 

function wp_to_fb_call() {
    //error_log('is this thing on');
}

add_action('wp_ajax_wp_to_fb', 'wp_to_fb_call');

// add fb sdk to admin page 

function add_fb_sdk() {
    // access current screen object to get cpt value
    // global $post variable not available in this context
    global $current_screen;
    if($current_screen->post_type == 'wp-cpt') {
        echo "
        <script>
        window.fbAsyncInit = function() {
            FB.init({
            appId            : '1204369433444241',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v15.0'
            });
        };
        </script>
        <script async defer crossorigin='anonymous' src='https://connect.facebook.net/en_US/sdk.js'></script>
        ";
    }
}

add_action('admin_footer', 'add_fb_sdk');