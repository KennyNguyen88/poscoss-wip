angular.module('aboutService', [])
    .factory('aboutService',function($http){
        return {
            getEvent : function(){
                var events = [
                    {
                        date: "2010.05.18",
                        title: " PERMISSION ON INVESTMENT",
                        coverimg:"/assets/imgs/brief_07.jpg",
                        covercaption: "PERMISSION ON INVESTMENT",
                        items: [
                            {
                                img: "/assets/imgs/brief_08.jpg",
                                caption1: "PERMISSION ON INVESTMENT 1"
                            },
                            {
                                img: "/assets/imgs/brief_09.jpg",
                                caption1: "PERMISSION ON INVESTMENT 2"
                            }

                        ]
                    },
                    {
                        date: "2010.05.18",
                        title: " PERMISSION ON INVESTMENT",
                        coverimg:"/assets/imgs/brief_10.jpg",
                        covercaption: "PERMISSION ON INVESTMENT",
                        items: [
                            {
                                img: "/assets/imgs/brief_11.jpg",
                                caption1: "PERMISSION ON INVESTMENT 3"
                            },
                            {
                                img: "/assets/imgs/brief_12.jpg",
                                caption1: "PERMISSION ON INVESTMENT 4"
                            },
                            {
                                img: "/assets/imgs/brief_14.jpg",
                                caption1: "PERMISSION ON INVESTMENT 5"
                            },
                            {
                                img: "/assets/imgs/brief_15.jpg",
                                caption1: "PERMISSION ON INVESTMENT 6"
                            }

                        ]
                    }
                ];
                return events;
            }
        }
    })