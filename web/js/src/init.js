;(function() {
    'use strict';

    window.app = new Application(function() {
        // service config
        var Request = app.module('classes.Request'),
            userConfig = app.module('configs.User');

        app
            .service('config', function() {
                return {
                    user: userConfig
                }
            })
            .service('request', function() {
                var request = new Request();

                request.setDefaults({

                });

                return request;
            });
    });

})();
