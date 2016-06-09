angular.module('homeService', [])
    .factory('homeService',function($http){
        return {
            getProduct : function(){
                        //var products = [
                        //    {
                        //        link: "http://localhost/PoscoSS_Redesign/poscoss/src/public/product/billet",
                        //        img: "/assets/imgs/steel_hot_billets_rod.jpg",
                        //        caption:"Billet"
                        //    },
                        //    {
                        //        link: "http://localhost/PoscoSS_Redesign/poscoss/src/public/product/section",
                        //        img: "/assets/imgs/Section.jpg",
                        //        caption:"Bloom"
                        //    },
                        //    {
                        //        link: "http://localhost/PoscoSS_Redesign/poscoss/src/public/product/beam",
                        //        img: "/assets/imgs/Deformed_Steel_Bars.jpg",
                        //        caption:"Beam Blank"
                        //    }
                        //];
                //return products;
                return $http.get('/home/products');
            },
            getNew : function(){
                      //var news = [
                      //    {
                      //        link:"#",
                      //        img:"/assets/imgs/maxresdefault.jpg",
                      //        headline:"[2016-01-05] we product materials for high...",
                      //        subheadline:"",
                      //        position:"left-align"
                      //    },
                      //    {
                      //        link:"#",
                      //        img:"/assets/imgs/maxresdefault.jpg",
                      //        headline:"[2017-01-05] we product materials for high...",
                      //        subheadline:"",
                      //        position:"right-align"
                      //    }
                      //];
                //return news;
                return $http.get('/home/news');
            }
        }
    })