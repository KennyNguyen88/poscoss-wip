/**
 * Created by DucTrung on 3/18/2016.
 */
angular.module('homeController', [])
    .controller('homeController', function($scope, $http, homeService){
        $scope.products = {};
        $scope.news = {};

        //$scope.products = homeService.getProduct();
        //$scope.news = homeService.getNews();

        //get products
        homeService.getProduct()
            .success(function(data){
                $scope.products = data;
            });

        //get news
        homeService.getNew()
            .success(function(data){
                $scope.news = data;
                $scope.$broadcast('newloaded');
            });

    })