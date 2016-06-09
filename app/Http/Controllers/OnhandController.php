<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class OnhandController extends Controller
{
	public $sql_m20_onhand =
		"
		SELECT
		    'stkWgt' as TP,
		    HEAT_NO AS HEAT_NO ,
		  (
		    SELECT MAX(ACTL_ITM_CD)
		    FROM TB_M20_MTL_RSL@VINA_MESUSER
		    WHERE HEAT_NO = L.HEAT_NO
		      AND MTL_DIMS_CD = L.MTL_DIMS_CD ) AS ACT_ITEM ,
		  STK_WGT AS WGT
		FROM TB_M20_MTL_STK@VINA_MESUSER L
		WHERE STK_DT = (
		    SELECT MAX(STK_DT)
		    FROM TB_M20_MTL_STK@VINA_MESUSER)
		    AND HEAT_NO = :lotno
		union all
		SELECT
		    'notWhsQty' as TP,
		    HEAT_NO AS HEAT_NO ,
		  ACTL_ITM_CD AS ACT_ITEM ,
		  MTL_WGT AS WGT
		FROM TB_M20_MTL_RSL@VINA_MESUSER
		WHERE INSP_PSV_DT <= :dateCheck
		  AND CFM_YN = 'Y'
		  AND NVL(SCR_YN, 'N') = 'N'
		  AND NVL(MTL_WHS_DT, SYSDATE) > :dateCheck
		  AND HEAT_NO = :lotno
		union all
		SELECT
		    'whsReceiptWgt' as TP,
		    HEAT_NO AS HEAT_NO ,
		  (
		    SELECT MAX(ACTL_ITM_CD)
		    FROM TB_M20_MTL_RSL@VINA_MESUSER
		    WHERE HEAT_NO = M.HEAT_NO
		      AND MTL_DIMS_CD = M.MTL_DIMS_CD ) AS ACT_ITEM ,
		  NVL(MTL_WHS_WGT, 0)*-1 AS WGT
		FROM TB_M20_MTL_WHS_RSL@VINA_MESUSER M
		WHERE MTL_WHS_DT > :dateCheck
		  AND HEAT_NO = :lotno
		union all
		SELECT
		    'trnInoutWgt' as TP,
		    HEAT_NO AS HEAT_NO ,
		(
		SELECT MAX(ACTL_ITM_CD)
		FROM TB_M20_MTL_RSL@VINA_MESUSER
		WHERE HEAT_NO = K.HEAT_NO
		  AND MTL_DIMS_CD = K.MTL_DIMS_CD ) AS ACT_ITEM ,
		DECODE(TRN_WK_TP, 'I', 1, -1)*NVL(TRN_WGT, 0) AS WGT
		FROM TB_M20_MTL_TRN@VINA_MESUSER K
		WHERE TRN_END_YN = 'Y'
		AND TRN_REQ_DTM > TO_DATE(:dateCheck, 'YYYYMMDD')+.99999
		AND HEAT_NO = :lotno
		union all
		SELECT
		    'tranferRolling' as TP,
		    HEAT_NO AS HEAT_NO ,
		(
		SELECT MAX(ACTL_ITM_CD)
		FROM TB_M20_MTL_RSL@VINA_MESUSER
		WHERE HEAT_NO = T.HEAT_NO
		  AND MTL_DIMS_CD = T.MTL_DIMS_CD ) AS ACT_ITEM ,
		(DECODE(TRN_WK_TP, 'I', 1, -1)*NVL(TRN_WGT, 0)) AS WGT
		FROM TB_M20_MTL_TRN@VINA_MESUSER T
		WHERE TRN_END_YN = 'Y'
		AND TRN_REQ_DTM >= TO_DATE(:dateCheck, 'YYYYMMDD') - 730
		AND TRN_REQ_DTM <= TO_DATE(:dateCheck, 'YYYYMMDD') + 0.99999
		AND HEAT_NO = :lotno
		union all
		SELECT
		    'rollingResultInput1' as TP,
		    INP_MTL_NO_1 AS HEAT_NO ,
		  INP_ITM_CD_1 AS ACT_ITEM ,
		  SUM(DECODE(TCT_TP_CD, '43', -1, 1) * INP_WGT_1)*-1 AS WGT
		FROM TB_M30_E50M30_04@VINA_MESUSER
		WHERE PRD_DT >= TO_CHAR(TO_DATE(:dateCheck, 'YYYYMMDD') - 730, 'YYYYMMDD')
		  AND PRD_DT <= :dateCheck
		  AND TRANRET IN ('A',
		      'B',
		      'C')
		  AND (INP_MTL_NO_1,
		      INP_ITM_CD_1) IN (
		    SELECT HEAT_NO,
		      (
		        SELECT MAX(ACTL_ITM_CD)
		        FROM TB_M20_MTL_RSL@VINA_MESUSER
		        WHERE HEAT_NO = XX.HEAT_NO
		          AND MTL_DIMS_CD = XX.MTL_DIMS_CD )
		    FROM TB_M20_MTL_TRN@VINA_MESUSER XX
		    WHERE TRN_END_YN = 'Y'
		      AND TRN_REQ_DTM >= TO_DATE(:dateCheck, 'YYYYMMDD') - 730
		      AND TRN_REQ_DTM <= TO_DATE(:dateCheck, 'YYYYMMDD') + 0.99999)
		  AND INP_MTL_NO_1 = :lotno
		GROUP BY INP_MTL_NO_1, INP_ITM_CD_1
		union all
		SELECT
		    'rollingResultInput2' as TP,
		    INP_MTL_NO_2 AS HEAT_NO ,
		  INP_ITM_CD_2 AS ACT_ITEM ,
		  SUM(DECODE(TCT_TP_CD, '43', -1, 1) * INP_WGT_2)*-1 AS WGT
		FROM TB_M30_E50M30_04@VINA_MESUSER
		WHERE PRD_DT >= TO_CHAR(TO_DATE(:dateCheck, 'YYYYMMDD') - 730, 'YYYYMMDD')
		  AND PRD_DT <= :dateCheck
		  AND TRANRET IN ('A',
		      'B',
		      'C')
		  AND (INP_MTL_NO_2,
		      INP_ITM_CD_2) IN (
		    SELECT HEAT_NO,
		      (
		        SELECT MAX(ACTL_ITM_CD)
		        FROM TB_M20_MTL_RSL@VINA_MESUSER
		        WHERE HEAT_NO = XX.HEAT_NO
		          AND MTL_DIMS_CD = XX.MTL_DIMS_CD )
		    FROM TB_M20_MTL_TRN@VINA_MESUSER XX
		    WHERE TRN_END_YN = 'Y'
		      AND TRN_REQ_DTM >= TO_DATE(:dateCheck, 'YYYYMMDD') - 730
		      AND TRN_REQ_DTM <= TO_DATE(:dateCheck, 'YYYYMMDD') + 0.99999)
		    AND INP_MTL_NO_2 = :lotno
		GROUP BY INP_MTL_NO_2, INP_ITM_CD_2
		union all
		SELECT
		    'takeinNotProdInputSndif50' as TP,
		  DECODE(ISS_ITM_CD_1, 'E8A10', PRD_LOT_NO, ISS_MTL_NO_1) AS HEAT_NO ,
		  DECODE(ISS_ITM_CD_1, 'E8A10', INP_ITM_CD, ISS_ITM_CD_1) AS ACT_ITEM ,
		  SUM(ISS_WGT_1 * DECODE(ISS_ITM_CD_1, 'E8A10', -1, 1))*-1 AS WGT
		FROM TB_M20_SNDIF_STL_INOUT@VINA_MESUSER
		WHERE PRD_DT >= TO_CHAR(TO_DATE(:dateCheck, 'YYYYMMDD') - 730, 'YYYYMMDD')
		  AND PRD_DT <= :dateCheck
		  AND ( PRD_LOT_NO = :lotno
		      OR ISS_MTL_NO_1 = :lotno)
		  AND TCT_TP_CD IN ('50')
		  AND TRANRET IN ('A',
		      'B',
		      'C')
		GROUP BY DECODE(ISS_ITM_CD_1, 'E8A10', PRD_LOT_NO, ISS_MTL_NO_1) , DECODE(ISS_ITM_CD_1, 'E8A10', INP_ITM_CD, ISS_ITM_CD_1)
		union all
		SELECT
		    'takeinNotProdInputSndif54' as TP,
		  DECODE(ISS_ITM_CD_1, 'E8A10', PRD_LOT_NO, ISS_MTL_NO_1) AS HEAT_NO ,
		  DECODE(ISS_ITM_CD_1, 'E8A10', INP_ITM_CD, ISS_ITM_CD_1) AS ACT_ITEM ,
		  SUM(ISS_WGT_1 * DECODE(ISS_ITM_CD_1, 'E8A10', -1, 1))*-1 AS WGT
		FROM TB_M20_SNDIF_STL_INOUT@VINA_MESUSER
		WHERE PRD_DT >= TO_CHAR(TO_DATE(:dateCheck, 'YYYYMMDD') - 730, 'YYYYMMDD')
		  AND PRD_DT <= :dateCheck
		  AND ( PRD_LOT_NO = :lotno
		      OR ISS_MTL_NO_1 = :lotno)
		  AND TCT_TP_CD IN ('54')
		  AND TRANRET IN ('A',
		      'B',
		      'C')
		GROUP BY DECODE(ISS_ITM_CD_1, 'E8A10', PRD_LOT_NO, ISS_MTL_NO_1) , DECODE(ISS_ITM_CD_1, 'E8A10', INP_ITM_CD, ISS_ITM_CD_1)
		union all
		SELECT
		    'takeinNotProdInputScrSwt' as TP,
		                HEAT_NO AS HEAT_NO ,
		                ACTL_ITM_CD AS ACT_ITEM ,
		                MTL_WGT AS WGT
		            FROM
		                (
		                    SELECT SUBSTR(MSS.MTL_NO, 0, 6) AS HEAT_NO ,
		                      SUM(MTL.MTL_WGT) AS MTL_WGT ,
		                      MTL.ACTL_ITM_CD
		                    FROM TB_M20_MTL_SCR_SWT@VINA_MESUSER MSS ,
		                      TB_M20_MTL_RSL@VINA_MESUSER MTL
		                    WHERE 1=1
		                      AND MSS.SCR_SWT_TP IN ('A','D','C')
		                      AND MSS.ERP_TRM_YN = 'Y'
		                      AND MSS.MTL_NO = MTL.MTL_NO
		                      AND (
		                              (     TO_DATE(MSS.SWT_DT,'YYYYMMDD') >= to_date(:dateCheck, 'yyyymmdd') - 730
		                                and TO_DATE(MSS.SWT_DT,'YYYYMMDD') <= to_date(:dateCheck, 'yyyymmdd') + 0.99999)
		                           OR (TO_DATE(MSS.SWT_DT,'YYYYMMDD') >= to_date(:dateCheck, 'yyyymmdd'))
		                         )
		                    GROUP BY SUBSTR(MSS.MTL_NO, 0, 6) , MTL.ACTL_ITM_CD
		                )
		                where HEAT_NO = :lotno

		union all
		Select
		    'mov' as TP
		    , HEAT_NO
		    , (select max(actl_itm_cd)
		from TB_M20_MTL_RSL@VINA_MESUSER
		where heat_no = mm.heat_no
		and mtl_dims_cd = mm.mtl_dims_cd ) AS act_item
		    , MOV_WGT as WGT
		from TB_M20_MTL_MOV@VINA_MESUSER mm
		where HEAT_NO = :lotno
		";

	public $sql_m30_onhand =
		"
		     select
     '1' as TP
    , PROD_ACTL_ITM_CD                                                                    AS ITEM_CD
    ,DIV_ROL_LOT_NO                                                                      AS ROL_LOT_NO
    ,PRD_WGT-nvl(add_pcs_wgt,0)                                                          AS CUR_WGT
    ,0                                                                                   AS PROD_WGT
    ,0                                                                                   AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER
WHERE PRD_WHS_DT IS NULL
AND PRG_STS_CD < '9A'
AND SUBSTR(APR_GRD_CD,1,1) IN ('Y','2')
AND DIV_ROL_LOT_NO like :lotno||'%'
union all
select
     '2' as TP
    , PROD_ACTL_ITM_CD                                                                    AS ITEM_CD
    ,DIV_ROL_LOT_NO                                                                      AS ROL_LOT_NO
    ,0                                                                                   AS CUR_WGT
    ,(PRD_WGT-nvl(add_pcs_wgt,0))*-1                                                     AS PROD_WGT
    ,0                                                                                   AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER
WHERE (INSP_PSV_DT > :dateCheck OR INSP_PSV_DT IS NULL)
AND PRD_WHS_DT is null
AND PRG_STS_CD  < '9A'
AND SUBSTR(APR_GRD_CD,1,1) IN ('Y','2')
AND DIV_ROL_LOT_NO like :lotno||'%'
union all
select
    '3' as TP
    , PROD_ACTL_ITM_CD                                                                   AS ITEM_CD
    ,DIV_ROL_LOT_NO                                                                     AS ROL_LOT_NO
    ,0                                                                                  AS CUR_WGT
    ,0                                                                                  AS PROD_WGT
    ,(PRD_WGT-nvl(add_pcs_wgt,0))*1                                                     AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER
WHERE PRD_WHS_DT  >  :dateCheck
AND INSP_PSV_DT <=  :dateCheck
AND PRG_STS_CD  <  '9A'
AND DIV_ROL_LOT_NO like :lotno||'%'
union all
select
      '4' as TP
      , a.PROD_ACTL_ITM_CD                                                              AS ITEM_CD
      ,a.DIV_ROL_LOT_NO                                                                AS ROL_LOT_NO
      ,0                                                                               AS CUR_WGT
      ,0                                                                               AS PROD_WGT
      ,(a.PRD_WGT-nvl(a.add_pcs_wgt,0))*1                                              AS REC_WGT
 from tb_m30_prd_rsl@VINA_MESUSER a
     ,tb_m30_prd_abml_mtl_rsl@VINA_MESUSER b
WHERE a.prd_no = b.prd_no
  and to_char(b.ABML_MTL_DPS_DTM,'yyyymmdd')  > :dateCheck
  and a.INSP_PSV_DT                           <=  :dateCheck
  AND a.PRG_STS_CD                            >=  '9E'
  AND a.DIV_ROL_LOT_NO like :lotno||'%'
union all
select
    '5' as TP
    , (select max(PROD_ACTL_ITM_CD) from tb_m30_prd_rsl@VINA_MESUSER a where a.rol_lot_no = b.add_pcs_lot_no)    AS ITEM_CD
    ,add_pcs_lot_no                                                                                             AS ROL_LOT_NO
    ,add_pcs_wgt                                                                                                AS CUR_WGT
    ,0                                                                                                          AS PROD_WGT
    ,0                                                                                                          AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER b
WHERE PRD_WHS_DT             IS  NULL
AND PRG_STS_CD             <   '9A'
AND SUBSTR(APR_GRD_CD,1,1) IN  ('Y','2')
AND ADD_PCS_LOT_NO like :lotno||'%'
union all
select
    '6' as TP
    , (select max(PROD_ACTL_ITM_CD) from tb_m30_prd_rsl@VINA_MESUSER a where a.rol_lot_no = b.add_pcs_lot_no)   AS ITEM_CD
    ,add_pcs_lot_no                                                                                             AS ROL_LOT_NO
    ,0                                                                                                          AS CUR_WGT
    ,add_pcs_wgt*-1                                                                                             AS PROD_WGT
    ,0                                                                                                          AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER b
WHERE (INSP_PSV_DT > :dateCheck OR INSP_PSV_DT IS NULL)
AND  PRD_WHS_DT             is     null
AND  PRG_STS_CD             <      '9A'
AND ADD_PCS_LOT_NO like :lotno||'%'
AND  SUBSTR(APR_GRD_CD,1,1) IN     ('Y','2')
union all
select
    '7' as TP
    , (select max(PROD_ACTL_ITM_CD) from tb_m30_prd_rsl@VINA_MESUSER a where a.rol_lot_no = b.add_pcs_lot_no)  AS ITEM_CD
    ,add_pcs_lot_no                                                                                           AS ROL_LOT_NO
    ,0                                                                                                        AS CUR_WGT
    ,0                                                                                                        AS PROD_WGT
    ,add_pcs_wgt*1                                                                                            AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER b
WHERE PRD_WHS_DT  >  :dateCheck
AND INSP_PSV_DT <=  :dateCheck
AND PRG_STS_CD  < '9A'
AND ADD_PCS_LOT_NO like :lotno||'%'
union all
select
    '8' as TP
    , (select max(a.PROD_ACTL_ITM_CD) from tb_m30_prd_rsl@VINA_MESUSER a where a.rol_lot_no = b.add_pcs_lot_no)   AS ITEM_CD
     ,b.add_pcs_lot_no                                                                                           AS ROL_LOT_NO
     ,0                                                                                                          AS CUR_WGT
     ,0                                                                                                          AS PROD_WGT
     ,b.add_pcs_wgt*1                                                                                            AS REC_WGT
from tb_m30_prd_rsl@VINA_MESUSER b
    ,tb_m30_prd_abml_mtl_rsl@VINA_MESUSER c
WHERE b.prd_no                               =   c.prd_no
 AND to_char(c.ABML_MTL_DPS_DTM,'yyyymmdd') >   :dateCheck
 AND b.INSP_PSV_DT                          <=  :dateCheck
 AND b.PRG_STS_CD                           >=  '9E'
AND b.ADD_PCS_LOT_NO like :lotno||'%'
union all
select
    '9' as TP
    , (select max(b.PROD_ACTL_ITM_CD) from tb_m30_prd_rsl@VINA_MESUSER b where b.rol_lot_no = a.add_pcs_lot_no) AS ITEM_CD
    ,  a.add_pcs_lot_no AS ROL_LOT_NO
    ,  0 AS CUR_WGT
    ,  0 AS PROD_WGT
    ,  a.add_pcs_wgt*1 AS REC_WGT
 FROM  tb_m30_prd_rsl@VINA_MESUSER a
WHERE  a.INSP_PSV_DT > :dateCheck
    AND a.ADD_PCS_LOT_NO like :lotno||'%'
  AND  EXISTS (SELECT 1 FROM TB_M30_OPR_PRD_RSL@VINA_MESUSER OPR WHERE OPR.ROL_LOT_NO = a.ADD_PCS_LOT_NO AND OPR.FAC_TP = a.FAC_TP AND opr.PRD_DT <= :dateCheck)
		";

	public $sql_m60_onhand =
		"
		select 'tb_m60_prdno_inv' as tp,
		  xx.itm_cd itm_cd ,
		  xx.prd_no ,
		  xx.prd_wgt
		from tb_m60_prdno_inv@VINA_MESUSER xx
		where substr(prd_no, 1, length(prd_no)-3) like :lotno
		union all
		select 'tb_m60_receipt' as tp,
		  xx.sale_itm_cd itm_cd ,
		  xx.prd_no prd_no ,
		  sum(xx.prd_wgt) * -1 prd_wgt
		from (
		    select x.* ,
		      row_number() over(partition by x.prd_no
		        order by x.whs_seq desc) idx
		    from tb_m60_receipt@VINA_MESUSER x) xx
		where idx = 1
		  and prd_whs_dt > :dateCheck
		  and prd_prg_sts_cd in ('C1',
		      'C2',
		      'C4',
		      'C5',
		      'C6',
		      'C7',
		      'C8')
		  and substr(prd_no, 1, length(prd_no)-3) like :lotno
		group by xx.sale_itm_cd , xx.prd_no
		union all
		select 'tb_m60_issue_rslts' as tp,
		  zz.itm_cd ,
		  zz.prd_no ,
		  sum(zz.iss_wgt)
		from tb_m60_issue_rslts@VINA_MESUSER xx ,
		  tb_m60_issue_rslts_dtl@VINA_MESUSER zz
		where xx.iss_no = zz.iss_no
		  and xx.iss_cnl_yn = zz.iss_cnl_yn
		  and xx.iss_tp <> 'WM'
		  and xx.proc_dt > :dateCheck
		  and substr(prd_no, 1, length(prd_no)-3) like :lotno
		group by zz.itm_cd , zz.prd_no
		";

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
				$sql = $this->sql_m20_onhand;
				break;
			case 'M30':
				$sql = $this->sql_m30_onhand;
				break;
			case 'M60':
				$sql = $this->sql_m60_onhand;
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
