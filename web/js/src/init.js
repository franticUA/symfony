;(function() {
    'use strict';

    window.app = new Application(function() {
        var Request             = app.module('classes.Request'),
            Messanger           = app.module('classes.Messanger'),
            Loader              = app.module('classes.Loader'),
            PopupManager        = app.module('classes.PopupManager'),
            RepositoryFactory   = app.module('classes.RepositoriesFactory');

        app
            .service('messanger', Messanger)
            .service('request', Request)
            .service('repositoryFactory', RepositoryFactory)
            .factory('popupManager', function() {
                return new PopupManager($('[data-role=popups-container]'));
            })
            .factory('loader', function() {                
                return new Loader($('body'));
            });

        $('body').on('click', 'a[data-ajax]', function(event) {
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
            var loader  = app.service('loader');

            loader.show();

            request.make(params)
                .fail(function(result) {
                    if (result.responseJSON &&
                        result.responseJSON.message
                    ) {
                        alertify.error(result.responseJSON.message);
                    }
                    else {
                        alertify.error('error');
                    }
                })
                .always(function() {
                    loader.hide();
                });
        });

        $('body').on('submit', 'form[data-ajax]', function(event) {            
            event.preventDefault();

            var element = $(this);
            var params = {
                method: element.attr('method') || 'GET',
                url: element.attr('action') || '',
                data: element.serializeObject()
            };

            var request = app.service('request');
            request.make(params)
                .fail(function(result) {
                    if (result.responseJSON &&
                        result.responseJSON.message
                    ) {
                        alertify.error(result.responseJSON.message);
                    }
                    else {
                        alertify.error('error');
                    }
                });
        });

        $('[data-module]').each(function() {
            var element = $(this);
            app.createModule(
                element.data('module'),
                element,
                element.data()
            );
        });
    });

})();
