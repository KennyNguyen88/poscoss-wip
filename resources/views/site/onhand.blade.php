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
                                <a class="btn-floating red" href="{{route('onhand')}}">
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
    <section class="container">
        <div class="row">
            <div class="col m12 card-panel">
                <div class="right-title">
                    <span>On Hand</span>
                </div>
                <div class="row">
                    <div class="col m12" >
                        {{--<table class="bordered" id="onHandResult">--}}

                        {{--</table>--}}
                        <div id="bar_plotplaceholder" style="height: 300px"></div>

                        <div id="pie_plotplaceholder" style="height: 450px"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @parent
    <script type="text/javascript">


        $(document).ready(function () {
                // Bar Chart
//            var bar_data = [
//                ["January", 37559],
//                ["February", 13689],
//                ["March", 22726],
//                ["April", 34453],
//                ["May", 44996],
//                ["June", 53087],
//                ["July", 61017],
//                ["August", 58115],
//                ["September", 66086],
//                ["October", 68261],
//                ["November", 71072],
//                ["December", 64652]
//
//
//            ];


            var bar_data= [];
            var pre_bar_data = [
                {mon: "201601", total: 37559},
                {mon: "201602", total: 13689}

            ];
            pre_bar_data.forEach(function(element) {
                bar_data.push([element.mon,element.total]);
            });

            $.plot("#bar_plotplaceholder", [ bar_data ], {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center"
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });

                //Pie Chart default

            var pie_data = [
                { label: "January",  data: 37559},
                { label: "February",  data: 13689},
                { label: "March",  data: 22726},
                { label: "April",  data: 34453},
                { label: "May",  data: 44996},
                { label: "June",  data: 53087},
                { label: "July",  data: 61017},
                { label: "August",  data: 58115},
                { label: "September",  data: 66086},
                { label: "October",  data: 68261},
                { label: "November",  data: 71072},
                { label: "December",  data: 64652},
            ];

//            $.plot(placeholder, data, {
//                series: {
//                    pie: {
//                        show: true
//                    }
//                },
//                legend: {
//                    show: false
//                }
//            });

            $.plot("#pie_plotplaceholder", pie_data, {
                series: {
                    pie: {
                        innerRadius: 0.5,
                        show: true
                    }
                },
                legend: {
                    show: false
                }
            });




        });


    </script>
@endsection