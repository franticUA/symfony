;(function(app) {
    'use strict';

    app.module('repositories.Article', Article);

    Article.$inject = ['request'];

    function Article(request) {
        this.request = request;
    }

    Article.prototype.like = function(id) {
        return this.request.make({
            method: 'GET',
            url: utils.format('/api/article/{$id}/like/1', {id: id})
        });
    };

})(window.app || {});
