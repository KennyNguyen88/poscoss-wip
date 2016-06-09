/**
 * Created by DucTrung on 3/18/2016.
 */
angular.module('pageController', [])
    .controller('pageController', function($scope, $http, pageService){
        $scope.sliders = {};
        $scope.home_navs = {};
        $scope.breadcrumbs = {};
        //get sliders by pageID
        pageService.getSlider(pageID)
            .success(function(data){
                $scope.sliders = data;
                $scope.$broadcast('sliderloaded');
            })

        //main navigation
        pageService.getMainNav(pageID)
            .success(function(data){
                $scope.home_navs = data;
            })
        ;
        //breadcrumb
        pageService.getBreadCrumb(pageID)
            .success(function(data){
                $scope.breadcrumbs = data;
            })
        ;
    })