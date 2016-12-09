/**
 * Created by arimolzer on 9/12/16.
 */

var APIModule = (function () {

    var postLikeEndpoint = '/post/like/{id}';

    var likePost = function(postId) {
        return $.ajax({
            url: postLikeEndpoint.replace('{id}', postId)
        });
    };

    return {
        likePost: likePost
    };

})();