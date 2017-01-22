/**
 * Created by arimolzer on 9/12/16.
 */

var APIModule = (function () {

    var postLikeEndpoint = '/post/like/{id}';
    var postBookmarkEndpoint = '/post/bookmark/{id}';
    var postStatsModalEndpoint = '/post/stats/{id}';

    var likePost = function(postId) {
        return $.ajax({
            url: postLikeEndpoint.replace('{id}', postId)
        });
    };

    var bookmarkPost = function(postId) {
        return $.ajax({
            url: postBookmarkEndpoint.replace('{id}', postId)
        });
    };

    var postStatsModal = function (postId) {
        return $.ajax({
            url: postStatsModalEndpoint.replace('{id}', postId)
        });
    };

    return {
        likePost: likePost,
        bookmarkPost: bookmarkPost,
        postStatsModal: postStatsModal
    };

})();