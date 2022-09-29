(function($) {
    $( document ).ready(function() {
        $('#send-data').click(()=>{
            //console.log($('#wp_to_fb_data').val());
            // making an ajax call to custom function - may not use
            //$.ajax({
            //    url: "/wp-admin/admin-ajax.php",
            //    type: 'POST',
            //    data: 'action=wp_to_fb',
            //    success: () => {
            //        alert('success');
            //    }
            //})
            const sendThis = $('#wp_to_fb_data').val();
            FB.api(
                '/101258959404799/feed/',
                'POST',
                {"message":sendThis},
                function(response) {
                    console.log(response);
                }
            );
        });
    });
})( jQuery );

// example request to create a post 
// "https://graph.facebook.com/{page-id}/feed
//?message=Hello Fans!
//&link=add_a_link
//&access_token={page-access-token}"


// "(#200) If posting to a group, requires app being installed in the group, and 
// either publish_to_groups permission with user token, or both pages_read_engagement 
// and pages_manage_posts permission with page token; If posting to a page, 
// requires both pages_read_engagement and pages_manage_posts as an admin with 
// sufficient administrative permission"
