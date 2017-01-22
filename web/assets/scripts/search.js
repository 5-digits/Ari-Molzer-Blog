var searchModule = (function(api) {

    // ---------------
    // --- Helpers ---
    // ---------------

    // Timer
    var typingTimer;

    // Define intentional latency for search
    var doneTypingInterval = 800;

    // Define a number of relevant DOM elements - for ease
    var $searchInput = $('#search');
    var $searchClose = $('#search-close');
    var $searchResults = $('#search-results');
    var $searchContainer = $('#nav-search');

    // ------------------------
    // --- Private Methods ---
    // -----------------------

    var keyUpSearchStart = function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(requestSearchResults, doneTypingInterval);

        // Check if the input is empty, and remove search results if it is
        if (!$searchInput.val()) {
            $searchResults.empty();
            $searchResults.hide();
        }
    };

    // User is finished typing, search for results relating to their request
    var requestSearchResults = function() {

        // Search for posts relating to the search term
        $.getJSON("{{ url('searchTermEndpoint') }}/" + $searchInput.val(), function (data) {

            // Make the empty search results visible on the DOM
            $searchResults.show();

            // Empty the search results
            $searchResults.empty();

            // Check if the AJAX call returned any results and return a message
            // if there are none.
            if (data.length === 0) {

                // Add a message to let the user know
                $('<li class="collection-item orange-text"><h5 class="title">No results found</h5></li></a>').prependTo($searchResults);

                // Remove the message after 3 seconds
                setTimeout(function () {
                    $searchResults.fadeOut('slow');
                    $searchResults.empty();
                }, 3000)

                // Break out
                return false;
            }

            // Loop through the results and add them to the DOM
            $.each(data, function (index, value) {
                $('<a class="collection-item avatar orange-text" href="' + value['url'] + '"><img src="/uploads/posts/' + value['headerImage'] + '" class="circle"><span class="title">' + value['title'] + '</span><p>' + value['subtitle'] + '</p></a>').prependTo($searchResults).slideDown();
            });
        });
    }


    // On keyup, start the countdown
    $searchInput.on('keyup', keyUpSearchStart());

    // On keydown, clear the countdown
    $searchInput.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    // When the user clicks the X button in the input
    // clear all of the relevant input values
    $searchClose.click(function () {
        $searchInput.val(null);
        $searchResults.hide();
        $searchResults.empty();
    });

    return {
        startSearch: function () {
            // do something
        }
    };


})(APIModule);