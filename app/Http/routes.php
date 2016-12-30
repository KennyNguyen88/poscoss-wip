<?php

Route::get('/', ['as' => 'wip-close','uses' => 'PageController@index']);

Route::get('/search/ifStatus/{fromDate}/{toDate}', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchIfStatus']);

Route::get('/search/oit', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchOIT']);

Route::get('/search/oit/check', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchOITCheck']);

Route::get('/step/{fromDate}/{toDate}', ['as' => 'wip-close-step','uses' => 'PageController@step']);

Route::get('/step/{id}/{fromDate}/{toDate}/{update}', ['as' => 'wip-close-step-id','uses' => 'PageController@step_id']);

Route::get('/ifNotSend/{fromDate}/{toDate}', ['as' => 'wip-close-ifNotSend','uses' => 'PageController@ifNotSend']);

Route::get('/ifNotSend/{chain}/{fromDate}/{toDate}', ['as' => 'wip-close-ifNotSend-detail','uses' => 'PageController@ifNotSendDetail']);

Route::get('/minus/{fromDate}', ['as' => 'wip-close-minus','uses' => 'PageController@minus']);

//Route::get('/prdRsl/{fromDate}', ['as' => 'wip-close-prdRsl','uses' => 'ProductionResultController@index']);

Route::get('/prdRsl', ['as' => 'wip-close-prdRsl-index','uses' => 'ProductionResultController@index']);

Route::get('/prdRsl/search/', ['as' => 'wip-close-prdRsl-search','uses' => 'ProductionResultController@search']);

//KPI

Route::get('/kpi', ['as' => 'kpi','uses' => 'KpiController@index']);

Route::get('/kpi/production/{chain}/{year}', ['as' => 'kpi-production','uses' => 'KpiController@production_result']);

Route::get('/kpi/material/{chain}/{year}', ['as' => 'kpi-material','uses' => 'KpiController@material_result']);

Route::get('/kpi/rework/{chain}/{year}', ['as' => 'kpi-rework','uses' => 'KpiController@rework_result']);

Route::get('/kpi/smp/heat/comm/01/{year}', ['as' => 'kpi-heat-comm-01','uses' => 'KpiController@smp_heat_comm_01']);

Route::get('/kpi/smp/heat/comm/02/{year}', ['as' => 'kpi-heat-comm-02','uses' => 'KpiController@smp_heat_comm_02']);

Route::get('/kpi/smp/heat/comm/03/{year}', ['as' => 'kpi-heat-comm-03','uses' => 'KpiController@smp_heat_comm_03']);

Route::get('/kpi/smp/mlt/rsl/01/{year}', ['as' => 'kpi-mlt-rsl-01','uses' => 'KpiController@smp_mtl_rsl_01']);

Route::get('/kpi/smp/mlt/rsl/02/{year}', ['as' => 'kpi-mlt-rsl-02','uses' => 'KpiController@smp_mtl_rsl_02']);

Route::get('/kpi/smp/mlt/rsl/03/{year}', ['as' => 'kpi-mlt-rsl-03','uses' => 'KpiController@smp_mtl_rsl_03']);
//ext id detail

Route::get('/test', ['as' => 'test','uses' => 'PageController@test']);

//mobile

Route::get('/mobile/onhand/{itemCd?}', ['as' => 'mobile-onhand','uses' => 'MobileController@getOnhandItems']);

Route::get('/mobile/cycleCnt/subInventory/{subInventory?}', ['as' => 'mobile-cycleCnt-subInventory','uses' => 'MobileController@getSubInventoryList']);

Route::get('/mobile/cycleCnt/detail/{subInventory}', ['as' => 'mobile-cycleCnt-detail','uses' => 'MobileController@getCycleCntDetail']);

Route::get('/mobile/transaction/detail/{inventoryItemId}', ['as' => 'mobile-transaction-detail','uses' => 'MobileController@getTransactionDetail']);



