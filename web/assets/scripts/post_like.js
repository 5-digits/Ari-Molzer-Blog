/**
 * Created by arimolzer on 9/12/16.
 */

var PostLikeModule = (function (api) {

    return {
        togglePostLike: function (postId) {

            var $post = $('#post_like');

            // Immediately add CSS class for instant user feedback
            if ($post.hasClass('red-text')) {
                $post.removeClass('red-text');
            } else {
                $post.addClass('red-text');
            }

            api.likePost(postId)
                .then(function() {
                    // success
                    console.log("Success");
                })
                .catch(function() {
                    // Revert the class if the AJAX request fails
                    console.log("Fail");
                    if ($post.hasClass('red-text')) {
                        $post.removeClass('red-text');
                    } else {
                        $post.addClass('red-text');
                    }
                });
        }
    }
})(APIModule);