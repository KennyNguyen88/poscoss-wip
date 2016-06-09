@extends('site.master.page_master')

@section('title', 'WIP CLOSE')
@section('style')
    @parent
    {{--append style--}}
@endsection

@section('content')
    <section id="control-box-onhand" class="container">
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
                        <a class="btn-floating btn-large blue" >
                            <i class="large material-icons">mode_edit</i>
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating red" href="{{route('wip-close')}}">
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
                        <table class="bordered" id="onHandResult">

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @parent
@endsection