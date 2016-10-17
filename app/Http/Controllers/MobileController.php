<?php

namespace App\Http\Controllers;
use App\Http\Controllers\queryIF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \DateTime;
use \DateInterval;
use App\Http\Requests;

class MobileController extends Controller
{
	public function getOnhandItems($itemCd = '') {
		$results = DB::select(queryIF::$sql_mobile_onhand,['itemCd' => $itemCd]);
		return response()->json($results);
	}

	public function getSubInventoryList($subInventory = '') {
		$results = DB::select(queryIF::$sql_mobile_subInventoryList,['SUBINVENTORY_CODE' => $subInventory]);
		return response()->json($results);
	}

	public function getCycleCntDetail($subInventory) {
		$results = DB::select(queryIF::$sql_mobile_cycleCntDetail,['SUBINVENTORY_CODE' => $subInventory]);
		return response()->json($results);
	}
	public function getTransactionDetail($inventoryItemId) {
		$results = DB::select(queryIF::$sql_mobile_transaction_history_detail,['inventory_item_id' => $inventoryItemId]);
		return response()->json($results);
	}
	
}
