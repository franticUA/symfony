;(function() {
    'use strict';

    /**
     * @module classes/RequestBuilder
     * @example
     * var request = new RequestBuilder()
     *                  .setLoader(true, false)
     *                  .setErrorHandler(false);
     *                  .build();
     *
     * request.make(function() {
     *      return player.login(email, password);
     * })
     *
     */


    /**
     * RequestBuilder - description
     *
     * @constructor
     * @param  {type} request description
     * @return {type}         description
     */
    function RequestBuilder(request) {
        this.request = request;
    }

    /**
     * RequestBuilder#setLoader - description
     *
     * @param  {type} isLoader description
     * @param  {type} isHide   description
     * @return {type}          description
     */
    RequestBuilder.prototype.setLoader = function(isLoader, isHide) {
        this.request = isLoader
                        ? new Loader(isHide)
                        : this.request;
    };

    RequestBuilder.prototype.setLoader = function(isErrorHandler, isHide) {
        this.request = isErrorHandler
                        ? new ErrorHandler(isHide)
                        : this.request;
    };

    RequestBuilder.prototype.builder = function() {
        return this.request;
    };

})();
