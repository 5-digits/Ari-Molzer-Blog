/**
 * Created by arimolzer on 12/12/16.
 */

var PostBookmarkModule = (function (api) {

    return {
        togglePostBookmark: function (postId) {

            var $bookMark = $('#post_bookmark');
            var $bookMarkLabel = $('#post_bookmark_label');

            // Immediately add CSS class for instant user feedback
            if ($bookMark.hasClass('bookmarked')) {
                $bookMark.removeClass('bookmarked');
                $bookMarkLabel.text('bookmark_border');

            } else {
                $bookMark.addClass('bookmarked');
                $bookMarkLabel.text('bookmark');
            }

            api.bookmarkPost(postId)
                .then(function() {
                    // success
                    console.log("Added to bookmarks");
                })
                .catch(function() {
                    // Revert the class if the AJAX request fails
                    console.log("Failed to add to bookmarks");
                    if ($bookMark.hasClass('bookmarked')) {
                        $bookMark.removeClass('bookmarked');
                        $bookMarkLabel.text('bookmark_border');
                    } else {
                        $bookMarkLabel.text('bookmark');
                    }
                });
        }
    }
})(APIModule);