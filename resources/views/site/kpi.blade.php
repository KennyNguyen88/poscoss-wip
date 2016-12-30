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
                    </ul>
                </div>
            </div>
            <div class="col m9 card-panel" style="margin-left: 20px">
                <div class="right-title">
                    <span id="kpi_content">KPI</span>
                </div>
                <div class="row" id="chart_content">

                        {{--<img src="/resources/assets/imgs/shutterstock_149459636.jpg" alt="" class="img-responsive">--}}

                    <div class="col m4">
                        <div class="card light-blue accent-4">
                            <div class="card-content white-text container-fluid">
                                <div class="row">
                                    <div class="col m6 center-align">
                                        <h1>H</h1><p>HEAT</p>
                                    </div>
                                    <div class="col m6">
                                        <p>5399</p>
                                        <p>Total</p>
                                        <p>2736</p>
                                        <p>DG300N</p>
                                        <p>648</p>
                                        <p>DEC</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col m4">
                        <div class="card light-blue accent-4">
                            <div class="card-content white-text container-fluid">
                                <div class="row">
                                    <div class="col m6 center-align">
                                        <h1>W</h1><p>WEIGHT</p>
                                    </div>
                                    <div class="col m6">
                                        <p>204081</p>
                                        <p>V04830</p>
                                        <p>1005</p>
                                        <p>V05551</p>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col m4">
                        <div class="card light-blue accent-4">
                            <div class="card-content white-text container-fluid">
                                <div class="row">
                                    <div class="col m6 center-align">
                                        <h1>Q</h1><p>QUANTITY</p>
                                    </div>
                                    <div class="col m6">
                                        <p>84</p>
                                        <p>V02431</p>
                                        <p>1</p>
                                        <p>V05551</p>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col m6 offset-m2">
                        <div class="card light-blue accent-4">
                            <div class="card-content white-text container-fluid">
                                <div class="row">
                                    <div class="col m4 center-align">
                                        <h1>D</h1><p>DIMENSION</p>
                                    </div>
                                    <div class="col m4">
                                        <p>342768596</p>
                                        <p>BT160</p>
                                        <p>5123766</p>
                                        <p>BT130</p>
                                    </div>
                                    <div class="col m4">
                                        <p>142247</p>
                                        <p>BT160</p>
                                        <p>3247</p>
                                        <p>BT130</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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