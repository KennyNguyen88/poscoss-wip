var timer;
function joblist(){
    alert('To be in Contruction !');
};
function getStatus()
{
    return $('#refresh').is(":checked");
}
function checkOIT()
{
    var url = "/public/search/oit/check";
    var tf = false;
    $.ajax({
        url: url,
        async: false,
        success: function(data) {
            if (parseInt(data[0]['total']) <= 0)
            {
                tf = true;
            }
        },
        type: 'GET'
    });;
    return tf;
}
function doUpdateStep_child(step)
{
    //confirm update action
    //get rows based on step
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var url = "/public/step";
    var cnt = 0;
    $.ajax({
        url: url + '/' + step + '/' + fromDate + '/' + toDate + '/' + '0',
        async: false,
        success: function(data) {
            cnt = data.length;
        },
        type: 'GET'
    });

    var r = confirm("Do you want to updated " + cnt + " rows ?");
    if (r) {
        //tien hanh update + thong bao so rows thanh cong
        $.ajax({
            url: url + '/' + step + '/' + fromDate + '/' + toDate + '/' + '1',
            async: false,
            success: function(data) {
                console.log(data);
                alert('Updated ' + data + ' row(s)');
            },
            type: 'GET'
        });
    }
}
function doUpdateStep(step)
{

    if(checkOIT())
    {
        doUpdateStep_child(step);
    }
    else{
        var r = confirm("Please check OIT list. Do you want to force update ?");
        if(r)
        {
            doUpdateStep_child(step);
        }
    }
}
function updateStep(step)
{
    if(getStatus()) //refresh dang on
    {
        //chon lua set off
        var r = confirm("Do you want to set refresh off");
        if (r == true) {
            //set refresh off
            $('#refresh').prop('checked',false);
            en_pe_search();
            clearInterval(timer);
            console.log('refresh stop');
            //continue update
            doUpdateStep(step);
        } else {
            alert('Cannot update due to refresh is on');
        }
    }
    else{
        //update
        doUpdateStep(step);
    }
}
function dis_pe_search()
{
    $('#period').prop("disabled",'disabled');
    $('#btnSearch').prop("disabled",'disabled');
}
function en_pe_search()
{
    $('#period').prop("disabled",'');
    $('#btnSearch').prop("disabled",'');
    $('select').material_select();
}
function onhand(chain)
{
    var url = "/public/onhand/search";
    var dateCheck = $('#dateCheck').val();
    var lotno = $('#lotno').val();
    $.get(url + '/' + chain + '/' + dateCheck + '/' + lotno, function (data) {
        var result = '';
        var x;

        if (chain == 'M20')
        {
            result += '<thead>';
            result += '<tr>';
            result += '<th class="">TP</th>';
            result += '<th class="">HEAT_NO</th>';
            result += '<th class="">ACT_ITEM</th>';
            result += '<th class="">WGT</th>';
            result += '</tr>';
            result += '</thead>';
            result += '<tbody>';
            for (x in data)
            {
                result += '<tr>';
                result += '<td>';
                result += data[x]["tp"];
                result += '</td>';
                result += '<td>';
                result += data[x]["heat_no"];
                result += '</td>';
                result += '<td>';
                result += data[x]["act_item"];
                result += '</td>';
                result += '<td>';
                result += data[x]["wgt"];
                result += '</td>';
                result += '</tr>';
            }
            result += '</tbody>';
        }
        if (chain == 'M30')
        {
            result += '<thead>';
            result += '<tr>';
            result += '<th class="">TP</th>';
            result += '<th class="">ITEM_CD</th>';
            result += '<th class="">ROL_LOT_NO</th>';
            result += '<th class="">CUR_WGT</th>';
            result += '<th class="">PROD_WGT</th>';
            result += '<th class="">REC_WGT</th>';
            result += '</tr>';
            result += '</thead>';
            result += '<tbody>';
            for (x in data)
            {
                result += '<tr>';
                result += '<td>';
                result += data[x]["tp"];
                result += '</td>';
                result += '<td>';
                result += data[x]["item_cd"];
                result += '</td>';
                result += '<td>';
                result += data[x]["rol_lot_no"];
                result += '</td>';
                result += '<td>';
                result += data[x]["cur_wgt"];
                result += '</td>';
                result += '<td>';
                result += data[x]["prod_wgt"];
                result += '</td>';
                result += '<td>';
                result += data[x]["rec_wgt"];
                result += '</td>';
                result += '</tr>';
            }
            result += '</tbody>';
        }
        if (chain == 'M60')
        {
            result += '<thead>';
            result += '<tr>';
            result += '<th class="">TP</th>';
            result += '<th class="">itm_cd</th>';
            result += '<th class="">prd_no</th>';
            result += '<th class="">prd_wgt</th>';
            result += '</tr>';
            result += '</thead>';
            result += '<tbody>';
            for (x in data)
            {
                result += '<tr>';
                result += '<td>';
                result += data[x]["tp"];
                result += '</td>';
                result += '<td>';
                result += data[x]["itm_cd"];
                result += '</td>';
                result += '<td>';
                result += data[x]["prd_no"];
                result += '</td>';
                result += '<td>';
                result += data[x]["prd_wgt"];
                result += '</td>';
                result += '</tr>';
            }
            result += '</tbody>';
        }
        $('#onHandResult').empty();
        $('#onHandResult').html(result);
    });
}
function ifDetail(chain)
{
    var url = "/public/ifNotSend/" + chain;
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    $.get(url + '/' + fromDate + '/' + toDate, function (data) {
        var result = '';
        var x;
        for (x in data)
        {
            result += '<tr>';
            result += '<td>';
            result += data[x]["ext_id"];
            result += '</td>';
            result += '<td>';
            result += data[x]["prd_dt"];
            result += '</td>';

            result += '<td>';
            result += data[x]["tct_tp_cd"];
            result += '</td>';
            result += '<td>';
            result += data[x]["tranret"];
            result += '</td>';
            result += '</tr>';
        }
        $('#if_not_send_detail_result').empty();
        $('#if_not_send_detail_result').html(result);
    });
}
$(document).ready(function () {
    //materialize init
    if ($('select')){
        $('select').material_select();
    }

    //site
        $('#refresh').change(function(){
            if($(this).is(":checked")) {
                //disable period, search button when change status off > on
                dis_pe_search();
                //do repeat
                //set autosearch
                console.log('refresh start');
                timer = setInterval(function(){
                    refresh();
                }, $('#period').val());
            }
            else{
                //enable period when change status on > off
                en_pe_search();
                clearInterval(timer);
                console.log('refresh stop');
            }
            $('select').material_select();
        });

        $('#modal_IF_trigger').click(function(){
            var url = "/public/ifNotSend";
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            $.get(url + '/' + fromDate + '/' + toDate, function (data) {
                var result = '';
                var x;
                for (x in data)
                {
                    result += '<tr>';
                    result += '<td>';
                        result += data[x]["chain"];
                    result += '</td>';
                    result += '<td>';
                    result += data[x]["tp"];
                    result += '</td>';
                    switch (data[x]["total"]){
                        case '0':
                            result += '<td>';
                            break;
                        default :
                            result += '<td> <a style="cursor: pointer" onclick="ifDetail(' + "'" +  data[x]["chain"] + "'" +  ')">';
                            break;
                    }
                    result += data[x]["total"];
                    result += '</a></td>';
                    result += '</tr>';
                }
                $('#if_not_send_result').empty();
                $('#if_not_send_result').html(result);
            });

            $('#modal_IF').openModal();
        });
    
        $('#modal_minus_trigger').click(function(){
            var url = "/public/minus";
            var fromDate = $('#fromDate').val();
            $.get(url + '/' + fromDate, function (data) {
                var result = '';
                var x;
                for (x in data)
                {
                    result += '<tr>';
                    result += '<td>';
                    result += data[x]["item_code"];
                    result += '</td>';
                    result += '<td>';
                    result += data[x]["onhand_qty"];
                    result += '</td>';
                    result += '<td>';
                    result += data[x]["pending_qty"];
                    result += '</td>';
                    result += '<td>';
                    result += data[x]["var_qty"];
                    result += '</td>';
                    result += '</tr>';
                }
                $('#minus_stock_result').empty();
                $('#minus_stock_result').html(result);
            });
            $('#modal_minus').openModal();
        });

        $('#btnSearch').click(function(){
            refresh();
        });

        $('#btnStep').click(function(){
            step();
        });
        function refresh()
        {
            //refresh I/F Status result
            var url = "/public/search/ifStatus";
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            console.log('search ifStatus');
            console.log('fromDate ' + fromDate);
            console.log('toDate ' + toDate);
            $.get(url + '/' + fromDate + '/' + toDate, function (data) {
                //success
                var result = '';
                var x;
                for (x in data)
                {
                    result += '<div class="col m2 s6">';

                    switch (data[x]["process_status_code"]){
                        case 'WIP_READY':
                            result += '<div class="card green">';
                            break;
                        case 'WIP_CANCEL':
                            result += '<div class="card grey">';
                            break;
                        case '000':
                            result += '<div class="card grey">';
                            break;
                        case 'NEW':
                            result += '<div class="card yellow">';
                            break;
                        default :
                            result += '<div class="card red">';
                            break;
                    }

                    result += '<div class="card-content tooltipped" data-position="bottom" delay="50" data-tooltip="'+ data[x]["process_status_desc"] + ' / "' + data[x]["program"] + '>';
                    result += '<p class="bignum">'+ data[x]["count"] + '</p>';
                    result += '<p class="statusCode">'+ data[x]["process_status_code"] + '</p>';
                    result += '</div>';
                    result += '</div>';
                    result += '</div>';
                }
                $('#ifStatusResult').empty();
                $('#ifStatusResult').html(result);
                $('.tooltipped').tooltip({delay: 50});
            });

            //refresh OIT result
            console.log('search oit');
            $.get("/public/search/oit", function (data) {
                var x;
                var i = 0;
                for (x in data){
                    //console.log(data[x]['tp']);
                    //console.log(data[x]['total']);
                    i++;
                    $('#oit_'+i).html(data[x]['total']);
                }
            });
        }

        function step()
        {

            var url = "/public/step";
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();

            $.get(url + '/' + fromDate + '/' + toDate, function (data) {
                //success
                var result = '';
                var x;
                for (x in data)
                {

                    if (data[x]['step'] < 13)
                    {
                        result += '<tr>';
                        result += '<td><a class="hand" id="step_'+data[x]['step']+'" onclick="updateStep('+data[x]['step']+')">' + data[x]['step'] + '</a></td>';
                        result += '<td><a class="hand" id="step_'+data[x]['step']+'" onclick="updateStep('+data[x]['step']+')">' + data[x]['step_description'] + '</a></td>';
                        result += '<td><a class="hand" id="step_'+data[x]['step']+'" onclick="updateStep('+data[x]['step']+')">' + data[x]['total'] + '</a></td>';
                        result += '/<tr>';
                    }
                    else{
                        result += '<tr>';
                        result += '<td>' + data[x]['step'] + '</td>';
                        result += '<td>' + data[x]['step_description'] + '</td>';
                        result += '<td>' + data[x]['total'] + '</td>';
                        result += '/<tr>';
                    }
                }
                $('#stepResult').empty();
                $('#stepResult').html(result);
                //$('#stepResult').replaceWith(result);
                });

        }

        $('#btnSearchOnHandM20').click(function(){
            onhand('M20');
        });
        $('#btnSearchOnHandM30').click(function(){
            onhand('M30');
        });
        $('#btnSearchOnHandM60').click(function(){
            onhand('M60');
        });
    
});





