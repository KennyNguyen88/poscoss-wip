<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class KpiController extends Controller
{
	public function fmDate($Date) {
		return str_replace( "-", "", $Date );
	}
	public function index()
	{
		return view('site.kpi');
	}
	public function production_result($chain, $year){
//		$year = '2016';
		$sql = null;
		switch ($chain)
		{
			case 'M20':
				$sql = "
							SELECT
							    SUBSTR(MTL.PSV_DT,0,6) AS MON,
							    ROUND(SUM(CASE WHEN MTL.INSP_RSL_TP <> 'B' OR MTL.SCR_OCC_CAU_CD <> 'B' THEN MTL.MTL_WGT END)/1000) AS GOOD,
							    ROUND(SUM(CASE WHEN MTL.INSP_RSL_TP = 'B' OR MTL.SCR_OCC_CAU_CD = 'B' THEN MTL.MTL_WGT END)/1000) AS BAD
							FROM 
							    TB_M20_MTL_RSL@VINA_MESUSER MTL    
							WHERE 1=1 
							AND EXISTS (
							    SELECT 
							        1
							    FROM
							        TB_M20_HEAT_COMM@VINA_MESUSER COMM
							    WHERE
							        1=1
							        AND COMM.PSV_DT LIKE :P_YEAR||'%'
							        AND MTL.HEAT_NO = COMM.HEAT_NO
							    )
							AND HEAT_NO LIKE 'V%'
							GROUP BY SUBSTR(MTL.PSV_DT,0,6) 
							ORDER BY 1
						";
				break;
			default: break;

		}
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}
	public function material_result($chain, $year){
//		$year = '2016';
		$sql = null;
		switch ($chain)
		{
			case 'M20':
				$sql = "
							SELECT
							    SUBSTR(B.PRD_DT,0,6) AS MON
							    , ROUND(SUM(A.CRG_WGT)/1000) TOTAL    
							FROM
							    (
							        SELECT HEAT_NO, SUM(CRG_WGT) AS CRG_WGT, RAW_MTL_PRD_CD
							        FROM TB_M20_EAF_MAIN_MTL_USE_RSL@VINA_MESUSER
							        WHERE CRG_WGT > 0
							        GROUP BY HEAT_NO, RAW_MTL_PRD_CD
							        UNION ALL
							        SELECT HEAT_NO, SUM(SUB_MTL_INP_WGT) AS CRG_WGT, RAW_MTL_PRD_CD
							        FROM TB_M20_SUB_MTL_INP_RSL@VINA_MESUSER
							        WHERE SUB_MTL_INP_WGT > 0
							        GROUP BY HEAT_NO, RAW_MTL_PRD_CD
							        UNION ALL
							        SELECT A.HEAT_NO, SUM(A.WIRE_USE_WGT_1) AS CRG_WGT, WIRE_CD_1 AS RAW_MTL_PRD_CD
							        FROM TB_M20_WIRE_USE_RSL@VINA_MESUSER A
							        WHERE A.WIRE_USE_WGT_1 > 0
							        GROUP BY A.HEAT_NO, WIRE_CD_1
							    ) A
							    , TB_M20_SNDIF_STL_INOUT@VINA_MESUSER B
							WHERE 1=1
							AND A.HEAT_NO = B.PRD_LOT_NO
							AND B.PRD_DT LIKE :P_YEAR||'%'
							AND A.RAW_MTL_PRD_CD LIKE 'AA%'
							GROUP BY
							    SUBSTR(A.RAW_MTL_PRD_CD,1,1)
							    , SUBSTR(B.PRD_DT,0,6)
							ORDER BY 1
						";
				break;
			default: break;

		}
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}
	public function rework_result($chain, $year){
//		$year = '2016';
		$sql = null;
		switch ($chain)
		{
			case 'M20':
				$sql = "
							SELECT 
							    SUBSTR(B.PRD_DT,0,6) AS MON
							    , ROUND(SUM (A.MTL_MOD_WGT)/1000) AS TOTAL
							FROM 
							    TB_M20_MTL_MOD_RSL@VINA_MESUSER A
							    , TB_M20_SNDIF_STL_INOUT@VINA_MESUSER B
							WHERE 1=1
							    AND A.MTL_NO LIKE 'V%'    
							    AND SUBSTR(A.MTL_NO,0,6) = B.PRD_LOT_NO
							    AND B.PRD_DT LIKE :P_YEAR||'%'
							GROUP BY
							    SUBSTR(B.PRD_DT,0,6)
							ORDER BY 1
						";
				break;
			default: break;

		}
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}
	/* TONG SO HEAT */
	public function smp_heat_comm_01($year){
		$sql =
			"
				SELECT COUNT(*) AS TOTAL
				FROM TB_M20_HEAT_COMM@VINA_MESUSER
				WHERE HEAT_NO LIKE 'V%'
				AND PSV_DT LIKE :P_YEAR||'%'
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}

	//TONG SO HEAT THEO STEEL GRD
	public function smp_heat_comm_02($year){
		$sql =
			"
				SELECT 
				    RSL_INCO_STL_GRD
				    , COUNT(*) AS TOTAL
				FROM TB_M20_HEAT_COMM@VINA_MESUSER
				WHERE HEAT_NO LIKE 'V%'
				AND PSV_DT LIKE :P_YEAR||'%'
				GROUP BY RSL_INCO_STL_GRD
				ORDER BY 1
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}

	/* TONG SO HEAT THEO THANG, STEELGRADE */
	public function smp_heat_comm_03($year){
		$sql =
			"
				SELECT 
				    SUBSTR(PSV_DT,0,6) AS MON
				    , RSL_INCO_STL_GRD
				    , COUNT(*) AS TOTAL
				FROM TB_M20_HEAT_COMM@VINA_MESUSER
				WHERE HEAT_NO LIKE 'V%'
				AND PSV_DT LIKE :P_YEAR||'%'
				GROUP BY 
				    SUBSTR(PSV_DT,0,6)
				    , RSL_INCO_STL_GRD
				ORDER BY 1,2
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}

	/* HEAT CO KHOI LUONG NHO NHAT, LON NHAT */
	public function smp_mtl_rsl_01($year){
		$sql =
			"
				WITH HEAT_WGT AS (
				SELECT
				    HEAT_NO
				    , SUM(MTL_WGT) AS TOTAL    
				FROM TB_M20_MTL_RSL@VINA_MESUSER
				WHERE HEAT_NO IN ( SELECT HEAT_NO
				                    FROM TB_M20_HEAT_COMM@VINA_MESUSER
				                    WHERE HEAT_NO LIKE 'V%'
				                    AND PSV_DT LIKE :P_YEAR||'%'
				                  )
				GROUP BY HEAT_NO                  
				)
				
				SELECT 
				    'MAX' as TP
				    , A.*    
				FROM HEAT_WGT A
				WHERE A.TOTAL = (SELECT MAX(TOTAL) FROM HEAT_WGT B)
				UNION ALL
				SELECT 
				    'MIN' as TP
				    , A.*    
				FROM HEAT_WGT A
				WHERE A.TOTAL = (SELECT MIN(TOTAL) FROM HEAT_WGT B)
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}

	/* HEAT CO SO LUONG CAY NHO NHAT, LON NHAT */
	public function smp_mtl_rsl_02($year){
		$sql =
			"
				WITH HEAT_WGT AS (
				SELECT
				    HEAT_NO
				    , COUNT(*) AS TOTAL    
				FROM TB_M20_MTL_RSL@VINA_MESUSER
				WHERE HEAT_NO IN ( SELECT HEAT_NO
				                    FROM TB_M20_HEAT_COMM@VINA_MESUSER
				                    WHERE HEAT_NO LIKE 'V%'
				                    AND PSV_DT LIKE :P_YEAR||'%'
				                  )
				GROUP BY HEAT_NO                  
				)
				
				SELECT 
				    'MAX' as TP
				    , A.*    
				FROM HEAT_WGT A
				WHERE A.TOTAL = (SELECT MAX(TOTAL) FROM HEAT_WGT B)
				UNION ALL
				SELECT 
				    'MIN' as TP
				    , A.*    
				FROM HEAT_WGT A
				WHERE A.TOTAL = (SELECT MIN(TOTAL) FROM HEAT_WGT B)
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}

	/* TONG KHOI LUONG TINH THEO DIMENSION */
	public function smp_mtl_rsl_03($year){
		$sql =
			"
				WITH HEAT_WGT AS (
				SELECT
				    HEAT_NO
				    , MTL_DIMS_CD
				    , SUM(MTL_WGT) AS TOTAL_WGT
				    , COUNT(*) AS TOTAL_PCS
				FROM TB_M20_MTL_RSL@VINA_MESUSER
				WHERE HEAT_NO IN ( SELECT HEAT_NO
				                    FROM TB_M20_HEAT_COMM@VINA_MESUSER
				                    WHERE HEAT_NO LIKE 'V%'
				                    AND PSV_DT LIKE :P_YEAR||'%'
				                  )
				GROUP BY HEAT_NO, MTL_DIMS_CD                  
				)
				SELECT 
				    MTL_DIMS_CD
				    , SUM(TOTAL_WGT) AS TOTAL_WGT
				    , SUM(TOTAL_PCS) AS TOTAL_PCS
				FROM HEAT_WGT
				GROUP BY MTL_DIMS_CD
				ORDER BY 1
			"
		;
		if(!is_null($sql))
		{
			$results = DB::select($sql,['P_YEAR' => $year]);
			return response()->json($results);
		}
		return null;
	}
}
