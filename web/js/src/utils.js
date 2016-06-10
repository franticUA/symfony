(function() {
    'use strict';

    window.utils = {
        format: function(string, values) {
            var result = string;
            $.each(values, function(key, value) {
                result = result.replace('{$' + key + '}', value);
            });

            return result;
        }
    }

})();
