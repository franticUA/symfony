;(function() {
    'use strict';

    /**
     *
     * @constructor
     */
    function Loader(container) {
        this.container = container;
    }

    Loader.prototype.show = function () {
        this.container.addClass('loader');
    };

    Loader.prototype.hide = function () {
        this.container.removeClass('loader');
    };

})();
