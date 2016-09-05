@extends('site.master.page_master')

@section('title', 'WIP CLOSE')
@section('style')
    @parent
    {{--append style--}}
@endsection

@section('content')
    <section id="control-box" class="container">
        <div class="row">
            <form id="productionResult" method="get" class="col m10 offset-m1" action="{{route('wip-close-prdRsl-search')}}">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col m3 s6">
                                {{--From Date --}}
                                <label for="fromDate">From Date</label>
                                <input type="date" class="datepicker" id="fromDate" name="fromDate" value="<?php echo date('Y-m-01'); ?>">
                            </div>
                            <div class="col m3 s6">
                                <button id="" type="submit" class="waves-effect waves-light btn">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="">

        <div class="row">
            <div class="col m10 offset-m1">
                <h4 class="white-text">Production Result</h4>
                <div class="card">
                    <table class="bordered">
                        <tbody>
                        <tr>
                            <td colspan="2" rowspan="3">Item</td>
                            <td colspan="7" class="center-align">Semi Product</td>
                            <td colspan="3" class="center-align">Finish Good</td>
                            <td colspan="2" class="center-align">Shipping</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="right-align">ERP</td>
                            <td colspan="3" class="center-align">MES</td>
                            <td colspan="2" class="center-align">ERP</td>
                            <td class="right-align">MES</td>
                            <td class="right-align">ERP</td>
                            <td class="right-align">MES</td>
                        </tr>
                        <tr>
                            <td class="right-align">Prod.Qty</td>
                            <td colspan="2" class="center-align">Input</td>
                            <td class="right-align">Yield</td>
                            <td class="right-align">Prod.Qty</td>
                            <td colspan="2" class="center-align">Input</td>
                            <td class="right-align">Receipt</td>
                            <td class="right-align">Return</td>
                            <td class="right-align">Receipt</td>
                            <td class="right-align">Issue</td>
                            <td class="right-align">Issue</td>
                        </tr>
                        <tr>
                            <td rowspan="4" class="left-align">SMP</td>
                            <td rowspan="3" class="left-align">Normal</td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][2]->prod_qty)) { echo $results['SMP_Normal_Semi_ERP'][2]->prod_qty; } else { echo 0; }?></td>
                            <td class="left-align">Scrap</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][2]->iss_qty)) { echo $results['SMP_Normal_Semi_ERP'][2]->iss_qty; } else { echo 0; }?></td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][2]->yield)) { echo $results['SMP_Normal_Semi_ERP'][2]->yield; } else { echo 0; }?></td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['SMP_Normal_Semi_MES_ProdQty'][0]->total)) { echo $results['SMP_Normal_Semi_MES_ProdQty'][0]->total; } else { echo 0; }?></td>
                            <td class="left-align">Scrap</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_MES_Input_Scrap'][0]->total)) { echo $results['SMP_Normal_Semi_MES_Input_Scrap'][0]->total; } else { echo 0; }?></td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['FG_Shipping_ERP'][3]->total)) { echo $results['FG_Shipping_ERP'][3]->total; } else { echo 0; }?></td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['FG_Shipping_ERP'][4]->total)) { echo $results['FG_Shipping_ERP'][4]->total; } else { echo 0; }?></td>
                            <td rowspan="3" class="right-align"><?php if(isset($results['FG_Shipping_MES'][2]->fg_receipt)) { echo $results['FG_Shipping_MES'][2]->fg_receipt; } else { echo 0; }?></td>
                            <td rowspan="4" class="right-align"><?php if(isset($results['FG_Shipping_ERP'][5]->total)) { echo $results['FG_Shipping_ERP'][5]->total; } else { echo 0; }?></td>
                            <td rowspan="4" class="right-align"><?php if(isset($results['FG_Shipping_MES'][2]->fg_shipping)) { echo $results['FG_Shipping_MES'][2]->fg_shipping; } else { echo 0; }?></td>
                        </tr>
                        <tr>
                            <td class="left-align">Ferro</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP_Input'][0]->total)) { echo $results['SMP_Normal_Semi_ERP_Input'][0]->total; } else { echo 0; }?></td>
                            <td class="left-align">Ferro</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_MES_Input_Ferro'][0]->total)) { echo $results['SMP_Normal_Semi_MES_Input_Ferro'][0]->total; } else { echo 0; }?></td>
                        </tr>
                        <tr>
                            <td class="left-align">Ore</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP_Input'][1]->total)) { echo $results['SMP_Normal_Semi_ERP_Input'][1]->total; } else { echo 0; }?></td>
                            <td class="left-align">Ore</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_MES_Input_Ore'][0]->total)) { echo $results['SMP_Normal_Semi_MES_Input_Ore'][0]->total; } else { echo 0; }?></td>
                        </tr>
                        <tr>
                            <td class="left-align">Rework</td>
                            <td class="right-align">x</td>
                            <td colspan="2" class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td colspan="2" class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][2]->fg_return)) { echo $results['FG_Shipping_MES'][2]->fg_return; } else { echo 0; }?></td>

                        </tr>
                        <tr>
                            <td rowspan="2" class="left-align">Section</td>
                            <td class="left-align">Normal</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][1]->prod_qty)) { echo $results['SMP_Normal_Semi_ERP'][1]->prod_qty; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][1]->iss_qty)) { echo $results['SMP_Normal_Semi_ERP'][1]->iss_qty; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][1]->yield)) { echo $results['SMP_Normal_Semi_ERP'][1]->yield; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Section_Normal_Semi_MES'][0]->prd_wgt)) { echo $results['Section_Normal_Semi_MES'][0]->prd_wgt; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Section_Normal_Semi_MES'][0]->inp_wgt)) { echo $results['Section_Normal_Semi_MES'][0]->inp_wgt; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][6]->total)) { echo $results['FG_Shipping_ERP'][6]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][7]->total)) { echo $results['FG_Shipping_ERP'][7]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][1]->fg_receipt)) { echo $results['FG_Shipping_MES'][1]->fg_receipt; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][8]->total)) { echo $results['FG_Shipping_ERP'][8]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][1]->fg_shipping)) { echo $results['FG_Shipping_MES'][1]->fg_shipping; } else { echo 0; }?></td>
                        </tr>
                        <tr>

                            <td class="left-align">Rework</td>
                            <td class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][1]->prod_qty)) { echo $results['Section_Rework_Semi_ERP'][1]->prod_qty; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][1]->iss_qty)) { echo $results['Section_Rework_Semi_ERP'][1]->iss_qty; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][1]->yield)) { echo $results['Section_Rework_Semi_ERP'][1]->yield; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Section_Rework_Semi_MES'][0]->prd_wgt)) { echo $results['Section_Rework_Semi_MES'][0]->prd_wgt; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Section_Rework_Semi_MES'][0]->inp_wgt)) { echo $results['Section_Rework_Semi_MES'][0]->inp_wgt; } else { echo 0; }?></td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][1]->fg_return)) { echo $results['FG_Shipping_MES'][1]->fg_return; } else { echo 0; }?></td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="left-align">Rebar</td>
                            <td class="left-align">Normal</td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][0]->prod_qty)) { echo $results['SMP_Normal_Semi_ERP'][0]->prod_qty; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][0]->iss_qty)) { echo $results['SMP_Normal_Semi_ERP'][0]->iss_qty; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['SMP_Normal_Semi_ERP'][0]->yield)) { echo $results['SMP_Normal_Semi_ERP'][0]->yield; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Rebar_Normal_Semi_MES'][0]->prd_wgt)) { echo $results['Rebar_Normal_Semi_MES'][0]->prd_wgt; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Rebar_Normal_Semi_MES'][0]->inp_wgt)) { echo $results['Rebar_Normal_Semi_MES'][0]->inp_wgt; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][0]->total)) { echo $results['FG_Shipping_ERP'][0]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][1]->total)) { echo $results['FG_Shipping_ERP'][1]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][0]->fg_receipt)) { echo $results['FG_Shipping_MES'][0]->fg_receipt; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_ERP'][2]->total)) { echo $results['FG_Shipping_ERP'][2]->total; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][0]->fg_shipping)) { echo $results['FG_Shipping_MES'][0]->fg_shipping; } else { echo 0; }?></td>
                        </tr>
                        <tr>

                            <td class="left-align">Rework</td>
                            <td class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][0]->prod_qty)) { echo $results['Section_Rework_Semi_ERP'][0]->prod_qty; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][0]->iss_qty)) { echo $results['Section_Rework_Semi_ERP'][0]->iss_qty; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Section_Rework_Semi_ERP'][0]->yield)) { echo $results['Section_Rework_Semi_ERP'][0]->yield; } else { echo 0; }?></td>
                            <td class="right-align"><?php if(isset($results['Rebar_Rework_Semi_MES'][0]->prd_wgt)) { echo $results['Rebar_Rework_Semi_MES'][0]->prd_wgt; } else { echo 0; }?></td>
                            <td colspan="2" class="right-align"><?php if(isset($results['Rebar_Rework_Semi_MES'][0]->inp_wgt)) { echo $results['Rebar_Rework_Semi_MES'][0]->inp_wgt; } else { echo 0; }?></td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                            <td class="right-align"><?php if(isset($results['FG_Shipping_MES'][0]->fg_return)) { echo $results['FG_Shipping_MES'][0]->fg_return; } else { echo 0; }?></td>
                            <td class="right-align">x</td>
                            <td class="right-align">x</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
            </div>
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
    </section>
@endsection

@section('script')
    @parent
@endsection