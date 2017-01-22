/**
 * Created by arimolzer on 12/1/17.
 */

var PostStatsModule = (function (api) {

    return {
        loadPostStats: function (postId) {

            // Check if the modal has already been loaded, load it or show it.
            if (!$('#statistics-modal').length) {

                $('#preloader-modal').openModal();

                // Make AJAX request to get the posts statistics data
                api.postStatsModal(postId)
                    .then(function(data) {
                        $('#modal-container').append(data);
                        $('#preloader-modal').closeModal();
                        $('#statistics-modal').openModal();
                    })
                    .catch(function() {
                        // Revert the class if the AJAX request fails
                        $('#preloader-modal').closeModal();
                        Materialize.toast('Failed to load statistics.', 4000)
                    });
            } else {
                // Open existing statistics modal.
                $('#statistics-modal').openModal();
            }
        }
    }
})(APIModule);