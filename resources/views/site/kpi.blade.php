@extends('site.master.page_master')

@section('title', 'KPI')
@section('style')
    @parent
    {{--append style--}}
@endsection

@section('content')
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
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 l3 card-panel">
                <div class="row">
                    <div class="col s12">
                        <div class="right-title">
                            <span>Control Box</span>
                        </div>
                        <form id="" method="get">
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s6">
                                            <label for="dateCheck">Check Date</label>
                                            <input type="date" class="datepicker" id="dateCheck" name="checkDate" value="<?php echo date('Y-m-01'); ?>">
                                        </div>
                                        <div class="col s6">
                                            <div class="input-field" style="margin-top: 23px">
                                                <input placeholder="Input Heat No / Lot No" id="lotno" type="text">
                                                <label for="lotno">Heat/Lot</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-action right-align">
                                    <button id="btnSearchOnHandM20" type="button" class="waves-effect waves-light btn">M20</button>
                                    <button id="btnSearchOnHandM30" type="button" class="waves-effect waves-light btn">M30</button>
                                    <button id="btnSearchOnHandM60" type="button" class="waves-effect waves-light btn">M60</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div>
                            <ul class="collapsible popout" data-collapsible="accordion">
                                <li>
                                    <div class="collapsible-header"><i class="material-icons">settings</i>SMP</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_SMP_Result">General</a>
                                            <a href="#!" class="collection-item" id="action_SMP_productionResult">Production</a>
                                            <a href="#!" class="collection-item" id="action_SMP_materialResult">Material</a>
                                            <a href="#!" class="collection-item" id="action_SMP_reworkResult">Rework</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="material-icons">settings</i>Rolling Rebar</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_rol_re_Result">General</a>
                                            <a href="#!" class="collection-item" id="action_rol_re_productionResult">Production</a>
                                            <a href="#!" class="collection-item" id="action_rol_re_materialResult">Material</a>
                                            <a href="#!" class="collection-item" id="action_rol_re_reworkResult">Rework</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="material-icons">settings</i>Rolling Section</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_rol_sec_Result">General</a>
                                            <a href="#!" class="collection-item" id="action_rol_sec_productionResult">Production</a>
                                            <a href="#!" class="collection-item" id="action_rol_sec_materialResult">Material</a>
                                            <a href="#!" class="collection-item" id="action_rol_sec_reworkResult">Rework</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="fa fa-ship" aria-hidden="true"></i>Shipping Rebar</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_ship_re_productionResult">undefined</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="fa fa-ship" aria-hidden="true"></i>Shipping Section</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_rol_sec_productionResult">undefined</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="fa fa-ship" aria-hidden="true"></i>Test</div>
                                    <div class="collapsible-body">
                                        <div class="collection">
                                            <a href="#!" class="collection-item" id="action_test">Test</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 l9 card-panel">
                <div class="right-title">
                    <span id="kpi_content">KPI</span>
                </div>
                <div class="row" >
                    <div class="col s12" id="chart_content">
                        <img src="/resources/assets/imgs/shutterstock_149459636.jpg" alt="" class="responsive-img">
                    </div>
                </div>
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
    <script type="text/javascript" src="/resources/assets/js/kpi.js"></script>
@endsection