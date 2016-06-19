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
     function RequestBuilder(request) {
         this.request = null;
         this.defaultOptions = {};
         this.options = [];
     }

     RequestBuilder.prototype._decorate = function(promise) {
         var promise = promise;
         $.each(this.options, function(index, option) {
             var decorator = options[0];
             var args = options[0].splite(1);
             args.unshift(promise);

             promise = decoator.call(args);
         }, this);

         return promise;
     }

     RequestBuilder.prototype.make = function(callback) {
         var promise = callback();
         promise = this._decorate(promise);
         return promise;
     };

     RequestBuilder.prototype.setLoader = function(isLoader, isHide) {
         this.options.push([Loader, isHide]);
        //  this.request = isLoader
        //                 ? new Loader(this.request, isHide)
        //                 : this.request;
     };

     RequestBuilder.prototype.setAutoHandler = function(isAutoHandler) {
         this.request = isErrorHandler
                        ? new ErrorHandler(this.request)
                        : this.request;
     };

     RequestBuilder.prototype.builder = function() {
         return this.request;
     };

     function LoaderHandler(promise) {
         showLoader();
          return promise.then(function() {
              hideLoader();
          });
     }

     function MessageHandler(promise) {
         return promise
             .then(function(result) {
                 if (result.message) {
                     alertify.success(result.message);
                 }

                 return result;
             })
             .fail(function(result) {
                 if (result.responseJSON &&
                     result.responseJSON.message
                 ) {
                     alertify.error(result.responseJSON.message);
                 }
                 else {
                     alertify.error('error');
                 }

                 return result;
             });
     }


})();
