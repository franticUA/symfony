;(function(app) {
    'use strict';

    app.module('repositories.User', User);

    function User(request) {
        this.request = request;
    }

    User.prototype.register = function (data) {
        this.request.make({
            method: 'POST',
            data: data
        })
    };

})(window.app);
