;(function(app) {
    'use strict';

    app.module('classes.PopupManager', PopupManager);

    function PopupManager(container) {
        this._container = container;
        this._stack = [];
        this._current = null;
    }

    PopupManager.prototype.open = function(id, options) {
        var selector = utils.format('[data-id={$id}]', {id: id});

        this._current = this._container.find(selector);
        this._current.addClass('active');
    };

    PopupManager.prototype.close = function() {
        this._container
            .find('[data-role=popup]')
            .removeClass('active');

        this._current = this.getFromStack();
    };

})(window.app || {});
