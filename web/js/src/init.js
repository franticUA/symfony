;(function() {
    'use strict';

    window.app = new Application(function() {
        // service config
        var Request = app.module('classes.Request'),
            User = app.module('models.User');

        app
            .service('config', function() {


                return {
                    getUserId: function() {

                    }
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
