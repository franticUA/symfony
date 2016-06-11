;(function(app) {
    'use strict';

    app.module('classes.RepositoriesFactory', RepositoriesFactory);

    RepositoriesFactory.$inject = ['request'];

    function RepositoriesFactory(request) {
        this.request = request;
    }

    RepositoriesFactory.prototype.get = function(repositoryName) {
        switch (repositoryName) {
            case 'Article':
                var ArticleRepository = app.module('repositories.Article');
                return new ArticleRepository(this.request);
                break;
            default:
                return null;
        }
    };

    // function RepositorisFactoryCachingProxy(repositoriesFactory) {
    //     this.repositoriesFactory = repositoriesFactory;
    //     this._cache = {};
    // }
    //
    // RepositorisFactoryCachingProxy.prototype.get = function(repositoryName) {
    //     if (!this.cache[repositoryName]) {
    //         this.cache[repositoryName] = this.repositoriesFactory.get(repositoryName)
    //     }
    //     return this.cache[repositoryName];
    // }


})(window.app || {});
