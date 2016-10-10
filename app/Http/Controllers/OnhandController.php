<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class OnhandController extends Controller
{
	public function fmDate($Date) {
		return str_replace( "-", "", $Date );
	}
	public function index()
	{
		return view('site.onhand');
	}

	public function search($chain,$dateCheck,$lotno)
	{
		$dateCheck = $this->fmDate($dateCheck);
		$sql = null;
		switch ($chain)
		{
			case 'M20':
				$sql = queryIF::$sql_m20_onhand;
				break;
			case 'M30':
				$sql = queryIF::$sql_m30_onhand;
				break;
			case 'M60':
				$sql = queryIF::$sql_m60_onhand;
				break;
			default:
				break;
		}
		if(!is_null($sql))
		{
			$results = DB::select($sql,['dateCheck' => $dateCheck, 'lotno' => $lotno]);
			return response()->json($results);
		}
		return null;
	}
}
