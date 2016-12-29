<?php
/**
 * Created by DucTrung.
 * Email: giatrangrua@gmail.com
 * Date: 10/10/2016
 * Time: 9:46 AM
 */

namespace App\Http\Controllers;


class queryIF {
	//IF Status
	public static $sql_if_status =
		"SELECT DECODE(PROCESS_STATUS_CODE,'000',1,'WIP_CANCEL',2,'WIP_READY',3,'NEW',4,5) as STT,
    pmr.process_status_code as process_status_code
          ,count(1)                as count
          ,loc.lookup_desc         as process_status_desc
          ,loc.segment2            as program
    FROM   psvwip_mes_result_tb_all    pmr
          ,psvwip_com_lookup_code_all  loc
    WHERE  1=1
    AND    pmr.process_status_code = loc.lookup_code(+)
    AND    loc.lookup_type_code(+) = 'WIP_PROCESS_STATUS_CODE'
    AND    pmr.transaction_date   >= :fromDate
    AND    pmr.transaction_date   <= :toDate
    GROUP BY pmr.process_status_code, loc.lookup_desc, loc.segment2
    order by DECODE(PROCESS_STATUS_CODE,'000',1,'WIP_CANCEL',2,'WIP_READY',3,'NEW',4,5) "
	;

	//OIT
	public static $sql_oit =
		"SELECT
		'MTL_TRX_PENDING' as TP,
    	COUNT(1) AS TOTAL
		FROM MTL_TRANSACTIONS_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_FLAG <> 3
		UNION ALL
		SELECT
		    'MTL_TRX_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM MTL_TRANSACTIONS_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_FLAG = 3
		UNION ALL
		SELECT
		    'MTL_LOT_TRX_PENDING' as TP,
		    COUNT(1) AS TOTAL
		FROM MTL_TRANSACTION_LOTS_INTERFACE H
		WHERE EXISTS (SELECT 1
		FROM MTL_TRANSACTIONS_INTERFACE L
		WHERE L.ORGANIZATION_ID          = 87
		AND   L.TRANSACTION_INTERFACE_ID = H.TRANSACTION_INTERFACE_ID )
		AND   PROCESS_FLAG <> '3'
		UNION ALL
		SELECT
		    'MTL_LOT_TRX_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM   MTL_TRANSACTION_LOTS_INTERFACE H
		WHERE EXISTS (SELECT 1
		FROM MTL_TRANSACTIONS_INTERFACE L
		WHERE L.ORGANIZATION_ID          = 87
		AND   L.TRANSACTION_INTERFACE_ID = H.TRANSACTION_INTERFACE_ID )
		AND    PROCESS_FLAG = '3'
		UNION ALL
		SELECT
		    'WIP_JOB_PENDING' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_JOB_SCHEDULE_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS <> 3
		UNION ALL
		SELECT
		    'WIP_JOB_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_JOB_SCHEDULE_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS = 3
		UNION ALL
		SELECT
		    'MOV_TRX_PENDING' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_MOVE_TXN_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS <> 3
		UNION ALL
		SELECT
		    'MOV_TRX_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_MOVE_TXN_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS = 3
		UNION ALL
		SELECT
		    'RESOURCE_PENDING' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_COST_TXN_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS <> 3
		UNION ALL
		SELECT
		    'RESOURCE_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM WIP_COST_TXN_INTERFACE
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_STATUS = 3
		UNION ALL
		SELECT
		    'MTL_TEMP_PENDING' as TP,
		    COUNT(1) AS TOTAL
		FROM MTL_MATERIAL_TRANSACTIONS_TEMP
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_FLAG <> '3'
		UNION ALL
		SELECT
		    'MTL_TEMP_ERROR' as TP,
		    COUNT(1) AS TOTAL
		FROM MTL_MATERIAL_TRANSACTIONS_TEMP
		WHERE ORGANIZATION_ID = 87
		AND PROCESS_FLAG = '3'"
	;

	public static $sql_step =
		"WITH V_STEP AS
(
    SELECT
        '1' AS STEP
        , 'SMP SEMI' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE     LIKE SUBSTR(:fromDate,1,6)||'%'
    AND DEPARTMENT_CODE      LIKE '11130'
    AND PROCESS_STATUS_CODE = 'WIP_READY'
    UNION ALL
    SELECT
        '2' AS STEP
        , 'SMP FINISH PRODUCT' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
       AND TRANSACTION_DATE     LIKE SUBSTR(:fromDate,1,6)||'%'
       AND DEPARTMENT_CODE      LIKE '11190'
       AND ASSEMBLY_ITEM_CODE   LIKE 'F_S%'
       AND (PROCESS_STATUS_CODE = 'WIP_READY')
    UNION ALL
    SELECT
        '4' AS STEP
        , 'SMP SHIPPING' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE LIKE SUBSTR(:fromDate,1,6)||'%'
    AND COMPONENT_ITEM_CODE1 LIKE 'FBS%'
    AND PROCESS_STATUS_CODE = 'WIP_READY'
    UNION ALL
    SELECT 
	    '5' AS STEP
	    , 'REBAR SEMI' AS STEP_DESCRIPTION
	    , COUNT(*) AS TOTAL
	FROM PSVWIP_MES_RESULT_TB_ALL
	WHERE 1=1
	AND TRANSACTION_DATE     BETWEEN :fromDate
	                            AND :toDate
	AND DEPARTMENT_CODE     LIKE '13120'
	AND ASSEMBLY_ITEM_CODE  LIKE 'EDD%'
	AND PROCESS_STATUS_CODE = 'WIP_READY'
	AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (    SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                                  FROM PSVWIP_MES_RESULT_TB_ALL
	                                                 WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                                   AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
	                                                   AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
	                                                   AND (    PROCESS_STATUS_CODE =    'NEW'
	                                                        OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                                        OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                                        )
	                                                )
	AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                               FROM PSVWIP_MES_RESULT_TB_ALL
	                                              WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                                AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
	                                                AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
	                                                AND (    PROCESS_STATUS_CODE =    'NEW'
	                                                    OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                                    OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                                    )
	                                              )
    UNION ALL
    SELECT
        '6' AS STEP
        , 'REBAR FINISH' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
               AND TRANSACTION_DATE  BETWEEN :fromDate
                                         AND :toDate
               AND DEPARTMENT_CODE      LIKE '13190'
               AND ASSEMBLY_ITEM_CODE   LIKE 'F_D%'
               AND PROCESS_STATUS_CODE  =    'WIP_READY'   
               AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (   SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                     FROM PSVWIP_MES_RESULT_TB_ALL
                                                    WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                      AND ASSEMBLY_ITEM_CODE    LIKE 'EDD%'
                                                      AND (    PROCESS_STATUS_CODE =    'NEW'
                                                           OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                           OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                           )
                                                   )   
               AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                      FROM PSVWIP_MES_RESULT_TB_ALL
                                                     WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                       AND ASSEMBLY_ITEM_CODE    LIKE 'EDD%'
                                                       AND (    PROCESS_STATUS_CODE =    'NEW'
                                                           OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                           OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                           )
                                                   )
    UNION ALL
    SELECT
        '7' AS STEP
        , 'REBAR REWORK' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE BETWEEN :fromDate
                            AND :toDate
    AND ASSEMBLY_ITEM_CODE LIKE 'EDD%'
    AND PROCESS_STATUS_CODE = 'WIP_READY'
    AND TRANSACTION_TYPE_CODE = '62'
    UNION ALL
    SELECT
        '8' AS STEP
        , 'REBAR SHIPPING' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE BETWEEN :fromDate
                            AND :toDate
    AND COMPONENT_ITEM_CODE1 LIKE 'FDD%'
    AND PROCESS_STATUS_CODE = 'WIP_READY'
    UNION ALL
    SELECT 
        '9' AS STEP
        , 'SECTION SEMI' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE     BETWEEN :fromDate AND :toDate
    AND DEPARTMENT_CODE      LIKE '12120'
    AND ASSEMBLY_ITEM_CODE   LIKE 'E_E%'
    AND PROCESS_STATUS_CODE  =    'WIP_READY'
    AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (    SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                      FROM PSVWIP_MES_RESULT_TB_ALL
                                                     WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                       AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
                                                       AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
                                                       AND (    PROCESS_STATUS_CODE =    'NEW'
                                                            OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                            OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                            )
                                                    )
    AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                   FROM PSVWIP_MES_RESULT_TB_ALL
                                                  WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                    AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
                                                    AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
                                                    AND (    PROCESS_STATUS_CODE =    'NEW'
                                                        OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                        OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                        )
                                                  )
    UNION ALL
    SELECT 
    '10' AS STEP
    , 'SECTION FINISH' AS STEP_DESCRIPTION
    , COUNT(*) AS TOTAL
	FROM PSVWIP_MES_RESULT_TB_ALL
	WHERE 1=1
	   AND TRANSACTION_DATE  BETWEEN :fromDate
	                             AND :toDate
	   AND DEPARTMENT_CODE      LIKE '12190'
	   AND ASSEMBLY_ITEM_CODE   LIKE 'F_E%'
	   AND PROCESS_STATUS_CODE  =    'WIP_READY'       
	   AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (   SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                         FROM PSVWIP_MES_RESULT_TB_ALL
	                                        WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                          AND ASSEMBLY_ITEM_CODE    LIKE 'E_E%'
	                                          AND (    PROCESS_STATUS_CODE =    'NEW'
	                                               OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                               OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                               )
	                                       )       
	   AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                          FROM PSVWIP_MES_RESULT_TB_ALL
	                                         WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                           AND ASSEMBLY_ITEM_CODE    LIKE 'E_E%'
	                                           AND (    PROCESS_STATUS_CODE =    'NEW'
	                                               OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                               OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                               )
	                                       )
    UNION ALL
    SELECT
        '11' AS STEP
        , 'SECTION REWORK' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE BETWEEN :fromDate AND :toDate
    AND ASSEMBLY_ITEM_CODE LIKE 'E_E%'
    AND PROCESS_STATUS_CODE = 'WIP_READY'
    AND TRANSACTION_TYPE_CODE = '62'
    UNION ALL
    SELECT
        '12' AS STEP
        , 'SECTION SHIPPING' AS STEP_DESCRIPTION
        , COUNT(*) AS TOTAL
        FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
        AND TRANSACTION_DATE  BETWEEN :fromDate
                            AND :toDate
        AND COMPONENT_ITEM_CODE1  LIKE 'F_E%'
        AND PROCESS_STATUS_CODE    =    'WIP_READY'
)

SELECT
    '0' AS STEP
    , 'TO WIP_READY' AS STEP_DESCRIPTION
    , COUNT(*) AS TOTAL
FROM PSVWIP_MES_RESULT_TB_ALL
WHERE 1=1
AND TRANSACTION_DATE LIKE SUBSTR(:fromDate,1,6)||'%'
AND TRANSACTION_TYPE_CODE <> 91
AND (
        PROCESS_STATUS_CODE LIKE 'NEW'
        OR
        PROCESS_STATUS_CODE LIKE 'PSV_WIP%'
    )
UNION ALL
SELECT *
FROM V_STEP
UNION ALL
SELECT 
    '13' AS STEP
    , 'TOTAL AVAILABLE' AS STEP_DESCRIPTION
    , SUM(TOTAL) AS TOTAL
FROM V_STEP
UNION ALL
SELECT 
    '14' AS STEP
    , 'TOTAL READY' AS STEP_DESCRIPTION
    , COUNT(*) AS TOTAL
FROM PSVWIP_MES_RESULT_TB_ALL
WHERE 1=1
    AND TRANSACTION_DATE  BETWEEN :fromDate AND :toDate        
    AND PROCESS_STATUS_CODE    =    'WIP_READY'"
	;

	public static $sql_oit_check =
		"SELECT SUM(TOTAL) AS TOTAL
			FROM
			(
			    SELECT
			        'MTL_TRX_PENDING' as TP,
			        COUNT(1) AS TOTAL
			    FROM MTL_TRANSACTIONS_INTERFACE
			    WHERE ORGANIZATION_ID = 87
			    AND PROCESS_FLAG <> 3
			    UNION ALL
			    SELECT
			        'MTL_TRX_ERROR' as TP,
			        COUNT(1) AS TOTAL
			    FROM MTL_TRANSACTIONS_INTERFACE
			    WHERE ORGANIZATION_ID = 87
			    AND PROCESS_FLAG = 3
			    UNION ALL
			    SELECT
			        'MTL_LOT_TRX_PENDING' as TP,
			        COUNT(1) AS TOTAL
			    FROM MTL_TRANSACTION_LOTS_INTERFACE H
			    WHERE EXISTS (SELECT 1
			    FROM MTL_TRANSACTIONS_INTERFACE L
			    WHERE L.ORGANIZATION_ID          = 87
			    AND   L.TRANSACTION_INTERFACE_ID = H.TRANSACTION_INTERFACE_ID )
			    AND   PROCESS_FLAG <> '3'
			    UNION ALL
			    SELECT
			        'MTL_LOT_TRX_ERROR' as TP,
			        COUNT(1) AS TOTAL
			    FROM   MTL_TRANSACTION_LOTS_INTERFACE H
			    WHERE EXISTS (SELECT 1
			    FROM MTL_TRANSACTIONS_INTERFACE L
			    WHERE L.ORGANIZATION_ID          = 87
			    AND   L.TRANSACTION_INTERFACE_ID = H.TRANSACTION_INTERFACE_ID )
			    AND    PROCESS_FLAG = '3'
			    UNION ALL
			    SELECT
				    'WIP_JOB_PENDING' as TP,
				    COUNT(1) AS TOTAL
				FROM WIP_JOB_SCHEDULE_INTERFACE
				WHERE ORGANIZATION_ID = 87
				AND PROCESS_STATUS <> 3
				UNION ALL
				SELECT
				    'WIP_JOB_ERROR' as TP,
				    COUNT(1) AS TOTAL
				FROM WIP_JOB_SCHEDULE_INTERFACE
				WHERE ORGANIZATION_ID = 87
				AND PROCESS_STATUS = 3
				UNION ALL
				SELECT
				    'MOV_TRX_PENDING' as TP,
				    COUNT(1) AS TOTAL
				FROM WIP_MOVE_TXN_INTERFACE
				WHERE ORGANIZATION_ID = 87
				AND PROCESS_STATUS <> 3
				UNION ALL
				SELECT
				    'MOV_TRX_ERROR' as TP,
				    COUNT(1) AS TOTAL
				FROM WIP_MOVE_TXN_INTERFACE
				WHERE ORGANIZATION_ID = 87
				AND PROCESS_STATUS = 3
	)";

	public static $sql_step_0_search =
		"Select IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date like substr(:fromDate,1,6)||'%'
		and TRANSACTION_TYPE_CODE <> 91
		and (
		        process_status_code like 'NEW'
		        or
		        process_status_code like 'PSV_WIP%'
		    )
		";

	public static $sql_step_1_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date     like substr(:fromDate,1,6)||'%'
		and department_code      like '11130'
		and process_status_code = 'WIP_READY'
		";

	public static $sql_step_2_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		   and transaction_date     like substr(:fromDate,1,6)||'%'
		   and department_code      like '11190'
		   and assembly_item_code   like 'F_S%'
		   and (process_status_code = 'WIP_READY')
		";

	public static $sql_step_4_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and TRANSACTION_DATE like substr(:fromDate,1,6)||'%'
		and COMPONENT_ITEM_CODE1 like 'FBS%'
		and PROCESS_STATUS_CODE = 'WIP_READY'
		";

	public static $sql_step_5_search =
		"
	SELECT 
	    IF_EXT_ID
	FROM PSVWIP_MES_RESULT_TB_ALL
	WHERE 1=1
	AND TRANSACTION_DATE     BETWEEN :fromDate
	                            AND :toDate
	AND DEPARTMENT_CODE     LIKE '13120'
	AND ASSEMBLY_ITEM_CODE  LIKE 'EDD%'
	AND PROCESS_STATUS_CODE = 'WIP_READY'
	AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (    SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                                  FROM PSVWIP_MES_RESULT_TB_ALL
	                                                 WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                                   AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
	                                                   AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
	                                                   AND (    PROCESS_STATUS_CODE =    'NEW'
	                                                        OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                                        OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                                        )
	                                                )
	AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                               FROM PSVWIP_MES_RESULT_TB_ALL
	                                              WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                                AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
	                                                AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
	                                                AND (    PROCESS_STATUS_CODE =    'NEW'
	                                                    OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                                    OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                                    )
	                                              )
		";

//	public static $sql_step_6_search =
//		"Select
//		    IF_EXT_ID
//		from PSVWIP_MES_RESULT_TB_ALL
//		where 1=1
//		   and transaction_date  between :fromDate
//		                             and :toDate
//		   and department_code      like '13190'
//		   and assembly_item_code   like 'F_D%'
//		   and process_status_code  =    'WIP_READY'
//		";

	public static $sql_step_6_search =
		"Select IF_EXT_ID    
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		   and transaction_date  between :fromDate
		                             and :toDate
		   and department_code      like '13190'
		   and assembly_item_code   like 'F_D%'
		   and process_status_code  =    'WIP_READY'   
		   and nvl(component_lot_number1, '0')  not in (   select distinct assembly_lot_number
		                                         from PSVWIP_MES_RESULT_TB_ALL
		                                        where transaction_date      like substr(:fromDate,1,6)||'%'
		                                          and assembly_item_code    like 'EDD%'
		                                          and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )   
		   and nvl(component_lot_number2, '0')  not in (select distinct assembly_lot_number
		                                          from PSVWIP_MES_RESULT_TB_ALL
		                                         where transaction_date      like substr(:fromDate,1,6)||'%'
		                                           and assembly_item_code    like 'EDD%'
		                                           and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )";

	public static $sql_step_7_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date between :fromDate
		                        and :toDate
		and assembly_item_code like 'EDD%'
		and process_status_code = 'WIP_READY'
		and TRANSACTION_TYPE_CODE = '62'
		";

	public static $sql_step_8_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date between :fromDate
		                        and :toDate
		and component_item_code1 like 'FDD%'
		and process_status_code = 'WIP_READY'

		";

	public static $sql_step_9_search =
		"
	SELECT 
        IF_EXT_ID
    FROM PSVWIP_MES_RESULT_TB_ALL
    WHERE 1=1
    AND TRANSACTION_DATE     BETWEEN :fromDate AND :toDate
    AND DEPARTMENT_CODE      LIKE '12120'
    AND ASSEMBLY_ITEM_CODE   LIKE 'E_E%'
    AND PROCESS_STATUS_CODE  =    'WIP_READY'
    AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (    SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                      FROM PSVWIP_MES_RESULT_TB_ALL
                                                     WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                       AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
                                                       AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
                                                       AND (    PROCESS_STATUS_CODE =    'NEW'
                                                            OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                            OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                            )
                                                    )
    AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
                                                   FROM PSVWIP_MES_RESULT_TB_ALL
                                                  WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
                                                    AND ASSEMBLY_ITEM_CODE    LIKE 'E_S%'
                                                    AND ASSEMBLY_LOT_NUMBER   IS NOT NULL
                                                    AND (    PROCESS_STATUS_CODE =    'NEW'
                                                        OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
                                                        OR  PROCESS_STATUS_CODE LIKE 'PSV%'
                                                        )
                                                  )
		";

	public static $sql_step_10_search =
		"
	SELECT 
    	IF_EXT_ID
	FROM PSVWIP_MES_RESULT_TB_ALL
	WHERE 1=1
	   AND TRANSACTION_DATE  BETWEEN :fromDate
	                             AND :toDate
	   AND DEPARTMENT_CODE      LIKE '12190'
	   AND ASSEMBLY_ITEM_CODE   LIKE 'F_E%'
	   AND PROCESS_STATUS_CODE  =    'WIP_READY'       
	   AND NVL(COMPONENT_LOT_NUMBER1, '0')  NOT IN (   SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                         FROM PSVWIP_MES_RESULT_TB_ALL
	                                        WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                          AND ASSEMBLY_ITEM_CODE    LIKE 'E_E%'
	                                          AND (    PROCESS_STATUS_CODE =    'NEW'
	                                               OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                               OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                               )
	                                       )       
	   AND NVL(COMPONENT_LOT_NUMBER2, '0')  NOT IN (SELECT DISTINCT ASSEMBLY_LOT_NUMBER
	                                          FROM PSVWIP_MES_RESULT_TB_ALL
	                                         WHERE TRANSACTION_DATE      LIKE SUBSTR(:fromDate,1,6)||'%'
	                                           AND ASSEMBLY_ITEM_CODE    LIKE 'E_E%'
	                                           AND (    PROCESS_STATUS_CODE =    'NEW'
	                                               OR  PROCESS_STATUS_CODE LIKE 'WIP_READY%'
	                                               OR  PROCESS_STATUS_CODE LIKE 'PSV%'
	                                               )
	                                       )
		";

	public static $sql_step_11_search =
		"Select
		    IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date between :fromDate and :toDate
		and assembly_item_code like 'E_E%'
		and process_status_code = 'WIP_READY'
		and TRANSACTION_TYPE_CODE = '62'
		";

	public static $sql_step_12_search =
		"Select
		    IF_EXT_ID
		    from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		    and transaction_date  between :fromDate
		                        and :toDate
		    and component_item_code1  like 'F_E%'
		    and process_status_code    =    'WIP_READY'
		";

	public static $sql_if_not_send =
		"SELECT
		    'M20' as CHAIN
		    , 'Product Result' as TP
		    , COUNT(1) as total
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
		union all
		Select
		    'M30' as CHAIN
		    , 'Not send ERP' as TP
		    , COUNT(*) as total
		from TB_M30_E50M30_04@VINA_MESUSER
		where 1=1
		and PRD_DT between :fromDate and :toDate
		and TRANRET in ('B','C')
		union all
		Select
		    'M30' as CHAIN
		    , 'Not make operation' as TP
		    , COUNT(*) as total
		from tb_m30_opr_prd_rsl@VINA_MESUSER
		where 1=1
		and PRD_DT between :fromDate and :toDate
		and IF_EXT_ID not in ( Select EXT_ID
		                        from TB_M30_E50M30_04@VINA_MESUSER
		                        )
		union all
		Select
		    'M60' as CHAIN
		    , 'Not Send ERP' as TP
		    , COUNT(*) as total
		from TB_M60_E50M60_05@VINA_MESUSER
		where 1=1
		and PRD_DT between :fromDate and :toDate
		and TRANRET in ('B','C')
		";

	public static $sql_step_0_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'WIP_READY'
		where 1=1
		and transaction_date like substr(:fromDate,1,6)||'%'
		and TRANSACTION_TYPE_CODE <> 91
		and (
		        process_status_code like 'NEW'
		         or
		         process_status_code like 'PSV_WIP%'
		    )
		";

	public static $sql_step_1_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		and transaction_date     like substr(:fromDate,1,6)||'%'
		and department_code      like '11130'
		and process_status_code = 'WIP_READY'
		";

	public static $sql_step_2_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		   and transaction_date     like substr(:fromDate,1,6)||'%'
		   and department_code      like '11190'
		   and assembly_item_code   like 'F_S%'
		   and (process_status_code = 'WIP_READY')
		";

	public static $sql_step_4_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set PROCESS_STATUS_CODE = 'NEW'
		where 1=1
		and TRANSACTION_DATE like substr(:fromDate,1,6)||'%'
		and COMPONENT_ITEM_CODE1 like 'FBS%'
		and PROCESS_STATUS_CODE = 'WIP_READY'
		";

	public static $sql_step_5_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		and transaction_date    between :fromDate and :toDate
		and department_code     like '13120'
		and assembly_item_code  like 'EDD%'
		and process_status_code = 'WIP_READY'
		and nvl(component_lot_number1, '0')  not in (    select distinct assembly_lot_number
		                                                  from PSVWIP_MES_RESULT_TB_ALL
		                                                 where transaction_date      like substr(:fromDate,1,6)||'%'
		                                                   and assembly_item_code    like 'E_S%'
		                                                   and assembly_lot_number   is not null
		                                                   and (    process_status_code =    'NEW'
		                                                        or  process_status_code like 'WIP_READY%'
		                                                        or  process_status_code like 'PSV%'
		                                                        )
		                                                )
		and nvl(component_lot_number2, '0')  not in (select distinct assembly_lot_number
		                                               from PSVWIP_MES_RESULT_TB_ALL
		                                              where transaction_date      like substr(:fromDate,1,6)||'%'
		                                                and assembly_item_code    like 'E_S%'
		                                                and assembly_lot_number   is not null
		                                                and (    process_status_code =    'NEW'
		                                                    or  process_status_code like 'WIP_READY%'
		                                                    or  process_status_code like 'PSV%'
		                                                    )
		                                              )
		";

	public static $sql_step_6_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		   and transaction_date  between :fromDate
		                             and :toDate
		   and department_code      like '13190'
		   and assembly_item_code   like 'F_D%'
		   and process_status_code  =    'WIP_READY'   
		   and nvl(component_lot_number1, '0')  not in (   select distinct assembly_lot_number
		                                         from PSVWIP_MES_RESULT_TB_ALL
		                                        where transaction_date      like substr(:fromDate,1,6)||'%'
		                                          and assembly_item_code    like 'EDD%'
		                                          and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )   
		   and nvl(component_lot_number2, '0')  not in (select distinct assembly_lot_number
		                                          from PSVWIP_MES_RESULT_TB_ALL
		                                         where transaction_date      like substr(:fromDate,1,6)||'%'
		                                           and assembly_item_code    like 'EDD%'
		                                           and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )";

	public static $sql_step_7_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code  =    'NEW'
		where 1=1
		and transaction_date between :fromDate
		                    and :toDate
		and assembly_item_code like 'EDD%'
		and process_status_code = 'WIP_READY'
		and TRANSACTION_TYPE_CODE = '62'
		";

	public static $sql_step_8_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		and transaction_date between :fromDate
		                        and :toDate
		and component_item_code1  like 'FDD%'
		and process_status_code = 'WIP_READY'
		";

	public static $sql_step_9_update =
		"
		update
    PSVWIP_MES_RESULT_TB_ALL
	set process_status_code = 'NEW'
	where 1=1
	and transaction_date     between :fromDate and :toDate
	and department_code      like '12120'
	and assembly_item_code   like 'E_E%'
	and process_status_code  =    'WIP_READY'
	and nvl(component_lot_number1, '0')  not in (    select distinct assembly_lot_number
	                                                  from PSVWIP_MES_RESULT_TB_ALL
	                                                 where transaction_date      like substr(:fromDate,1,6)||'%'
	                                                   and assembly_item_code    like 'E_S%'
	                                                   and assembly_lot_number   is not null
	                                                   and (    process_status_code =    'NEW'
	                                                        or  process_status_code like 'WIP_READY%'
	                                                        or  process_status_code like 'PSV%'
	                                                        )
	                                                )
	and nvl(component_lot_number2, '0')  not in (select distinct assembly_lot_number
	                                               from PSVWIP_MES_RESULT_TB_ALL
	                                              where transaction_date      like substr(:fromDate,1,6)||'%'
	                                                and assembly_item_code    like 'E_S%'
	                                                and assembly_lot_number   is not null
	                                                and (    process_status_code =    'NEW'
	                                                    or  process_status_code like 'WIP_READY%'
	                                                    or  process_status_code like 'PSV%'
	                                                    )
	                                              )
		";

	public static $sql_step_10_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		   and transaction_date  between :fromDate and :toDate
		   and department_code      like '12190'
		   and assembly_item_code   like 'F_E%'
		   and process_status_code  =    'WIP_READY'   
		   and nvl(component_lot_number1, '0')  not in (   select distinct assembly_lot_number
		                                         from PSVWIP_MES_RESULT_TB_ALL
		                                        where transaction_date      like substr(:fromDate,1,6)||'%'
		                                          and assembly_item_code    like 'E_E%'
		                                          and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )   
		   and nvl(component_lot_number2, '0')  not in (select distinct assembly_lot_number
		                                          from PSVWIP_MES_RESULT_TB_ALL
		                                         where transaction_date      like substr(:fromDate,1,6)||'%'
		                                           and assembly_item_code    like 'E_E%'
		                                           and (    process_status_code =    'NEW'
		                                               or  process_status_code like 'WIP_READY%'
		                                               or  process_status_code like 'PSV%'
		                                               )
		                                       )
		";

	public static $sql_step_11_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		and transaction_date between :fromDate and :toDate
		and assembly_item_code like 'E_E%'
		and process_status_code = 'WIP_READY'
		and TRANSACTION_TYPE_CODE = '62'
		";

	public static $sql_step_12_update =
		"
		update PSVWIP_MES_RESULT_TB_ALL
		set process_status_code = 'NEW'
		where 1=1
		    and transaction_date  between :fromDate
		                        and :toDate
		    and component_item_code1  like 'F_E%'
		    and process_status_code = 'WIP_READY'
		";

	public $test =
		"Select IF_EXT_ID
		from PSVWIP_MES_RESULT_TB_ALL
		where 1=1
		and transaction_date like substr(:fromDate,1,4)||'%'
		and (
		        process_status_code like 'NEW'
		        or
		        process_status_code like 'PSV_WIP%'
		    )
		    ";

	public static $sql_m30 =
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
			                 SUBSTR(PRD.ADD_PCS_LOT_NO,1,9) || '0' AS ROL_LOT_NO
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
			                AND (SELECT MAX(INSP_PSV_DT) FROM TB_M30_PRD_RSL@VINA_MESUSER RSL WHERE RSL.DIV_ROL_LOT_NO = PRD.ADD_PCS_LOT_NO) like SUBSTR(:fromDate,0,6)||'%'
			                AND PRD.FAC_TP = :FAC_TP
			                AND PRD.ADD_PCS_LOT_NO IS NOT NULL
			                AND SUB.INST_TP LIKE :INST_TP||'%'
			                GROUP BY PRD.ADD_PCS_LOT_NO
			            UNION ALL
			            SELECT
			                 SUBSTR(PRD.ADD_PCS_LOT_NO,1,9) || '0' AS ROL_LOT_NO
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
	public static $sql_erp =
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

	public static $sql_m20_onhand =
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
		  AND NVL(MTL_WHS_DT, TO_CHAR(SYSDATE,'YYYYMMDD')) > :dateCheck
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

	public static $sql_m30_onhand =
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

	public static $sql_m60_onhand =
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

	public static $sql_mobile_onhand =
		"
			SELECT  
			    MSI.SEGMENT1 ITEM_CD,    
			    MSI.INVENTORY_ITEM_ID,  
			    MSI.DESCRIPTION DESCRIPTION, 
			    MSI.PRIMARY_UOM_CODE, 
			    MOQ.SUBINVENTORY_CODE, 
			    SUM(NVL(TRANSACTION_QUANTITY, 0)) QUANTITY 
			FROM  
			    MTL_ONHAND_QUANTITIES MOQ, 
			    MTL_SYSTEM_ITEMS MSI, 
			    MTL_ITEM_LOCATIONS MIL 
			WHERE  
			    MOQ.INVENTORY_ITEM_ID = MSI.INVENTORY_ITEM_ID 
			    AND MOQ.ORGANIZATION_ID = MSI.ORGANIZATION_ID 
			    AND MOQ.LOCATOR_ID = MIL.INVENTORY_LOCATION_ID(+) 
			    AND ( MSI.DESCRIPTION LIKE '%'||:itemCd||'%' 
			        OR MSI.SEGMENT1 LIKE '%'||:itemCd||'%' ) 
			    AND NVL(TRANSACTION_QUANTITY, 0) <> 0 
			GROUP BY  
			    MSI.SEGMENT1,  
			    MOQ.SUBINVENTORY_CODE,  
			    MSI.INVENTORY_ITEM_ID,  
			    MSI.DESCRIPTION,  
			    MSI.PRIMARY_UOM_CODE, 
			    DECODE(MOQ.LOCATOR_ID, NULL, NULL, MIL.SEGMENT1) 
			ORDER BY 1, 2
		";

	public static $sql_mobile_subInventoryList =
		"
		SELECT 
		    SECONDARY_INVENTORY_NAME
		    , DESCRIPTION
		FROM MTL_SECONDARY_INVENTORIES
		WHERE 
		    SECONDARY_INVENTORY_NAME LIKE '%'||:SUBINVENTORY_CODE||'%' 
		    AND SECONDARY_INVENTORY_NAME IN (
		                                        SELECT SUBINVENTORY_CODE
		                                        FROM PSVWIP_CYCLE_COUNT_TRX_ALL
		                                        GROUP BY SUBINVENTORY_CODE 
		                                    )
		ORDER BY SECONDARY_INVENTORY_NAME
		";
	
	public static $sql_mobile_cycleCntDetail =
		"
		SELECT MSI.SEGMENT1 ITEM_CODE ,
		  MSI.DESCRIPTION ITEM_DESC ,
		  CCT.ONHAND_QTY ONHAND_QTY ,
		  CCT.TRX_QTY ACTUAL_QTY
		FROM PSVWIP_CYCLE_COUNT_TRX_ALL CCT ,
		  MTL_SYSTEM_ITEMS MSI
		WHERE CCT.ITEM_ID = MSI.INVENTORY_ITEM_ID
		  AND CCT.ORGANIZATION_ID = MSI.ORGANIZATION_ID
		  AND CCT.SUBINVENTORY_CODE = :SUBINVENTORY_CODE
		"
	;
	
	public static $sql_mobile_transaction_history_detail =
		"
		SELECT 
		    TRANSACTION_DATE TRX_DATE ,
		    SUBINVENTORY_CODE SUBINVENTORY_CODE ,
		    TRANSACTION_TYPE_DESC TRX_TYPE_DESC ,   
		    PRIMARY_QUANTITY TRX_QTY ,
		    (
		        SELECT SUM(NVL(TRANSACTION_QUANTITY, 0))
		        FROM MTL_ONHAND_QUANTITIES
		        WHERE INVENTORY_ITEM_ID = :inventory_item_id) + SUM(PERIVIOUS_PRIMARY_QUANTITY) OVER (
		        ORDER BY TRANSACTION_DATE DESC, TRANSACTION_ID
		    ) DUE_QTY
		FROM 
		    (
		        SELECT 
		            A.SUBINVENTORY_CODE SUBINVENTORY_CODE ,
		            A.TRANSACTION_DATE TRANSACTION_DATE ,
		            A.PRIMARY_QUANTITY PRIMARY_QUANTITY ,
		            NVL(LAG(-A.PRIMARY_QUANTITY) OVER (
		                ORDER BY A.TRANSACTION_DATE DESC , A.TRANSACTION_ID), 0) PERIVIOUS_PRIMARY_QUANTITY ,
		            C.DESCRIPTION TRANSACTION_TYPE_DESC ,
		            A.CREATION_DATE CREATION_DATE ,
		            A.TRANSACTION_ID TRANSACTION_ID
		        FROM 
		            MTL_MATERIAL_TRANSACTIONS A ,
		            MTL_TRANSACTION_TYPES C
		        WHERE A.INVENTORY_ITEM_ID = :inventory_item_id
		        AND A.TRANSACTION_TYPE_ID = C.TRANSACTION_TYPE_ID
		        AND A.TRANSACTION_DATE >= SYSDATE - 600
		        AND A.TRANSACTION_DATE < SYSDATE 
		    )
		";
}