var app = angular.module('app', ["ngTouch", "angucomplete"], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });