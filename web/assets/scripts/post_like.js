/**
 * Created by arimolzer on 9/12/16.
 */

var PostLikeModule = (function (api) {

    return {
        togglePostLike: function (postId) {

            var $postLike = $('#post_like');
            var $postLikeCount = $('#post_like_count');
            var $postLikeCountInt = parseInt($('#post_like_count').html());

            // Immediately add CSS class for instant user feedback
            if ($postLike.hasClass('red-text')) {
                $postLike.removeClass('red-text');
                $postLikeCount.html($postLikeCountInt - 1);
            } else {
                $postLike.addClass('red-text');
                $postLikeCount.html($postLikeCountInt + 1);
            }

            api.likePost(postId)
                .then(function() {
                    // success
                    console.log("Success");
                })
                .catch(function() {
                    // Revert the class if the AJAX request fails
                    console.log("Fail");
                    if ($postLike.hasClass('red-text')) {
                        $postLike.removeClass('red-text');
                        $postLikeCount.html($postLikeCountInt - 1);
                    } else {
                        $postLike.addClass('red-text');
                        $postLikeCount.html($postLikeCountInt + 1);
                    }
                });
        }
    }
})(APIModule);