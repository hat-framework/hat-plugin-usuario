'use strict';
var usuario_messageApp = angular.module('usuario_messageApp', []).
filter('fromNow', function() {
  return function(date) {
    return moment(date).fromNow();
  };
});
