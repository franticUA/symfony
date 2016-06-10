;(function(app) {
    'use strict';

    app.module('repositories.User', User);

    User.$inject = ['request'];

    function User(apiBase, request) {
        this.request = request;
        this.base = apiBase;
    }

    User.prototype.register = function(data) {
        return this.request.make({
            url: utils.format('/{$base}/auth/register', {
                base: this.base
            }),
            method: 'POST',
            data: data
        });
    };

    User.prototype.login = function(login, password) {
        return this.request.make({
            url: utils.format('/{$base}/auth/login', {
                base: this.base
            }),
            method: 'POST',
            data: {
                login: login,
                password: password
            }
        });
    };

})(window.app);
