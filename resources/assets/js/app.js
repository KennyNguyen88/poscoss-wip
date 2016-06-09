var app =  angular.module(
    'app',
    ['ngMaterial',
        'ngSanitize',
        'pageController',
        'homeController',
        'aboutController',
        'pageService',
        'homeService',
        'aboutService',
        'ui.materialize'
    ],
    function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});