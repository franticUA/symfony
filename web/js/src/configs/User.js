;(function(app) {
    'use strict';

    app.module('configs.User', function() {
        var options = window.applicationOptions || {};

        function isLogined() {
            return options.user.id != null;
        }

        return {
            isLogined: isLogined
        }

    });

})(window.app || {});
