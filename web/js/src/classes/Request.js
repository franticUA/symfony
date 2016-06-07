;(function(app, $) {
    'use strict';

    app.module('classes.Request', Request);

    function Request() {
        this._defaults = {
            method: 'GET'
        };
    }

    Request.prototype.setDefaults = function(defaults) {
        this._defaults = $.extend(true, this._defaults, defaults);
    }

    Request.prototype.make = function(options) {
        var options = $.extend(true, this._defaults, options);
        return $.ajax(options);
    };

})(window.app || {}, window.$ || {});
