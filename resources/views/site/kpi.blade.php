@extends('site.master.page_master')

@section('title', 'WIP CLOSE')
@section('style')
    @parent
    {{--append style--}}
@endsection

@section('content')
    <section id="control-box-onhand" class="container" style="display: none;">
        <div class="row">
            <form id="" method="get" class="col m8 offset-m2">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col m3">
                                <label for="dateCheck">Check Date</label>
                                <input type="date" class="datepicker" id="dateCheck" name="checkDate" value="<?php echo date('Y-m-01'); ?>">

                            </div>
                            <div class="input-field col m3">
                                <input placeholder="Input Heat No / Lot No" id="lotno" type="text">
                                <label for="lotno">Heat/Lot</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-action right-align">
                        <button id="btnSearchOnHandM20" type="button" class="waves-effect waves-light btn">M20</button>
                        <button id="btnSearchOnHandM30" type="button" class="waves-effect waves-light btn">M30</button>
                        <button id="btnSearchOnHandM60" type="button" class="waves-effect waves-light btn">M60</button>
                    </div>
                    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
                        <a class="btn-floating btn-large blue" href="{{route('wip-close')}}">
                            <i class="large material-icons">mode_edit</i>
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating red" href="{{route('kpi')}}">
                                    <i class="large material-icons">list</i>
                                </a>
                            </li>
                            <li>
                                <a class="btn-floating red" href="{{route('wip-close-prdRsl-index')}}">
                                    <i class="large material-icons">info_outline</i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="container-fluid">
        <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-large blue" href="{{route('wip-close')}}">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li>
                    <a class="btn-floating red" href="{{route('kpi')}}">
                        <i class="large material-icons">list</i>
                    </a>
                </li>
                <li>
                    <a class="btn-floating red" href="{{route('wip-close-prdRsl-index')}}">
                        <i class="large material-icons">info_outline</i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col m2 card-panel">
                <div class="right-title">
                    <span>Control Box</span>
                </div>
                <div>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header"><i class="material-icons">filter_drama</i>Production Result</div>
                            <div class="collapsible-body">
                                <div class="collection">
                                    <a href="#!" class="collection-item" id="action_SMP_productionResult">SMP</a>
                                    {{--<a href="#!" class="collection-item">Rolling - Rebar</a>--}}
                                    {{--<a href="#!" class="collection-item">Rolling - Section</a>--}}
                                    {{--<a href="#!" class="collection-item">Shipping - Rebar</a>--}}
                                    {{--<a href="#!" class="collection-item">Shipping - Section</a>--}}
                                </div>
                            </div>
                        </li>
                        {{--<li>--}}
                            {{--<div class="collapsible-header"><i class="material-icons">place</i>Second</div>--}}
                            {{--<div class="collapsible-body"><p>Lorem ipsum dolor sit amet.</p></div>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>--}}
                            {{--<div class="collapsible-body"><p>Lorem ipsum dolor sit amet.</p></div>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
            <div class="col m9 card-panel" style="margin-left: 20px">
                <div class="right-title">
                    <span>KPI</span>
                </div>
                <div class="row">
                    <div class="col m6">
                        {{--<h4>Good Quantity</h4>--}}
                        <div id="bar_good_plotplaceholder" style="height: 300px"></div>
                    </div>
                    <div class="col m6">
                        {{--<h4>Scrap Quantity</h4>--}}
                        <div id="bar_bad_plotplaceholder" style="height: 300px"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col m6">
                        {{--<h4>Good Quantity / Input Scrap</h4>--}}
                        <div id="bar_yield_plotplaceholder" style="height: 450px"></div>
                    </div>
                    <div class="col m6">
                        {{--<h4>Rework (Scrarfing)</h4>--}}
                        <div id="bar_rework_plotplaceholder" style="height: 450px"></div>
                    </div>
                </div>

                {{--<div class="row">--}}
                    {{--<div class="col m6">--}}
                        {{--<div id="pie_good_plotplaceholder" style="height: 450px"></div>--}}
                    {{--</div>--}}
                    {{--<div class="col m6">--}}
                        {{--<div id="pie_bad_plotplaceholder" style="height: 450px"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}


            </div>
        </div>
    </section>
@endsection

@section('script')
    @parent
    <script type="text/javascript" src="/resources/assets/js/shared/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/resources/assets/js/shared/flot/jquery.flot.categories.min.js"></script>
    <script type="text/javascript" src="/resources/assets/js/shared/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" src="/resources/assets/js/shared/flot/jquery.flot.stack.min.js"></script>

    <script type="text/javascript">
        var posco_blue = "#00578A";
        var posco_light_blue = "#00A7E5";
        // Bar Chart [ [x1,y1] , [x2,y2] ]
            // var bar_data = [
            //     ["January", 37559],
            //     ["February", 13689]
            //            ];
            //  $.plot("#bar_plotplaceholder", [ bar_data ], {
            //      series: {
            //          bars: {
            //              show: true,
            //              barWidth: 0.6,
            //              align: "center"
            //          }
            //      },
            //      xaxis: {
            //          mode: "categories",
            //          tickLength: 0
            //      }            //  });

        // Bar Chart Stacked [ [[x1,y1] , [x2,y2]] , [[x1,y1] , [x2,y2]] , [[x1,y1] , [x2,y2]] ]
            // $.plot("#bar_plotplaceholder", [ bar_data_good, bar_data_bad], {
            //     series: {
            //         stack: 0,
            //         bars: {
            //             show: true,
            //             barWidth: 0.6
            //         }
            //     }
            // });

        // Pie Chart [ {label: "text1", data: number1},{label: "text2", data: number2} ]
            //   var pie_data = [
            //       { label: "January",  data: 37559},
            //       { label: "February",  data: 13689}
            //   ];
            //   $.plot("#pie_plotplaceholder", pie_data, {
            //       series: {
            //           pie: {
            //               innerRadius: 0.5,
            //               show: true
            //           }
            //       },
            //       legend: {
            //           show: false
            //       }
            //   });

        function getProductionResult(chain,year){ // return [{"mon":"201601","good":"37559","bad":"233"},{"mon":"201602","good":"13689","bad":"111"}]
        var url = "/public/kpi/production";
        chain = 'M20';
        year = '2016';
        var result = null;
        $.ajax({
            url: url + '/' + chain + '/' + year,
            async: false,
            success: function(data) {
                result = data;
            },
            type: 'GET'
        });
        return result;
    }
        function getMaterialResult(chain,year){ // return [{"mon":"201601","total":"37559"},{"mon":"201602","total":"13689"}]
            var url = "/public/kpi/material";
            chain = 'M20';
            year = '2016';
            var result = null;
            $.ajax({
                url: url + '/' + chain + '/' + year,
                async: false,
                success: function(data) {
                    result = data;
                },
                type: 'GET'
            });
            return result;
        }
        function getReworkResult(chain,year){ // return [{"mon":"201601","total":"37559"},{"mon":"201602","total":"13689"}]
            var url = "/public/kpi/rework";
            chain = 'M20';
            year = '2016';
            var result = null;
            $.ajax({
                url: url + '/' + chain + '/' + year,
                async: false,
                success: function(data) {
                    result = data;
                },
                type: 'GET'
            });
            return result;
        }
        function showSingle_bar(id_container, data,title){
            $.plot(id_container, data, {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center",
                        fillColor: posco_light_blue,
                        lineWidth: 0
                    },
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });
            $("<h4>"+title+"</h4>").insertBefore(id_container);
        }
        function showStack_bar(id_container, data,title){
            $.plot(id_container, data, {
                series: {
                    stack: 1,
                    bars: {
                        show: true,
                        barWidth: 0.6
                    }
                }
            });
            $("<h4>"+title+"</h4>").insertBefore(id_container);
        }
        function showQuantity_pie(id_container, data){
            $.plot(id_container, data, {
                series: {
                    pie: {
                        innerRadius: 0.5,
                        show: true
                    },

                },
                legend: {
                    show: false
                },
//               colors: [ "#FFDC2E", "#E8023A", "#0F81FF", "#8EE832", "#FF6102" ]
            });
        }
        function showProductionResult(chain, year){
            var bar_data_good= [];
            var bar_data_bad= [];
            var pie_data_good = [];
            var pie_data_bad = [];

            var pre_bar_data = getProductionResult(chain,year);

            pre_bar_data.forEach(function(element) {
                bar_data_good.push([element.mon,element.good]);
                bar_data_bad.push([element.mon,element.bad]);
                pie_data_good.push({label: element.mon,data: element.good});
                pie_data_bad.push({label: element.mon,data: element.bad});
            });
            showSingle_bar("#bar_good_plotplaceholder",[ bar_data_good ],"Good Quantity");
            showSingle_bar("#bar_bad_plotplaceholder",[ bar_data_bad ],"Bad Quantity");
//            showQuantity_pie("#pie_good_plotplaceholder",pie_data_good);
//            showQuantity_pie("#pie_bad_plotplaceholder",pie_data_bad);
        }
        function showMaterialResult(chain, year){
            var bar_data_good= [];
            var bar_data_material = [];
            var production_bar_data = getProductionResult(chain,year);
            var material_bar_data = getMaterialResult(chain,year);

            production_bar_data.forEach(function(element) {
                bar_data_good.push([element.mon,element.good]);
            });

            material_bar_data.forEach(function(element) {
                bar_data_material.push([element.mon,element.total]);
            });
            showStack_bar("#bar_yield_plotplaceholder",[ bar_data_good, bar_data_material],"Good Quantity / Input Scrap");
        }
        function showReworkResult(chain, year){
            var bar_data= [];
            var rework_bar_data = getReworkResult(chain,year);
            rework_bar_data.forEach(function(element) {
                bar_data.push([element.mon,element.total]);
            });
            showSingle_bar("#bar_rework_plotplaceholder",[ bar_data ],"Rework (Scrarfing)");
        }
        $(document).ready(function () {

            $('#action_SMP_productionResult').click(function() {
                showProductionResult('M20', '2016');
                showMaterialResult('M20', '2016');
                showReworkResult('M20', '2016');
            });

        });


    </script>
@endsection