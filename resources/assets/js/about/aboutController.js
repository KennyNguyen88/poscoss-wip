angular.module('aboutController', [])
    .controller('aboutController', function($scope, $http, aboutService){
        $scope.events = aboutService.getEvent();
        //$scope.news = homeService.getNews();
    })