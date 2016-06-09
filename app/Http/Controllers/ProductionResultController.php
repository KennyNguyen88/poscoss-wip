<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class ProductionResultController extends Controller
{
	public $sql_m30 =
		"
			SELECT
			    SUM(PRD_WGT) AS PRD_WGT
			    , SUM (INP_WGT) AS INP_WGT
			FROM
			(
			    SELECT
			         RSL.PRD_WGT AS PRD_WGT
			         ,OPR.INP_WGT AS INP_WGT
			    FROM (
			        SELECT
			             DIV_ROL_LOT_NO
			            , SUM(PRD_WGT) AS PRD_WGT
			            , MAX(INST_TP) AS INST_TP
			        FROM (
			            SELECT
			                 PRD.DIV_ROL_LOT_NO
			                , SUM(NVL(PRD.PRD_WGT,0)-NVL(ADD_PCS_WGT,0)) AS PRD_WGT
			                , MAX(SUB.INST_TP) AS INST_TP
			            FROM TB_M30_PRD_RSL@VINA_MESUSER PRD
			                , TB_K40_ROL_INST_CMN@VINA_MESUSER CMN
			                , TB_K40_ROL_INST_SUB_ORD@VINA_MESUSER SUB
			            WHERE PRD.FAC_TP = CMN.FAC_TP
			                AND PRD.ROL_LOT_NO = CMN.ROL_LOT_NO
			                AND CMN.ROL_LOT_NO = SUB.ROL_LOT_NO
			                AND SUB.PROC_SEQ_TP IN ('F','A')
			                AND PRD.INSP_PSV_DT like SUBSTR(:fromDate,0,6)||'%'
			                AND PRD.FAC_TP = :FAC_TP
			                AND SUB.INST_TP LIKE :INST_TP||'%'
			            GROUP BY PRD.DIV_ROL_LOT_NO
			            UNION ALL
			            SELECT
			                 PRD.ADD_PCS_LOT_NO AS DIV_ROL_LOT_NO
			                , SUM(ADD_PCS_WGT) AS PRD_WGT
			                , MAX(SUB.INST_TP) AS INST_TP
			            FROM TB_M30_PRD_RSL@VINA_MESUSER PRD
			                ,TB_K40_ROL_INST_CMN@VINA_MESUSER CMN
			                ,TB_K40_ROL_INST_SUB_ORD@VINA_MESUSER SUB
			            WHERE PRD.FAC_TP = CMN.FAC_TP
			                AND PRD.ADD_PCS_LOT_NO = CMN.ROL_LOT_NO
			                AND CMN.ROL_LOT_NO = SUB.ROL_LOT_NO
			                AND SUB.PROC_SEQ_TP IN ('F','A')
			                AND PRD.INSP_PSV_DT like SUBSTR(:fromDate,0,6)||'%'
			                AND (SELECT MAX(INSP_PSV_DT) FROM TB_M30_PRD_RSL@VINA_MESUSER RSL WHERE RSL.ROL_LOT_NO = PRD.ADD_PCS_LOT_NO) like SUBSTR(:fromDate,0,6)||'%'
			                AND PRD.FAC_TP = :FAC_TP
			                AND PRD.ADD_PCS_LOT_NO IS NOT NULL
			                AND SUB.INST_TP LIKE :INST_TP||'%'
			                GROUP BY PRD.ADD_PCS_LOT_NO
			            UNION ALL
			            SELECT
			                 PRD.ADD_PCS_LOT_NO AS DIV_ROL_LOT_NO
			                , SUM(ADD_PCS_WGT) AS PRD_WGT
			                , MAX(SUB.INST_TP) AS INST_TP
			            FROM TB_M30_PRD_RSL@VINA_MESUSER PRD
			                ,TB_K40_ROL_INST_CMN@VINA_MESUSER CMN
			                ,TB_K40_ROL_INST_SUB_ORD@VINA_MESUSER SUB
			            WHERE PRD.FAC_TP = CMN.FAC_TP
			                AND PRD.ADD_PCS_LOT_NO = CMN.ROL_LOT_NO
			                AND CMN.ROL_LOT_NO = SUB.ROL_LOT_NO
			                AND SUB.PROC_SEQ_TP IN ('F','A')
			                AND SUBSTR(PRD.INSP_PSV_DT,0,6) > SUBSTR(:fromDate,0,6)
			                AND PRD.FAC_TP = :FAC_TP
			                AND PRD.ADD_PCS_LOT_NO IS NOT NULL
			                AND SUB.INST_TP LIKE :INST_TP||'%'
			                AND EXISTS (SELECT 1 FROM TB_M30_OPR_PRD_RSL@VINA_MESUSER OPR WHERE OPR.ROL_LOT_NO = PRD.ADD_PCS_LOT_NO AND OPR.FAC_TP = :FAC_TP AND OPR.PRD_DT like SUBSTR(:fromDate,0,6)||'%')
			              GROUP BY PRD.ADD_PCS_LOT_NO
			        )
			            GROUP BY DIV_ROL_LOT_NO
			        ) RSL
			        ,(
			        SELECT
			            ROL_LOT_NO
			            ,DECODE(TCT_TP_CD,'04','G','21','E','Z') AS INST_TP
			            ,MAX(PRD_DT) AS PRD_DT
			            ,MAX(PRD_WGT) KEEP(DENSE_RANK LAST ORDER BY PRD_DT) AS PRD_WGT
			            ,MAX(INP_WGT) KEEP(DENSE_RANK LAST ORDER BY PRD_DT) AS INP_WGT
			        FROM TB_M30_OPR_PRD_RSL@VINA_MESUSER OPR
			        WHERE TCT_TP_CD <> '13'
			        AND FAC_TP = :FAC_TP

			            GROUP BY ROL_LOT_NO,TCT_TP_CD
			        ) OPR
			    WHERE 1=1

			        AND RSL.DIV_ROL_LOT_NO = OPR.ROL_LOT_NO
			        AND RSL.INST_TP = OPR.INST_TP

			    UNION ALL
			    SELECT
			         0 AS PRD_WGT
			        , SUM(SCR.INP_WGT) AS INP_WGT
			    FROM TB_M30_OPR_PRD_RSL@VINA_MESUSER SCR
			        ,TB_K40_ROL_INST_CMN@VINA_MESUSER CMN
			    WHERE SCR.FAC_TP = CMN.FAC_TP
			        AND SCR.ROL_LOT_NO = CMN.ROL_LOT_NO
			        AND SCR.FAC_TP = :FAC_TP
			        AND SCR.PRD_DT like SUBSTR(:fromDate,0,6)||'%'
			        AND NVL(:INST_TP,'%') IN ('%','G')
			        AND SCR.TCT_TP_CD = '13'
			)
			";
	public $sql_erp =
		"select xx1c.rule_desc prod_group_desc ,
		  sum(decode(xawl.yield_dest_flag, 'Y', xawl.act_issue_qty, 0)) iss_qty ,
		  sum(decode(xawl.yield_dest_flag, 'Y', xawl.receipt_qty, 0)) prod_qty ,
		  sum(decode(xawl.yield_dest_flag, 'Y', xawl.fail_qty, 0)) fail_qty,
		  case
    		when sum(decode(xawl.yield_dest_flag, 'Y', xawl.act_issue_qty, 0)) = 0 then 0
    		else round(sum(decode(xawl.yield_dest_flag, 'Y', xawl.receipt_qty, 0)) / sum(decode(xawl.yield_dest_flag, 'Y', xawl.act_issue_qty, 0)) * 100,2)
  			end yield
		from
		      psvbom_act_wip_lot_all xawl ,
		      (
		      select
		        segment1 rule_code ,
		        segment2 rule_desc
		      from psvbom_com_lookup_code_all
		      where lookup_type_code = 'ITEM_RULE_PRDGRP_CODE'
		      group by
		          segment1 ,
		          segment2
		  ) xx1c
		where xawl.organization_id = 87
		  and to_char(xawl.trx_date,'YYYYMM') like substr(:fromDate,0,6)
		  and xawl.actual_item_code like :ITEM_CODE||'%'
		  and xawl.rework_yn = nvl(:rework_yn, xawl.rework_yn)
		  and xx1c.rule_code (+)= psvcm_com_get_pkg.get_item_rule_fnc(xawl.actual_item_code, 'ITEM_RULE_PRDGRP_CODE')
		group by xx1c.rule_desc
		order by 1
		";

	public function fmDate($Date) {
		return str_replace( "-", "", $Date );
	}
	public function index(){
		return view('site.productionResult');
	}

    public function search(Request $request)
    {
	    $fromDate = $this->fmDate($request->input('fromDate'));
	    $results = array();
	    $results['SMP_Normal_Semi_MES_ProdQty'] = $this->get_SMP_Normal_Semi_MES_ProdQty($fromDate);
	    $results['SMP_Normal_Semi_MES_Input_Scrap'] = $this->get_SMP_Normal_Semi_MES_Input_Scrap($fromDate);
	    $results['SMP_Normal_Semi_MES_Input_Ferro'] = $this->get_SMP_Normal_Semi_MES_Input_Ferro($fromDate);
	    $results['SMP_Normal_Semi_MES_Input_Ore'] = $this->get_SMP_Normal_Semi_MES_Input_Ore($fromDate);
	    $results['SMP_Normal_FG_MES_Receipt'] = $this->get_SMP_Normal_FG_MES_Receipt($fromDate);
	    $results['Section_Normal_Semi_MES'] = $this->get_Section_Normal_Semi_MES($fromDate);
	    $results['Section_Rework_Semi_MES'] = $this->get_Section_Rework_Semi_MES($fromDate);
	    $results['Rebar_Normal_Semi_MES'] = $this->get_Rebar_Normal_Semi_MES($fromDate);
	    $results['Rebar_Rework_Semi_MES'] = $this->get_Rebar_Rework_Semi_MES($fromDate);
	    $results['SMP_Normal_Semi_ERP'] = $this->get_SMP_Normal_Semi_ERP($fromDate); //0,1,2,21,22,23,41,42,43
	    $results['Section_Rework_Semi_ERP'] = $this->get_Section_Rework_Semi_ERP($fromDate); //31,32,33,51,52,53
	    $results['FG_Shipping_ERP'] = $this->get_FG_Shipping_ERP($fromDate); //26,27,29,46,47,49
	    $results['FG_Shipping_MES'] = $this->get_FG_Shipping_MES($fromDate); //28,30,38,48,50,58
	    $results['SMP_Normal_Semi_ERP_Input'] = $this->get_SMP_Normal_Semi_ERP_Input($fromDate); //10,12
	    return view('site.productionResult', ['results' => $results]);
    }
	public function get_SMP_Normal_Semi_ERP($fromDate)
	{
		$results = DB::select($this->sql_erp,['fromDate' => $fromDate, 'item_code' => 'E', 'rework_yn' => 'N']);
		return $results;
	}
	public function get_SMP_Normal_Semi_ERP_Input($fromDate){
		$sql =
			"
			SELECT  DECODE(ZZ.LOOKUP_CODE,'S1','FERRO','S2','ORE') AS LOOKUP_CODE
			,       SUM(TRX_QTY)    TOTAL
			FROM  ( SELECT  PRI.ORGANIZATION_ID
			        ,       PRI.TRX_ITEM_ID
			        ,       PRI.TRX_GROUP_CODE
			        ,       PLC.SEGMENT1               BALANCE_TYPE
			        ,       SUBSTR(PLC.LOOKUP_CODE_SEQ, 1, 1)   ORDERBY
			        ,       PRI.TRX_QTY
			        FROM    PSVCM.PSVCM_ACTG_RAW_IODOC_ALL PRI
			        ,       PSVCM.PSVCM_COM_LOOKUP_CODE_ALL PLC
			        WHERE   PRI.ORGANIZATION_ID = 87
			        AND     PRI.PERIOD_YYMM = SUBSTR(:FROMDATE,0,6)
			        AND     PRI.TRX_GROUP_CODE = PLC.LOOKUP_CODE
			        AND     PLC.LOOKUP_TYPE_CODE = 'RAW_IOTRX_TYPE'
			        AND     PLC.LOOKUP_CODE = 'B10'
			        UNION ALL
			        SELECT  PRI.ORGANIZATION_ID
			        ,       PRI.TRX_ITEM_ID
			        ,       PRI.TRX_GROUP_CODE
			        ,       PLC.SEGMENT1               BALANCE_TYPE
			        ,       SUBSTR(PLC.LOOKUP_CODE_SEQ, 1, 1)   ORDERBY
			        ,       PRI.TRX_QTY
			        FROM    PSVCM.PSVCM_ACTG_RAW_IODOC_ALL PRI
			        ,       PSVCM.PSVCM_COM_LOOKUP_CODE_ALL PLC
			        WHERE   PRI.ORGANIZATION_ID = 87
			        AND     PRI.PERIOD_YYMM LIKE SUBSTR(:FROMDATE,0,6)
			        AND     PRI.TRX_GROUP_CODE = PLC.LOOKUP_CODE
			        AND     PLC.LOOKUP_TYPE_CODE = 'RAW_IOTRX_TYPE'
			        AND     (   PLC.LOOKUP_CODE = 'B20'
			                OR  PLC.SEGMENT1 IN ('RECEIPT', 'ISSUE'))
			        UNION ALL
			        SELECT  PRI.ORGANIZATION_ID
			        ,       PRI.TRX_ITEM_ID
			        ,       PRI.TRX_GROUP_CODE
			        ,       PLC.SEGMENT1               BALANCE_TYPE
			        ,       SUBSTR(PLC.LOOKUP_CODE_SEQ, 1, 1)   ORDERBY
			        ,       PRI.TRX_QTY
			        FROM    PSVCM.PSVCM_ACTG_RAW_IODOC_ALL PRI
			        ,       PSVCM.PSVCM_COM_LOOKUP_CODE_ALL PLC
			        WHERE   PRI.ORGANIZATION_ID = 87
			        AND     PRI.PERIOD_YYMM = SUBSTR(:FROMDATE,0,6)
			        AND     PRI.TRX_GROUP_CODE = PLC.LOOKUP_CODE
			        AND     PLC.LOOKUP_TYPE_CODE = 'RAW_IOTRX_TYPE'
			        AND     PLC.LOOKUP_CODE = 'E10' ) XX
			,       MTL_SYSTEM_ITEMS_B MSI
			,       PSVCM.PSVCM_COM_LOOKUP_CODE_ALL ZZ
			WHERE   MSI.INVENTORY_ITEM_ID = XX.TRX_ITEM_ID
			AND     MSI.ORGANIZATION_ID = XX.ORGANIZATION_ID
			AND     MSI.SEGMENT1 = MSI.SEGMENT1
			AND     ZZ.LOOKUP_TYPE_CODE = 'RAW_IOTRX_ITEM_CLASS_CODE'
			AND     ZZ.SEGMENT1 = SUBSTR(MSI.SEGMENT1, 2, 2)
			AND     ZZ.LOOKUP_CODE IN ('S1','S2')
			GROUP BY ZZ.LOOKUP_CODE
			, XX.BALANCE_TYPE
			,       XX.ORDERBY
			,       ROLLUP(( TRX_GROUP_CODE
			                ))
			HAVING TRX_GROUP_CODE = 'I10'
			ORDER BY XX.ORDERBY
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_SMP_Normal_Semi_MES_ProdQty($fromDate){
		$sql =
			"SELECT
			     SUM(INP_WGT) AS TOTAL
			FROM
			    TB_M20_SNDIF_STL_INOUT@VINA_MESUSER  A
			WHERE
			    CREATION_TIMESTAMP = (SELECT MAX(CREATION_TIMESTAMP)
			                           FROM TB_M20_SNDIF_STL_INOUT@VINA_MESUSER X
			                           WHERE X.PRD_LOT_NO = A.PRD_LOT_NO
			                             AND PRD_DT LIKE SUBSTR(:fromDate,0,6)||'%'
			                             AND X.TCT_TP_CD = '02'
			                         )
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_SMP_Normal_Semi_MES_Input_Scrap($fromDate){
		$sql =
			"SELECT
			    SUM(CRG_WGT) TOTAL
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
			)
			WHERE 1=1
			    AND HEAT_NO IN  (
			                        SELECT
			                            PRD_LOT_NO
			                        FROM
			                            TB_M20_SNDIF_STL_INOUT@VINA_MESUSER
			                        WHERE
			                            PRD_DT LIKE substr(:fromDate,0,6)||'%'
			                    )
			    AND RAW_MTL_PRD_CD LIKE 'AA%'
			GROUP BY
			    SUBSTR(RAW_MTL_PRD_CD,1,2)
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_SMP_Normal_Semi_MES_Input_Ferro($fromDate){
		$sql =
			"SELECT
			     SUM(CRG_WGT) TOTAL
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
			)
			WHERE 1=1
			    AND HEAT_NO IN  (
			                        SELECT
			                            PRD_LOT_NO
			                        FROM
			                            TB_M20_SNDIF_STL_INOUT@VINA_MESUSER
			                        WHERE
			                            PRD_DT LIKE substr(:fromDate,0,6)||'%'
			                    )
			    AND RAW_MTL_PRD_CD LIKE 'ABA%'
			GROUP BY
			    SUBSTR(RAW_MTL_PRD_CD,1,3)";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_SMP_Normal_Semi_MES_Input_Ore($fromDate){
		$sql=
			"SELECT
			     SUM(CRG_WGT) TOTAL
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
			)
			WHERE 1=1
			    AND HEAT_NO IN  (
			                        SELECT
			                            PRD_LOT_NO
			                        FROM
			                            TB_M20_SNDIF_STL_INOUT@VINA_MESUSER
			                        WHERE
			                            PRD_DT LIKE substr(:fromDate,0,6)||'%'
			                    )
			    AND RAW_MTL_PRD_CD LIKE 'ABF%'
			GROUP BY
			    SUBSTR(RAW_MTL_PRD_CD,1,3)";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_SMP_Normal_FG_MES_Receipt($fromDate){
		$sql =
			"
			SELECT
			    SUM(MTL_WHS_WGT) AS TOTAL
			FROM
			    TB_M20_MTL_WHS_RSL@VINA_MESUSER
			WHERE
			    HEAT_NO IN  (
			                    SELECT
			                        HEAT_NO
			                    FROM
			                        TB_M20_HEAT_COMM@VINA_MESUSER
			                    WHERE
			                        PUR_PO_NO IN    (
			                                            SELECT
			                                                PUR_PO_NO
			                                            FROM
			                                                TB_M10_RAW_MTL_PORD_INF@VINA_MESUSER
			                                            WHERE
			                                                1=1
			                                                AND CNTR_DT LIKE SUBSTR(:fromDate,0,6)||'%'
			                                                AND RAW_MTL_PRD_CD LIKE 'ACB%'
			                                        )
			                )
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_Section_Normal_Semi_MES($fromDate){
		$results = DB::select($this->sql_m30,['fromDate' => $fromDate, 'fac_tp' => '2', 'INST_TP' => 'G']);
		return $results;
	}
	public function get_FG_Shipping_ERP($fromDate){
		$sql =
			"SELECT
			    DECODE(SUBINVENTORY_CODE,'F1101','SMP','F2101','Section','F3101','Deform') as SUBINVENTORY
			    , DECODE(TRX_GROUP_CODE,'I30','Shipping','I50','FG_Return','R10','FG_Receipt') as TRX_GROUP
			    , SUM(TOTAL) AS TOTAL
			FROM
			(
			    SELECT
			        B.SUBINVENTORY_CODE --DECODE(B.SUBINVENTORY_CODE,'F1101','SMP','F2101','Section','F3101','Deform') as SUBINVENTORY
			        , B.TRX_GROUP_CODE --DECODE(B.TRX_GROUP_CODE,'I30','Shipping','I50','FG_Return','R10','FG_Receipt') as TRX_GROUP
			        , SUM(B.TRX_QTY) AS TOTAL
			    FROM
			        PSVWIP_TXNSUM_IODOC_DTL B
			        , MTL_TRANSACTION_TYPES   MTT
			    WHERE
			        B.TRX_ITEM_ID IN
			        (
			            SELECT TAB.ITEM_ID AS ITEM_ID
			            FROM  (SELECT PTI.TRX_ITEM_ID ITEM_ID
			                        ,      MSI.SEGMENT1                         ITEM_CODE
			                   FROM   PSVWIP_TXNSUM_IODOC  PTI
			                   ,      MTL_SYSTEM_ITEMS_B   MSI
			                   WHERE  1=1
			                   AND    PTI.ORGANIZATION_ID                   = MSI.ORGANIZATION_ID
			                   AND    PTI.TRX_ITEM_ID                       = MSI.INVENTORY_ITEM_ID
			                   AND    PTI.ORGANIZATION_ID                   = 87
			                   AND    PTI.PERIOD_YYMM                       = SUBSTR(:fromDate,0,6)
			                   AND    MSI.SEGMENT1                       LIKE 'F%'
			                   AND    SUBSTR(PTI.SUBINVENTORY_CODE, 2,2) LIKE '%'
			                   AND    SUBSTR(MSI.SEGMENT1,1,1)              = 'F'
			                   GROUP BY PTI.TRX_ITEM_ID
			                   ,        MSI.SEGMENT1
			                   ,        MSI.DESCRIPTION  ) TAB
			                   ,(SELECT SEGMENT1 RULE_CODE ,
			                          SEGMENT2 RULE_DESC
			                        FROM PSVBOM_COM_LOOKUP_CODE_ALL
			                        WHERE LOOKUP_TYPE_CODE = 'ITEM_RULE_PRDGRP_CODE'
			                        GROUP BY SEGMENT1 ,
			                          SEGMENT2)     XX1C
			            WHERE   XX1C.RULE_CODE          =   SUBSTR(TAB.ITEM_CODE, 3, 1)
			        )
			    AND    B.TRX_TYPE_ID = MTT.TRANSACTION_TYPE_ID
			    AND    B.ORGANIZATION_ID = 87
			    AND    B.PERIOD_YYMM = SUBSTR(:fromDate,0,6)
			    AND    SUBSTR(B.SUBINVENTORY_CODE, 2,2) LIKE '%'
			    AND    (MTT.TRANSACTION_TYPE_NAME, B.TRX_GROUP_CODE) IN ( SELECT SEGMENT1, LOOKUP_CODE
			                                                              FROM   PSVWIP_COM_LOOKUP_CODE_ALL PLC
			                                                              WHERE  PLC.LOOKUP_TYPE_CODE = 'WIP_IOTRX_GATHERING_RULE'
			                                                                AND    PLC.SEGMENT5 in ('Good.Prod','Fin.Gd<->Semi','Shipping')
			                                                            )
			    GROUP BY B.SUBINVENTORY_CODE, B.TRX_GROUP_CODE
			    UNION
			    SELECT
			        'F1101' AS SUBINVENTORY_CODE
			        , 'I30' AS TRX_GROUP_CODE
			        , 0 AS TOTAL
			    FROM DUAL
			    UNION
			    SELECT
			        'F1101' AS SUBINVENTORY_CODE
			        , 'I50' AS TRX_GROUP_CODE
			        , 0 AS TOTAL
			    FROM DUAL
			    UNION
			    SELECT
			        'F1101' AS SUBINVENTORY_CODE
			        , 'R10' AS TRX_GROUP_CODE
			        , 0 AS TOTAL
			    FROM DUAL
			)
			GROUP BY SUBINVENTORY_CODE, TRX_GROUP_CODE
			order by 1,2
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_FG_Shipping_MES($fromDate){
		$sql =
			"
			SELECT
			    PRD_GRP_CD
			    , SUM(FG_RECEIPT) AS FG_RECEIPT
			    , SUM(FG_RETURN) AS FG_RETURN
			    , SUM(SHIPPING) AS FG_SHIPPING
			FROM
			(
			    SELECT
			           WHS_WGT.PRD_GRP_CD AS PRD_GRP_CD
			         , NVL(WHS_WGT.WHS_WGT, 0) AS FG_RECEIPT
			         , NVL(RETURN_ISS_WGT.RETURN_ISS_WGT, 0) AS FG_RETURN
			         , NVL(ISS_WGT.ISS_WGT, 0) AS SHIPPING

			      FROM
			    (
			        SELECT A.PRD_GRP_CD --DECODE(A.PRD_GRP_CD, 'D', 'Deform-bar', 'E', 'Section', 'Material' ) AS PRD_NM
			             , SUM(A.PRD_WGT) AS WHS_WGT
			          FROM TB_M60_RECEIPT@VINA_MESUSER A
			         WHERE A.WHS_SEQ = ( SELECT MAX(WHS_SEQ) FROM TB_M60_RECEIPT@VINA_MESUSER WHERE PRD_NO = A.PRD_NO )
			           AND A.PRD_PRG_STS_CD IN ( 'C1', 'C2', 'C4', 'C5', 'C6', 'C7', 'C8' )
			           AND SUBSTR(A.PRD_WHS_DT, 0, 6) = SUBSTR(:fromDate, 0, 6)
			         GROUP BY A.PRD_GRP_CD
			    ) WHS_WGT
			    , (
			        SELECT B.PRD_GRP_CD --DECODE(B.PRD_GRP_CD, 'D', 'Deform-bar', 'E', 'Section', 'Material' ) AS PRD_NM
			             , SUM(B.ISS_WGT) AS ISS_WGT
			          FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER A
			             , TB_M60_ISSUE_RSLTS_DTL@VINA_MESUSER B
			         WHERE A.ISS_NO = B.ISS_NO
			           AND A.ISS_CNL_YN = B.ISS_CNL_YN
			           AND B.ISS_CNL_YN = 'N'
			           AND NOT EXISTS (SELECT ISS_NO FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER WHERE ISS_NO = A.ISS_NO AND ISS_CNL_YN = 'Y')
			           AND A.ISS_TP IN ('SD')
			           AND SUBSTR(A.PROC_DT, 0, 6) = SUBSTR(:fromDate, 0, 6)
			         GROUP BY B.PRD_GRP_CD
			    ) ISS_WGT
			    , (
			        SELECT B.PRD_GRP_CD --DECODE(B.PRD_GRP_CD, 'D', 'Deform-bar', 'E', 'Section', 'Material' ) AS PRD_NM
			             , SUM(B.ISS_WGT) AS RETURN_ISS_WGT
			          FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER A
			             , TB_M60_ISSUE_RSLTS_DTL@VINA_MESUSER B
			         WHERE A.ISS_NO = B.ISS_NO
			           AND A.ISS_CNL_YN = B.ISS_CNL_YN
			           AND B.ISS_CNL_YN = 'N'
			           AND NOT EXISTS (SELECT ISS_NO FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER WHERE ISS_NO = A.ISS_NO AND ISS_CNL_YN = 'Y')
			           AND A.ISS_TP IN ('KP')
			           AND SUBSTR(A.PROC_DT, 0, 6) = SUBSTR(:fromDate, 0, 6)
			         GROUP BY B.PRD_GRP_CD
			    ) RETURN_ISS_WGT
			    , (
			        SELECT B.PRD_GRP_CD --DECODE(B.PRD_GRP_CD, 'D', 'Deform-bar', 'E', 'Section', 'Material' ) AS PRD_NM
			             , SUM(B.ISS_WGT) AS SCRAP_ISS_WGT
			          FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER A
			             , TB_M60_ISSUE_RSLTS_DTL@VINA_MESUSER B
			         WHERE A.ISS_NO = B.ISS_NO
			           AND A.ISS_CNL_YN = B.ISS_CNL_YN
			           AND B.ISS_CNL_YN = 'N'
			           AND NOT EXISTS (SELECT ISS_NO FROM TB_M60_ISSUE_RSLTS@VINA_MESUSER WHERE ISS_NO = A.ISS_NO AND ISS_CNL_YN = 'Y')
			           AND A.ISS_TP IN ('KS')
			           AND SUBSTR(A.PROC_DT, 0, 6) = SUBSTR(:fromDate, 0, 6)
			         GROUP BY B.PRD_GRP_CD
			    ) SCRAP_ISS_WGT
			    WHERE WHS_WGT.PRD_GRP_CD = ISS_WGT.PRD_GRP_CD(+)
			      AND WHS_WGT.PRD_GRP_CD = RETURN_ISS_WGT.PRD_GRP_CD(+)
			      AND WHS_WGT.PRD_GRP_CD = SCRAP_ISS_WGT.PRD_GRP_CD(+)
			    UNION
			    SELECT
			           'S' as PRD_GRP_CD
			         , 0 AS FG_RECEIPT
			         , 0 AS FG_RETURN
			         , 0 AS SHIPPING
			    FROM DUAL
			)
			GROUP BY PRD_GRP_CD
			ORDER BY 1
			";
		$results = DB::select($sql,['fromDate' => $fromDate]);
		return $results;
	}
	public function get_Section_Rework_Semi_ERP($fromDate){
		$results = DB::select($this->sql_erp,['fromDate' => $fromDate, 'item_code' => 'E', 'rework_yn' => 'Y']);
		return $results;
	}
	public function get_Section_Rework_Semi_MES($fromDate){
		$results = DB::select($this->sql_m30,['fromDate' => $fromDate, 'fac_tp' => '2', 'INST_TP' => 'E']);
		return $results;
	}
	public function get_Rebar_Normal_Semi_MES($fromDate){
		$results = DB::select($this->sql_m30,['fromDate' => $fromDate, 'fac_tp' => '3', 'INST_TP' => 'G']);
		return $results;
	}
	public function get_Rebar_Rework_Semi_MES($fromDate){
		$results = DB::select($this->sql_m30,['fromDate' => $fromDate, 'fac_tp' => '3', 'INST_TP' => 'E']);
		return $results;
	}


}
