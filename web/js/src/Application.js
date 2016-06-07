;(function(namespace) {
    'use strict';

    namespace.Application = Application;

    function Application(initCallback) {
        this._modules = {};
        $(initCallback || $.noop);
    }

    function _spliteNamespace(namespace) {
        return namespace.split('.');
    }

    Application.prototype.module = function(namespace, module) {
        return module
                ? this.setModule(namespace, module)
                : this.getModule(namespace);
    };

    Application.prototype.getModule = function(namespace) {
        var module = this._modules;
        _spliteNamespace(namespace).forEach(function(part) {
            module = module ? module[part] : null;
        });

        return module || null;
     };

    Application.prototype.setModule = function(namespace, module) {
        var current = this._modules,
            parts = _spliteNamespace(namespace);

        parts.forEach(function(part, index) {
            var value = (index >= parts.length - 1) ? module : {};
            current[part] = current[part] || value;
            current = current[part];
        });
    }

    Application.prototype.service = function(serviceName) {
        return this;        
    };

})(window);
