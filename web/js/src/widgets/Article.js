;(function(app, rivets) {
    'use strict';

    app.module('widgets.Article', Article);

    Article.$inject = ['repositoryFactory'];

    function Article($element, options, repositoryFactory) {
        var options = options || {};

        var vm = this;

        vm.repository = repositoryFactory.get('Article');
        vm.item = {
            id: options.id || null,
            rating: options.rating || 0,
            isLiked: options.isLiked || false,
            isFavorite:  options.isFavorite || false,
            comments: ''
        }

        vm.like = like;
        vm.addToFavorite = addToFavorite;
        vm.showCommets = showComments;

        activate();

        function activate() {
            rivets.bind($element, vm);
        }

        function like() {
            vm.repository.like(vm.item.id)
                .then(function(newRating) {
                    vm.item.rating = newRating;
                });
        }

        function addToFavorite() {
            vm.repository.addToFavorite(id)
                .then(function(status) {
                    item.isFavorite = status;
                });
        }

        function showComments() {
            vm.repository.getComments(id)
                .then(function(html) {
                    vm.item.comments = html;
                });
        }
    }

})(window.app || {}, window.rivets || {});
