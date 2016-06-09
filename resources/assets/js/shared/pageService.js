angular.module('pageService', [])
.factory('pageService',function($http){
    return {
        getSlider : function(pageID){
            //var sliders = [
            //    {
            //        link:"#",
            //        img:"/assets/imgs/thump_1.jpg",
            //        headline:"This is our big Tagline 1!",
            //        subheadline:"Here's our small slogan 1.",
            //        position:"left-align"
            //    },
            //    {
            //        link:"#",
            //        img:"/assets/imgs/slide_1.jpg",
            //        headline:"This is our big Tagline 2!",
            //        subheadline:"Here's our small slogan 2.",
            //        position:"center-align"
            //    }
            //
            //];
            //return sliders;

            return $http.get('/sliders/' + pageID);
        },
        getMainNav : function(pageID){
            //var home_navs = [
            //    {
            //        link: "http://localhost/about",
            //        img: '<img src="/assets/imgs/POSCO-SSVINA_04.gif"/>',
            //        body: 'About',
            //        img_home: '<img src="/assets/imgs/POSCO-SSVINA_04.gif"/><h5>About</h5>',
            //        sub: [
            //            {
            //                link: "about",
            //                body: 'Overview'
            //            },
            //            {
            //                link: "about-brief",
            //                body: 'Brief'
            //            },
            //            {
            //                link: "about-certificate",
            //                body: 'Certificate'
            //            },
            //            {
            //                link: "about-qa",
            //                body: 'Q&A'
            //            },
            //            {
            //                link: "about-vision",
            //                body: 'Vision'
            //            }
            //        ]
            //    },
            //    {
            //        link: "http://localhost/process",
            //        img: '<i class="fa fa-cog"></i>',
            //        body: 'Process',
            //        img_home: '<i class="fa fa-cog"></i><h5>Process</h5>',
            //        sub: [
            //            {
            //                link: "#",
            //                body: 'Technology'
            //            },
            //            {
            //                link: "#",
            //                body: 'Steel Making'
            //            },
            //            {
            //                link: "#",
            //                body: 'Rebar Mill'
            //            },
            //            {
            //                link: "#",
            //                body: 'Section Mill'
            //            }
            //        ]
            //    },
            //    {
            //        link: "http://localhost/product",
            //        img: '<img src="/assets/imgs/Steel_Beam-icon.png"/>',
            //        body: 'Products',
            //        img_home: '<img src="/assets/imgs/Steel_Beam-icon.png"/><h5>Products</h5>',
            //        sub: [
            //            {
            //                link: "#",
            //                body: 'Billet'
            //            },
            //            {
            //                link: "#",
            //                body: 'Deform Bar'
            //            },
            //            {
            //                link: "#",
            //                body: 'Section Steel'
            //            },
            //            {
            //                link: "#",
            //                body: 'Q&A'
            //            }
            //        ]
            //    },
            //    {
            //        link: "http://localhost/project",
            //        img: '<i class="fa fa-building"></i>',
            //        body: 'Projects',
            //        img_home: '<i class="fa fa-building"></i><h5>Projects</h5>',
            //        sub: []
            //    },
            //    {
            //        link: "http://localhost/sale",
            //        img: '<i class="fa fa-line-chart"></i>',
            //        body: 'Sales',
            //        img_home: '<i class="fa fa-line-chart"></i><h5>Sales</h5>',
            //        sub: [
            //            {
            //                link: "#",
            //                body: 'Sale Contact'
            //            },
            //            {
            //                link: "#",
            //                body: 'Sale Policy'
            //            },
            //            {
            //                link: "#",
            //                body: 'Customer Service'
            //            },
            //            {
            //                link: "#",
            //                body: 'Distributor'
            //            }
            //        ]
            //    },
            //    {
            //        link: "http://localhost/public-new",
            //        img: '<i class="fa fa-comments-o"></i>',
            //        body: 'Public Relations',
            //        img_home: '<i class="fa fa-comments-o"></i><h5>Public<br>Relations</h5>',
            //        sub: [
            //            {
            //                link: "#",
            //                body: 'News Center'
            //            },
            //            {
            //                link: "#",
            //                body: 'Activities'
            //            }
            //        ]
            //    },
            //    {
            //        link: "http://localhost/contact",
            //        img: '<i class="fa fa-envelope-o"></i>',
            //        body: 'Contact',
            //        img_home: '<i class="fa fa-envelope-o"></i><h5>Contact</h5>',
            //        sub: [
            //            {
            //                link: "#",
            //                body: 'Contact Info'
            //            },
            //            {
            //                link: "#",
            //                body: 'Recruiment'
            //            },
            //            {
            //                link: "#",
            //                body: 'Q&A'
            //            }
            //        ]
            //    }
            //
            //];
            //return home_navs;
            return $http.get('/nav/' + pageID);
        },
        getBreadCrumb: function(pageID){
            //var bread_crumbs = [
            //    {
            //        link: "#",
            //        content: "First"
            //    },
            //    {
            //        link: "#",
            //        content: "Second"
            //    },
            //    {
            //        link: "#",
            //        content: "Third"
            //    }
            //
            //]
            return $http.get('/br/' + pageID);
            //return bread_crumbs;
        }

    }
})