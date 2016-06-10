;(function() {
    'use strict';

    function Article(request) {
        this.request = request;
    }

    Artice.prototype.like = function(id) {
        return this.request.make({
            method: 'POST',
            url: format('/blog/article/{id}/like', {id: id})
        });
    };

})();
