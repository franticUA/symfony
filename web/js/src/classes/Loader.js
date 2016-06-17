;(function(app) {
    'use strict';

    app.module('classes.Loader', Loader);

    /**
     *
     * @constructor
     */
    function Loader(container) {
        this.container = container;
    }

    Loader.prototype.show = function () {
        this.container.addClass('waiting');
    };

    Loader.prototype.hide = function () {
        this.container.removeClass('waiting');
    };

})(window.app || {});
