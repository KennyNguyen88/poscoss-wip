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
    // chain = 'M20';
    // year = '2016';
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
    // chain = 'M20';
    // year = '2016';
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
    // chain = 'M20';
    // year = '2016';
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
function getSMPHeatComm01(year){
    var url = "/public/kpi/smp/heat/comm/01";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPHeatComm02(year){
    var url = "/public/kpi/smp/heat/comm/02";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPHeatComm03(year){
    var url = "/public/kpi/smp/heat/comm/03";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPMtlRsl01(year){
    var url = "/public/kpi/smp/mlt/rsl/01";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPMtlRsl02(year){
    var url = "/public/kpi/smp/mlt/rsl/02";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPMtlRsl03(year){
    var url = "/public/kpi/smp/mlt/rsl/03";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
        async: false,
        success: function(data) {
            result = data;
        },
        type: 'GET'
    });
    return result;
}
function getSMPMtlRsl04(year){
    var url = "/public/kpi/smp/mlt/rsl/04";
    // year = '2016';
    var result = null;
    $.ajax({
        url: url + '/' + year,
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
            stack: 0,
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
function showSMPResult(year){

    var totalHeat, totalHeatStlGrd, totalHeatStlGrdInfo, totalHeatMonth, totalHeatMonthInfo;
    var totalWeightMax, totalWeightMaxInfo, totalWeightMin, totalWeightMinInfo;
    var totalQuantityMax, totalQuantityMaxInfo, totalQuantityMin, totalQuantityMinInfo;
    var totalDimWgtMax, totalDimWgtMaxInfo, totalDimWgtMin, totalDimWgtMinInfo;
    var totalDimQutMax, totalDimQutMaxInfo, totalDimQutMin, totalDimQutMinInfo;
    //get result
    var SMPHeatComm01_data = getSMPHeatComm01('2016');
    totalHeat = SMPHeatComm01_data[0].total;

    var SMPHeatComm02_data = getSMPHeatComm02('2016');
    totalHeatStlGrd = SMPHeatComm02_data[0].total;
    totalHeatStlGrdInfo = SMPHeatComm02_data[0].rsl_inco_stl_grd;

    var SMPHeatComm03_data = getSMPHeatComm03('2016');
    totalHeatMonth = SMPHeatComm03_data[0].total;
    totalHeatMonthInfo = monNumtoCh(SMPHeatComm03_data[0].mon.substr(4));

    var SMPMtlRsl01_data = getSMPMtlRsl01('2016');
    totalWeightMax = SMPMtlRsl01_data[0].total;
    totalWeightMaxInfo = SMPMtlRsl01_data[0].heat_no;
    totalWeightMin = SMPMtlRsl01_data[1].total;
    totalWeightMinInfo = SMPMtlRsl01_data[1].heat_no;

    var SMPMtlRsl02_data = getSMPMtlRsl02('2016');
    totalQuantityMax = SMPMtlRsl02_data[0].total;
    totalQuantityMaxInfo = SMPMtlRsl02_data[0].heat_no;
    totalQuantityMin = SMPMtlRsl02_data[1].total;
    totalQuantityMinInfo = SMPMtlRsl02_data[1].heat_no;

    var SMPMtlRsl03_data = getSMPMtlRsl03('2016');
    totalDimWgtMax = SMPMtlRsl03_data[0].total_wgt;
    totalDimWgtMaxInfo = SMPMtlRsl03_data[0].mtl_dims_cd;
    totalDimWgtMin = SMPMtlRsl03_data[SMPMtlRsl03_data.length-1].total_wgt;
    totalDimWgtMinInfo = SMPMtlRsl03_data[SMPMtlRsl03_data.length-1].mtl_dims_cd;

    var SMPMtlRsl04_data = getSMPMtlRsl04('2016');
    totalDimQutMax = SMPMtlRsl04_data[0].total_pcs;
    totalDimQutMaxInfo = SMPMtlRsl04_data[0].mtl_dims_cd;
    totalDimQutMin = SMPMtlRsl04_data[SMPMtlRsl04_data.length-1].total_pcs;
    totalDimQutMinInfo = SMPMtlRsl04_data[SMPMtlRsl04_data.length-1].mtl_dims_cd;
    
    //prepare html
    //
    // var apphtml = '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    // apphtml += '<div class="row"><div class="col m6 center-align"><h1>H</h1><p>HEAT</p></div>';
    // apphtml += '<div class="col m6"><p>'+totalHeat+'</p><p>Total</p><p>'+totalHeatStlGrd+'</p><p>'+totalHeatStlGrdInfo+'</p>';
    // apphtml += '<p>'+totalHeatMonth+'</p><p>'+totalHeatMonthInfo+'</p></div></div></div></div></div>';
    //
    // apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    // apphtml += '<div class="row"><div class="col m6 center-align"><h1>W</h1><p>WEIGHT</p></div>';
    // apphtml += '<div class="col m6"><p>'+totalWeightMax+'</p><p>'+totalWeightMaxInfo+'</p><p>'+totalWeightMin+'</p><p>'+totalWeightMinInfo+'</p><p></p><p></p></div></div></div></div></div>'
    //
    // apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    // apphtml += '<div class="row"><div class="col m6 center-align"><h1>Q</h1><p>QUANTITY</p></div>';
    // apphtml += '<div class="col m6"><p>'+totalQuantityMax+'</p><p>'+totalQuantityMaxInfo+'</p><p>'+totalQuantityMin+'</p><p>'+totalQuantityMinInfo+'</p><p></p><p></p>';
    // apphtml += '</div></div></div></div></div>';
    //
    // apphtml += '<div class="col m8 offset-m2"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    // apphtml += '<div class="row"><div class="col m4 center-align"><h1>D</h1><p>DIMENSION</p></div>';
    // apphtml += '<div class="col m4"><p>'+totalDimWgtMax+'</p><p>'+totalDimWgtMaxInfo+'</p><p>'+totalDimWgtMin+'</p><p>'+totalDimWgtMinInfo+'</p></div>';
    // apphtml += '<div class="col m4"><p>'+totalDimQutMax+'</p><p>'+totalDimQutMaxInfo+'</p><p>'+totalDimQutMin+'</p><p>'+totalDimQutMinInfo+'</p></div></div></div></div></div>';

    var apphtml = '<div class="col s12">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">H</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalHeat+'</h4>';
    apphtml+= '<p>Total</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s4">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">H</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalHeatStlGrd+'</h4>';
    apphtml+= '<p>'+totalHeatStlGrdInfo+'</p>';
    apphtml+= '<p>Steelgrade</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s4">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">H</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalHeatMonth+'</h4>';
    apphtml+= '<p>'+totalHeatMonthInfo+'</p>';
    apphtml+= '<p>Month</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">W</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalWeightMax+'</h4>';
    apphtml+= '<p>'+totalWeightMaxInfo+'</p>';
    apphtml+= '<p>MAX</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">W</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalWeightMin+'</h4>';
    apphtml+= '<p>'+totalWeightMinInfo+'</p>';
    apphtml+= '<p>MIN</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">Q</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalQuantityMax+'</h4>';
    apphtml+= '<p>'+totalQuantityMaxInfo+'</p>';
    apphtml+= '<p>MAX</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">Q</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalQuantityMin+'</h4>';
    apphtml+= '<p>'+totalQuantityMinInfo+'</p>';
    apphtml+= '<p>MIN</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">D</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalDimWgtMax+'</h4>';
    apphtml+= '<p>'+totalDimWgtMaxInfo+'</p>';
    apphtml+= '<p>Max Weight</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">D</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalDimWgtMin+'</h4>';
    apphtml+= '<p>'+totalDimWgtMinInfo+'</p>';
    apphtml+= '<p>Min Weight</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">D</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalDimQutMax+'</h4>';
    apphtml+= '<p>'+totalDimQutMaxInfo+'</p>';
    apphtml+= '<p>Max Quantities</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s6">';
    apphtml+= '<div class="card light-blue accent-4">';
    apphtml+= '<div class="card-content white-text container-fluid">';
    apphtml+= '<div class="row">';
    apphtml+= '<div class="col s4 center-align">';
    apphtml+= '<h2 class="border-right">D</h2>';
    apphtml+= '</div>';
    apphtml+= '<div class="col s8">';
    apphtml+= '<h4>'+totalDimQutMin+'</h4>';
    apphtml+= '<p>'+totalDimQutMinInfo+'</p>';
    apphtml+= '<p>Min Quantities</p>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';
    apphtml+= '</div>';

    $('#chart_content').append(apphtml);
}
function showRollingRebarResult(year){
    //prepare html
    var apphtml = '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>H</h1><p>HEAT</p></div>';
    apphtml += '<div class="col m6"><p>5399</p><p>Total</p><p>2736</p><p>DG300N</p>';
    apphtml += '<p>648</p><p>DEC</p></div></div></div></div></div>';

    apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>W</h1><p>WEIGHT</p></div>';
    apphtml += '<div class="col m6"><p>204081</p><p>V04830</p><p>1005</p><p>V05551</p><p></p><p></p></div></div></div></div></div>'

    apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>Q</h1><p>QUANTITY</p></div>';
    apphtml += '<div class="col m6"><p>84</p><p>V02431</p><p>1</p><p>V05551</p><p></p><p></p>';
    apphtml += '</div></div></div></div></div>';

    apphtml += '<div class="col m8 offset-m2"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m4 center-align"><h1>D</h1><p>DIMENSION</p></div>';
    apphtml += '<div class="col m4"><p>342768596</p><p>BT160</p><p>5123766</p><p>BT130</p></div>';
    apphtml += '    <div class="col m4"><p>142247</p><p>BT160</p><p>3247</p><p>BT130</p></div></div></div></div></div>';

    $('#chart_content').append(apphtml);
}
function showRollingSectionResult(year){
    //prepare html
    var apphtml = '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>H</h1><p>HEAT</p></div>';
    apphtml += '<div class="col m6"><p>5399</p><p>Total</p><p>2736</p><p>DG300N</p>';
    apphtml += '<p>648</p><p>DEC</p></div></div></div></div></div>';

    apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>W</h1><p>WEIGHT</p></div>';
    apphtml += '<div class="col m6"><p>204081</p><p>V04830</p><p>1005</p><p>V05551</p><p></p><p></p></div></div></div></div></div>'

    apphtml += '<div class="col m4"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m6 center-align"><h1>Q</h1><p>QUANTITY</p></div>';
    apphtml += '<div class="col m6"><p>84</p><p>V02431</p><p>1</p><p>V05551</p><p></p><p></p>';
    apphtml += '</div></div></div></div></div>';

    apphtml += '<div class="col m8 offset-m2"><div class="card light-blue accent-4"><div class="card-content white-text container-fluid">';
    apphtml += '<div class="row"><div class="col m4 center-align"><h1>D</h1><p>DIMENSION</p></div>';
    apphtml += '<div class="col m4"><p>342768596</p><p>BT160</p><p>5123766</p><p>BT130</p></div>';
    apphtml += '    <div class="col m4"><p>142247</p><p>BT160</p><p>3247</p><p>BT130</p></div></div></div></div></div>';

    $('#chart_content').append(apphtml);
}
function showProductionResult(chain, year){
    //get data
    var bar_data_good= [];
    var bar_data_bad= [];
    var pie_data_good = [];
    var pie_data_bad = [];
    var pre_bar_data = getProductionResult(chain,year);

    pre_bar_data.forEach(function(element) {
        // bar_data_good.push([element.mon,element.good]);
        // bar_data_bad.push([element.mon,element.bad]);
        // pie_data_good.push({label: element.mon,data: element.good});
        // pie_data_bad.push({label: element.mon,data: element.bad});
        bar_data_good.push([monNumtoCh(element.mon.substr(4)),element.good]);
        bar_data_bad.push([monNumtoCh(element.mon.substr(4)),element.bad]);
        pie_data_good.push({label: monNumtoCh(element.mon.substr(4)),data: element.good});
        pie_data_bad.push({label: monNumtoCh(element.mon.substr(4)),data: element.bad});
    });

    //prepare html
    var apphtml = "<div class='col m8'><div id='bar_good_plotplaceholder' class='diagram'></div></div><div class='col m4'><div id='pie_good_plotplaceholder' class='diagram pie'></div></div>";
         apphtml += "<div class='col m8'><div id='bar_bad_plotplaceholder' class='diagram'></div></div><div class='col m4'><div id='pie_bad_plotplaceholder' class='diagram pie'></div></div>";
    $('#chart_content').append(apphtml);

    //output diagram
    showSingle_bar("#bar_good_plotplaceholder",[ bar_data_good ],"Good Quantity");
    showSingle_bar("#bar_bad_plotplaceholder",[ bar_data_bad ],"Bad Quantity");
    showQuantity_pie("#pie_good_plotplaceholder",pie_data_good);
    showQuantity_pie("#pie_bad_plotplaceholder",pie_data_bad);
}
function showMaterialResult(chain, year){
    //get data
    var bar_data_good= [];
    var bar_data_minus= [];
    var bar_data_material = [];
    var production_bar_data = getProductionResult(chain,year);
    var material_bar_data = getMaterialResult(chain,year);

    production_bar_data.forEach(function(element) {
        bar_data_good.push([element.mon,element.good]);
    });

    material_bar_data.forEach(function(element,index) {
        bar_data_material.push([element.mon,element.total]);
        bar_data_minus.push([element.mon, element.total - bar_data_good[index][1]])
    });

    console.log(bar_data_minus);
    //prepare html
    var apphtml = "<div class='col m8 offset-m2'><div id='bar_yield_plotplaceholder' class='diagram'></div>";
    $('#chart_content').append(apphtml);
    
    showStack_bar("#bar_yield_plotplaceholder",[ bar_data_good, bar_data_minus],"Good Quantity / Input Scrap");

}
function showReworkResult(chain, year){
    //get data
    var bar_data= [];
    var rework_bar_data = getReworkResult(chain,year);
    rework_bar_data.forEach(function(element) {
        bar_data.push([monNumtoCh(element.mon.substr(4)),element.total]);
    });
    //prepare html
    var apphtml = "<div class='col m8 offset-m2'><div id='bar_rework_plotplaceholder' class='diagram'></div>";
    $('#chart_content').append(apphtml);

    showSingle_bar("#bar_rework_plotplaceholder",[ bar_data ],"Rework (Scrarfing)");
}
function clearContent(){
    $("#chart_content").html("");
}
//Convert Mon number to string
function monNumtoCh(monNum){
    switch (monNum) {
        case '01':            return 'JAN';
        case '02':            return  'FEB';
        case '03':            return  'MAR';
        case '04':            return  'APR';
        case '05':            return  'MAY';
        case '06':            return  'JUN';
        case '07':            return  'JUL';
        case '08':            return  'AUS';
        case '09':            return  'SEP';
        case '10':            return  'OCT';
        case '11':            return  'NOV';
        case '12':            return  'DEC';
        default: return '';
    }
}

function showTest()
{
    var url = "http://172.27.120.212:7777/M20/handle.do?ServiceName=M2030_2001Q-service&find=1";
    var result = null;
    $.ajax({
        url: url,
        async: false,
        //headers: 'X-Foo',
        success: function(data) {
            // result = data;
            console.log(data);
        },
        error: function(xhr){
            console.log(data);
        },
        type: 'GET'
    });
    return result;
}
$(document).ready(function () {
    $('#action_SMP_Result').click(function() {
        clearContent();
        showSMPResult('2016');
    });
    $('#action_SMP_productionResult').click(function() {
        clearContent();
        showProductionResult('M20', '2017');
    });
    $('#action_SMP_materialResult').click(function() {
        clearContent();
        showMaterialResult('M20', '2016');
    });
    $('#action_SMP_reworkResult').click(function() {
        clearContent();
        showReworkResult('M20', '2016');
    });
    $('#action_rol_re_Result').click(function() {
        clearContent();
        showRollingRebarResult('2016');
    });
    $('#action_rol_re_productionResult').click(function() {
        clearContent();
        showProductionResult('M30', '2016');
    });
    $('#action_rol_re_materialResult').click(function() {
        clearContent();
        showMaterialResult('M30', '2016');
    });
    $('#action_rol_re_reworkResult').click(function() {
        clearContent();
        showReworkResult('M30', '2016');
    });
    $('#action_rol_sec_Result').click(function() {
        clearContent();
        showRollingSectionResult('2016');
    });
    $('#action_rol_sec_productionResult').click(function() {
        clearContent();
        showProductionResult('M30', '2016');
    });
    $('#action_rol_sec_materialResult').click(function() {
        clearContent();
        showMaterialResult('M30', '2016');
    });
    $('#action_rol_sec_reworkResult').click(function() {
        clearContent();
        showReworkResult('M30', '2016');
    });
    $('#action_test').click(function() {
        showTest();
        // clearContent();
        // showReworkResult('M30', '2016');
    });
});