<?php

namespace App\Http\Controllers;
use App\Http\Controllers\queryIF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \DateTime;
use \DateInterval;
use App\Http\Requests;

class PageController extends Controller
{
	public function fmDate($Date) {
		return str_replace( "-", "", $Date );
	}
	public function index()
	{
		//set default value for control box
		$fromDate = date('Y-m-01'); //begin of month
		$toDate = date('Y-m-t'); //end of month
		$filename = substr(str_replace("-","",$fromDate),0,6).".xml";
		//$filename = "201608.xml";
		$data = array(
			'pageID' => 'wip-close',
			'fromDate' => $fromDate,
			'toDate' => $toDate,
			'history' => $this->readHistory($filename)
		);
		return view('site.wip-close')->with('data',$data);
	}
	public function searchIfStatus($fromDate, $toDate)
	{
		$fromDate = $this->fmDate($fromDate);
		$toDate =  $this->fmDate($toDate);
		$if_status_results = DB::select(queryIF::$sql_if_status,['fromDate' => $fromDate, 'toDate' => $toDate]);
		return response()->json($if_status_results);
	}
	public function searchOIT()
	{
		$oit_results = DB::select(queryIF::$sql_oit);
		return response()->json($oit_results);
	}
	public function searchOITCheck()
	{
		$oit_check_results = DB::select(queryIF::$sql_oit_check);
		return response()->json($oit_check_results);
	}
	public function step_id($id, $fromDate, $toDate,$update)
	{
		$fromDate = $this->fmDate($fromDate);
		$toDate =  $this->fmDate($toDate);
		$step_id_results = null;
		$rows = 0;
		$test = null;
		if (!$update){ //search
			switch ($id){
				case 0:
					$step_id_results = DB::select(queryIF::$sql_step_0_search,['fromDate' => $fromDate]);
					break;
				case 1:
					$step_id_results = DB::select(queryIF::$sql_step_1_search,['fromDate' => $fromDate]);
					break;
				case 2:
					$step_id_results = DB::select(queryIF::$sql_step_2_search,['fromDate' => $fromDate]);
					break;
				case 4:
					$step_id_results = DB::select(queryIF::$sql_step_4_search,['fromDate' => $fromDate]);
					break;
				case 5:
					$step_id_results = DB::select(queryIF::$sql_step_5_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 6:
					$step_id_results = DB::select(queryIF::$sql_step_6_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 7:
					$step_id_results = DB::select(queryIF::$sql_step_7_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 8:
					$step_id_results = DB::select(queryIF::$sql_step_8_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 9:
					$step_id_results = DB::select(queryIF::$sql_step_9_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 10:
					$step_id_results = DB::select(queryIF::$sql_step_10_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 11:
					$step_id_results = DB::select(queryIF::$sql_step_11_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 12:
					$step_id_results = DB::select(queryIF::$sql_step_12_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				default:
					break;
			}
			return response()->json($step_id_results);
		}
		else{ //update
			$filename = substr($fromDate,0,6).".xml";
			switch ($id){
				case 0:
					//writing history
					$step_id_results = DB::select(queryIF::$sql_step_0_search,['fromDate' => $fromDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_0_update, ['fromDate' => $fromDate]);
					break;
				case 1:
					$step_id_results = DB::select(queryIF::$sql_step_1_search,['fromDate' => $fromDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_1_update, ['fromDate' => $fromDate]);
					break;
				case 2:
					$step_id_results = DB::select(queryIF::$sql_step_2_search,['fromDate' => $fromDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_2_update, ['fromDate' => $fromDate]);
					break;
				case 4:
					$step_id_results = DB::select(queryIF::$sql_step_4_search,['fromDate' => $fromDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_4_update, ['fromDate' => $fromDate]);
					break;
				case 5:
					$step_id_results = DB::select(queryIF::$sql_step_5_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_5_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 6:
					$step_id_results = DB::select(queryIF::$sql_step_6_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_6_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 7:
					$step_id_results = DB::select(queryIF::$sql_step_7_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_7_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 8:
					$step_id_results = DB::select(queryIF::$sql_step_8_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_8_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 9:
					$step_id_results = DB::select(queryIF::$sql_step_9_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_9_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 10:
					$step_id_results = DB::select(queryIF::$sql_step_10_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_10_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 11:
					$step_id_results = DB::select(queryIF::$sql_step_11_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_11_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				case 12:
					$step_id_results = DB::select(queryIF::$sql_step_12_search,['fromDate' => $fromDate, 'toDate' => $toDate]);
					$this->writingHistory($step_id_results,$id,$filename);
					//do update
					$rows = DB::update(queryIF::$sql_step_12_update, ['fromDate' => $fromDate, 'toDate' => $toDate]);
					break;
				default:
					break;
			}
			return response()->json($rows);
		}
	}
	public function step($fromDate, $toDate)
	{
		$fromDate = $this->fmDate($fromDate);
		$toDate = $this->fmDate($toDate);
		$step_results = DB::select(queryIF::$sql_step,['fromDate' => $fromDate, 'toDate' => $toDate]);
		return response()->json($step_results);
	}
	public function writingHistory($array,$step,$filename)
	{
		$stepdesc = null;
		switch($step){
			case 0:
				$stepdesc = "To WIP_READY";
				break;
			case 1:
				$stepdesc = "SMP Semi";
				break;
			case 2:
				$stepdesc = "SMP Finish Product";
				break;
			case 4:
				$stepdesc = "SMP Shipping";
				break;
			case 5:
				$stepdesc = "Rebar Semi";
				break;
			case 6:
				$stepdesc = "Rebar finish";
				break;
			case 7:
				$stepdesc = "Rebar Rework";
				break;
			case 8:
				$stepdesc = "Rebar Shipping";
				break;
			case 9:
				$stepdesc = "Section Semi";
				break;
			case 10:
				$stepdesc = "Section Finish";
				break;
			case 11:
				$stepdesc = "Section Rework";
				break;
			case 12:
				$stepdesc = "Section Shipping";
				break;
			default:
				break;
		}
		date_default_timezone_set("Asia/Krasnoyarsk");
		$now = (new DateTime())->format('Y-m-d H:i:s');
		$xml = new \DOMDocument();
		$xml->load($filename);
		$xml->formatOutput = true;

		$summary = $xml->getElementsByTagName('summary')->item(0);
		$update = $xml->createElement('update');
		$time = $xml->createElement('time',$now);
		$stepname = $xml->createElement('stepname',$stepdesc);
		$rowcnt = $xml->createElement('rowcnt',count($array));
		$ext_ids = $xml->createElement('ext_ids');
		foreach($array as $x)
		{
			$ext_id = $xml->createElement('ext_id',$x->if_ext_id);
			$ext_ids->appendChild($ext_id);
		}

		$update->appendChild($ext_ids);
		$update->appendChild($rowcnt);
		$update->appendChild($stepname);
		$update->appendChild($time);
		$summary->appendChild($update);

		$xml->save($filename);
	}
	public function readHistory($filename)
	{
		$results = array();
		$doc = new \DOMDocument();
		$doc->load($filename);

		$updates = $doc->getElementsByTagName('update');

		foreach ($updates as $update)
		{
			$temp = array();
			foreach($update->childNodes as $node)
			{

				if($node->nodeName == "time")
				{
					$temp['time'] = $node->nodeValue;
				}
				if($node->nodeName == "stepname")
				{
					$temp['stepname'] = $node->nodeValue;
				}
				if($node->nodeName == "rowcnt")
				{
					$temp['rowcnt'] = $node->nodeValue;
				}
			}
			array_push($results,$temp);
		}
		return $results;

	}
	public function ifNotSend($fromDate, $toDate)
	{
		$fromDate = $this->fmDate($fromDate);
		$toDate = $this->fmDate($toDate);
		$if_not_send_results = DB::select(queryIF::$sql_if_not_send,['fromDate' => $fromDate, 'toDate' => $toDate]);
		return response()->json($if_not_send_results);
	}
	public function prdRsl()
	{
		return view('site.productionResult');
	}
	public function minus($fromDate)
	{
		$sql =
			"
			select
				item_code
				, sum(onhand_qty) onhand_qty
				, sum(pending_qty) pending_qty
				, sum(onhand_qty)- sum(pending_qty) var_qty
			from
				(
					select
						segment1 item_code
						, sum(transaction_quantity) onhand_qty
						, 0 pending_qty
					from
						MTL_ONHAND_QUANTITIES a
						, mtl_system_items b
					where a.organization_id = 87
						and a.subinventory_code like 'A%'
						and a.organization_id = b.organization_id
						and a.inventory_item_id = b.inventory_item_id
					group by b.segment1
					union all
					select
						component_item_code item_code
						, 0 onhand_qty
						, component_qty pending_qty
					from
						psvwip_mes_component_tb_all
					where if_ext_id in (
											select if_ext_id
											from psvwip_mes_result_tb_all
											where 1=1
												and transaction_Date like substr(:fromDate,0,6)||'%'
												and department_code like '11130'
												and process_status_code in ('NEW', 'WIP_READY')
										)
				)
			group by item_code
			having (sum(onhand_qty)- sum(pending_qty)) < 0
			order by 1
			";
		$fromDate = $this->fmDate($fromDate);
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return response()->json($results);
	}
	public function ifNotSendDetail($chain, $fromDate, $toDate)
	{
		$sqlm20 =
			"
			SELECT
			    EXT_ID
			    , PRD_DT
			    , TCT_TP_CD
			    , TRANRET
			FROM
			    TB_M20_SNDIF_STL_INOUT@VINA_MESUSER  A
			WHERE
			    CREATION_TIMESTAMP = (SELECT MAX(CREATION_TIMESTAMP)
			                           FROM TB_M20_SNDIF_STL_INOUT@VINA_MESUSER X
			                           WHERE X.PRD_LOT_NO = A.PRD_LOT_NO
			                             AND X.PRD_DT BETWEEN :fromDate AND :toDate
			                             AND X.TCT_TP_CD = '02'
			                         )
			    AND TRANRET in ('B','C')
			";
		$sqlm30 =
			"
			Select
			    EXT_ID
			    , PRD_DT
			    , TCT_TP_CD
			    , TRANRET
			from TB_M30_E50M30_04@VINA_MESUSER
			where 1=1
			and PRD_DT between :fromDate AND :toDate
			and TRANRET in ('B','C')
			union all
			Select
			    IF_EXT_ID as EXT_ID
			    , PRD_DT
			    , TCT_TP_CD
			    , 'NOT' AS TRANRET
			from tb_m30_opr_prd_rsl@VINA_MESUSER
			where 1=1
			and PRD_DT between :fromDate AND :toDate
			and IF_EXT_ID not in ( Select EXT_ID
			                        from TB_M30_E50M30_04@VINA_MESUSER
			                        )
			";
		$sqlm60 =
			"
			Select
			    EXT_ID
			    , PRD_DT
			    , TCT_TP_CD
			    , TRANRET
			from TB_M60_E50M60_05@VINA_MESUSER
			where 1=1
			and PRD_DT between :fromDate AND :toDate
			and TRANRET in ('B','C')
			";
		$fromDate = $this->fmDate($fromDate);
		$toDate = $this->fmDate($toDate);
//		$sql = "";
		switch ($chain) {
			case 'M20':
				$if_not_send_detail_results = DB::select($sqlm20,['fromDate' => $fromDate, 'toDate' => $toDate]);
				return response()->json($if_not_send_detail_results);
			case 'M30':
				$if_not_send_detail_results = DB::select($sqlm30,['fromDate' => $fromDate, 'toDate' => $toDate]);
				return response()->json($if_not_send_detail_results);
			default:
				$if_not_send_detail_results = DB::select($sqlm60,['fromDate' => $fromDate, 'toDate' => $toDate]);
				return response()->json($if_not_send_detail_results);
		}
	}
	public function test()
	{
		$fromDate = '20160501';
		$toDate = '20160531';
//		$results = DB::update($this->sql_step_0_update);
//		$results = DB::select($this->test);
//		$results = substr($fromDate,0,6).'.xml';

//		return view('welcome', ['results' => $results]);
//		return response()->json($results);

	}
}
