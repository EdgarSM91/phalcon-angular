var front = angular.module("front",['ngRoute','ngMap','mgcrea.ngStrap']);

front.config(['$routeProvider','$locationProvider',function($routeProvider,$locationProvider){
    $routeProvider
        .when("/",{
            templateUrl:"/front/views/index/index.html",
            controller : "IndexController"
        })
        .when("/contactanos",{
            templateUrl:"/front/views/contact/contact.html",
            controller: "ContactController"
        })
        .otherwise({redirectTo:"/"});
    $locationProvider.html5Mode(true);
}]);