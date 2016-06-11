;(function() {
    'use strict';

    window.app = new Application(function() {
        var Request             = app.module('classes.Request'),
            Messanger           = app.module('classes.Messanger'),
            RepositoriesFactory = app.module('classes.RepositoriesFactory');

        app
            .service('messanger', Messanger)
            .service('request', Request)
            .service('repositoryFactory', RepositoriesFactory, 'request');

        $('body').on('click', '[data-ajax]', function(event) {
            event.preventDefault();

            var element = $(this);
            var params = {
                method: element.data('method') || 'GET',
                url: element.attr('href') || '',
                data: element.data()
            };

            delete params.data.ajax;
            delete params.data.method;

            var request = app.service('request');
            request.make(params)
                .fail(function(result) {
                    if (result.responseJSON &&
                        result.responseJSON.message
                    ) {
                        alertify.error(result.responseJSON.message);
                    }
                });
        });

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
