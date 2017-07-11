@extends('site.master.page_master')

@section('title', 'WIP CLOSE')
@section('style')
    @parent
    {{--append style--}}
@endsection

@section('content')
    <section id="control-box" class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <form id="" method="get">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col s6 m3 ">
                                    <label for="fromDate">From Date</label>
                                    <input type="date" class="datepicker" id="fromDate" name="fromDate" value="<?php echo $data['fromDate']; ?>">
                                </div>{{--From Date --}}
                                <div class="col s6 m3 ">
                                    <label for="toDate">To Date</label>
                                    <input type="date" class="datepicker" id="toDate" name="toDate" value="<?php echo $data['toDate']; ?>">
                                </div>{{--To Date --}}
                                <div class="input-field col s6 m3 ">
                                    <select id="period">
                                        <option selected value="5000">5</option>
                                        <option value="10000">10</option>
                                        <option value="15000">15</option>
                                    </select>
                                    <label for="period">Period</label>
                                </div>
                                <div class="col s6 m3 ">
                                    <label for="refresh">Refresh</label>
                                    <div class="switch">
                                        <label>
                                            Off
                                            <input type="checkbox" id="refresh" name="refresh">
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action right-align">
                            <a class="modal-trigger hide-on-small-and-down" id="modal_IF_trigger" style="cursor: pointer">I/F</a>
                            <a class="modal-trigger hide-on-small-and-down" id="modal_minus_trigger" style="cursor: pointer">Minus Stock</a>
                            <button id="btnSearch" type="button" class="waves-effect waves-light btn">Search</button>
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
                                <li>
                                    <a class="btn-floating red" href="{{route('crosscheck-index')}}">
                                        <i class="large material-icons">list</i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="modal_IF" class="modal bottom-sheet">
                            <div class="modal-content">
                                <h4>I/F</h4>
                                <div class="row">
                                    <div class="col m6 offset-m3">
                                        <table class="bordered">
                                            <thead>
                                            <tr>
                                                <th class="">Chain</th>
                                                <th class="">Type</th>
                                                <th class="">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody id="if_not_send_result">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col m6 offset-m3">
                                        <table class="bordered">
                                            <thead>
                                            <tr>
                                                <th class="">EXT_ID</th>
                                                <th class="">PRD_DT</th>
                                                <th class="">TCT_TP_CD</th>
                                                <th class="">TRANRET</th>
                                            </tr>
                                            </thead>
                                            <tbody id="if_not_send_detail_result">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                            </div>
                        </div>
                        <div id="modal_minus" class="modal bottom-sheet">
                            <div class="modal-content">
                                <h4>Minus Stock</h4>
                                <div class="row">
                                    <div class="col m6 offset-m3">
                                        <table class="bordered">
                                            <thead>
                                            <tr>
                                                <th class="">Item Code</th>
                                                <th class="">Onhand</th>
                                                <th class="">Pending</th>
                                                <th class="">Var</th>
                                            </tr>
                                            </thead>
                                            <tbody id="minus_stock_result">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>
    <section class="container">
            <div class="row">
                <div class="col s12 m12">
                    <div class="card-panel">
                        <div class="right-title">
                            <span>I/F Status</span>
                        </div>
                        <div class="row" id="ifStatusResult">
                        </div>
                    </div>
                </div>
                <div class="col s12 m12">
                    <div class="card-panel">
                        <div class="right-title">
                            <span >OIT</span>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <table class="bordered responsive-table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th data-field="MtlTrx" class="center-align">Mtl.Trx</th>
                                        <th data-field="MtlLotTrx" class="center-align">Mtl.Lot.Trx</th>
                                        <th data-field="WipJob" class="center-align">Wip.Job</th>
                                        <th data-field="MovTrx" class="center-align">Mov.Trx</th>
                                        <th data-field="MtlLotTrx" class="center-align">Resources</th>
                                        <th data-field="MtlTemp" class="center-align">Mtl.Temp</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td><b>Pending</b></td>
                                        <td class="center-align" id="oit_1"></td>
                                        <td class="center-align" id="oit_3"></td>
                                        <td class="center-align" id="oit_5"></td>
                                        <td class="center-align" id="oit_7"></td>
                                        <td class="center-align" id="oit_9"></td>
                                        <td class="center-align" id="oit_11"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Error</b></td>
                                        <td class="center-align" id="oit_2"></td>
                                        <td class="center-align" id="oit_4"></td>
                                        <td class="center-align" id="oit_6"></td>
                                        <td class="center-align" id="oit_8"></td>
                                        <td class="center-align" id="oit_10"></td>
                                        <td class="center-align" id="oit_12"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="card-panel">
                        <div class="right-title"><span>Step</span><a id="btnStep" class="btn white-text waves-effect waves-green">Refresh</a></div>
                        <table class="bordered">
                            <thead>
                            <tr>
                                <th data-field="Step">Step</th>
                                <th data-field="StepDesc">Step Description</th>
                                <th data-field="Total">Total</th>
                            </tr>
                            </thead>

                            <tbody id="stepResult">
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="col s12 m12 l6 offset-l1">
                    <div class="card-panel">
                        <div class="right-title"><span>History</span><!--a href="#" class="btn white-text waves-effect waves-green">Export</a--></div>
                        <table class="bordered">
                            <thead>
                            <tr>
                                <th data-field="Step">Time</th>
                                <th data-field="StepDesc">Step Name</th>
                                <th data-field="Total">Total</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($data['history'] as $htr)
                                <tr>
                                    <td>{{$htr['time']}}</td>
                                    <td>{{$htr['stepname']}}</td>
                                    <td>{{$htr['rowcnt']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
    </section>
@endsection

@section('script')
    @parent
@endsection