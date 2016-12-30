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
        bar_data.push([element.mon,element.total]);
    });
    //prepare html
    var apphtml = "<div class='col m8 offset-m2'><div id='bar_rework_plotplaceholder' class='diagram'></div>";
    $('#chart_content').append(apphtml);

    showSingle_bar("#bar_rework_plotplaceholder",[ bar_data ],"Rework (Scrarfing)");
}
function clearContent()
{
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


$(document).ready(function () {
    $('#action_SMP_Result').click(function() {
        clearContent();
        
        // showProductionResult('M20', '2016');
        // showMaterialResult('M20', '2016');
        // showReworkResult('M20', '2016');
    });
    
    $('#action_SMP_productionResult').click(function() {
        clearContent();
        showProductionResult('M20', '2016');
        // showMaterialResult('M20', '2016');
        // showReworkResult('M20', '2016');
    });

    $('#action_SMP_materialResult').click(function() {
        clearContent();
        // showProductionResult('M20', '2016');
        showMaterialResult('M20', '2016');
        // showReworkResult('M20', '2016');
    });

    $('#action_SMP_reworkResult').click(function() {
        clearContent();
        // showProductionResult('M20', '2016');
        // showMaterialResult('M20', '2016');
        showReworkResult('M20', '2016');
    });

});