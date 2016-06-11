;(function() {
    'use strict';

    window.app = new Application(function() {
        var Request             = app.module('classes.Request'),
            Messanger           = app.module('classes.Messanger'),
            RepositoriesFactory = app.module('classes.RepositoriesFactory');

        // console.log(Request);
        // console.log(Messanger);
        // console.log(RepositoriesFactory);

        app
            .service('messanger', Messanger)
            .service('request', Request)
            .service('repositoryFactory', RepositoriesFactory, 'request');

        $('[data-module]').each(function() {
            var $element = $(this);
            app.createModule(
                $element.data('module'),
                $element,
                $element.data()
            );
        });
    });

})();
