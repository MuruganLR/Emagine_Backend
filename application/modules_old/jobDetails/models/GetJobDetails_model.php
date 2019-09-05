<?php
//require_once APPPATH.'config/database_constants.php';
class GetJobDetails_model extends CI_Model
{

    public function getjobDetails()
    {
        $noData = array();
        $selectfields[0] = "IFNULL (b.`id`,'') as id";
        $selectfields[1] = "IFNULL (b.`buName`,'') as compnyname";
        $selectfields[2] = "IFNULL (o.`openTitle`,'') as openTitle";
        $selectfields[3] = "IFNULL (o.`ctcBandLowEnd`,'') as ctcBandLowEnd";
        $selectfields[4] = "IFNULL (o.`ctcBandHighEnd`,'') as ctcBandHighEnd";
        $selectfields[5] = "IFNULL (SUBSTRING_INDEX(o.`ctcCurrency`, '_', -1),'') as ctcCurrency";
        $selectfields[6] = "IFNULL (o.`employmentType`,'') as employmentType";
        $selectfields[7] = "IFNULL (o.`yrsOfExpMin`,'') as yrsOfExpMin";
        $selectfields[8] = "IFNULL (o.`yrsOfExpMax`,'') as yrsOfExpMax";
        $selectfields[9] = "IFNULL (o.`office`,'') as office";
        $selectfields[10] = "IFNULL (o.`address`,'') as address";
        $selectfields[11] = "IFNULL (o.`coordinator`,'') as coordinator";
        $selectfields[12] = "IFNULL (o.`hiringManager`,'') as hiringManager";
        $selectfields[13] = "IFNULL (o.`activationDate`,'') as activationDate";
        $selectfields[14] = "IFNULL (o.`crStreamName`,'') as crStreamName";
        $selectfields[15] = "IFNULL (o.`noPositionsTotal`- o.`noPositionsClosed`,'') as openPositions"; // TotalOpen Possitions
        $selectfields[16] = "IFNULL (o.`id`,'') as jobid";
        $selectfields[17] = "IFNULL (group_concat(jcd.`job_type_id`),'') as job_type_id";
        $selectfields[18] = "IFNULL (group_concat(concat(jt.`job_type`,'|',date(jcd.start_date),'|',date(jcd.end_date),'|', jcd.job_type_id,'|',IFNULL(jcd.`comment`,''),'|',IFNULL(jcd.`title`,''))),'') as job_type_data";
        $selectfields[19] = "IFNULL (o.`openingId`,'') as openingId";
        $selectfields[20] = "IFNULL (group_concat(jcd.`comment`),'') as comment";
        $selectfields[21] = "IFNULL (group_concat(jcd.`title`),'') as title";
        $selectfields[22] = "if(jcd.`job_type_id` is Not NULL && jcd.`job_type_id` !='' , 'hotjobclass','') as hotjobclass";

        $arrTables[0] = "`business_unit_list` " . " `b`";
        $arrTables[1] = "`opening_details` " . " `o`";
        $arrTables[2] = "`job_category_details`" . " `jcd`";
        $arrTables[3] = "`job_types` " . " `jt`";

        $join_condition[0] = "b.buId= o.buId";
        $join_condition[1] = "jcd.job_id= o.id and DATE(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5.30 HOUR)) BETWEEN DATE(jcd.start_date) AND DATE(jcd.end_date)";
        $join_condition[2] = "jcd.job_type_id= jt.id";

        $query = $this->db->select($selectfields);

        $this->db->from($arrTables[0]);
        $this->db->join($arrTables[1], $join_condition[0], 'inner')->join($arrTables[2], $join_condition[1], 'left', false)->join($arrTables[3], $join_condition[2], 'left', false);
        $this->db->where('o.`statusId`', '3')->where('(o.`noPositionsTotal`- o.`noPositionsClosed`) !=', 0);
        $this->db->group_by('o.id');
        $this->db->order_by('jt.id', "desc"); // to get hot job first
        $this->db->order_by('o.openingId', "desc"); //to fetch job from newest to oldest
        $query = $this->db->get();
        $result = $query->result();

        for ($j = 0; $j < sizeof($result); $j++) {
            $JobDtlDataArr = array();
            if (isset($result[$j]->job_type_id) && $result[$j]->job_type_id != '' && $result[$j]->job_type_id != null) {
                //$result[$j]->job_type_id = explode(",",$result[$j]->job_type_id);
                $JobDtlData = explode(",", $result[$j]->job_type_data);
                for ($sz = 0; $sz < sizeof($JobDtlData); $sz++) {
                    $JobDtlDataArr[$sz] = explode("|", $JobDtlData[$sz]);
                }
                $result[$j]->job_type_data = $JobDtlDataArr;
            }
        }

        //    echo "<pre>";print_r($result);die();

        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }

    }

    public function getDataToFilter($selectFld)
    {
        $noData = array();
        if ($selectFld == 'Salary') {
            $SQL = "SELECT DISTINCT min(ctcBandLowEnd) as ctcBandLowEnd, max(ctcBandHighEnd) as ctcBandHighEnd from
						(
						select `o`.`id`, o.ctcBandLowEnd,o.ctcBandHighEnd,o.`noPositionsTotal`,o.`noPositionsClosed`
						FROM `business_unit_list` `b` INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId`
						WHERE `o`.`statusId` = '3'
						GROUP BY `o`.`id`
						HAVING sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != 0
						) tbl";
            $query = $this->db->query($SQL);
        } elseif ($selectFld == 'HotJob') {
            $SQL = "select job_type from (SELECT DISTINCT jt.job_type as job_type FROM `business_unit_list` `b`
					INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId`
					 LEFT JOIN `job_category_details` `jcd` ON `jcd`.`job_id`= `o`.`id`
					 LEFT JOIN `job_types` `jt` ON `jcd`.`job_type_id`= `jt`.`id`
					WHERE `o`.`statusId` = '3' GROUP BY `o`.`id`,`jcd`.`job_type_id`
					 HAVING sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != 0
					) tbl where job_type is not null";
            $query = $this->db->query($SQL);
        } else {

            $selectfields[0] = 'b.buName as compnyname'; // just initialisation

            if ($selectFld == 'compnyName') {
                $selectfields[0] = 'b.buName as compnyname';
            }

            if ($selectFld == 'Locality') {
                $selectfields[0] = 'o.address as address';
            }

            if ($selectFld == 'JobType') {
                $selectfields[0] = 'o.employmentType as employmentType';
            }

            if ($selectFld == 'CareerStream') {
                $selectfields[0] = 'o.crStreamName as crStreamName';
            }

            $orderBy = explode(' ', $selectfields[0]);

            //$selectfields[0]= $selectField;

            $arrTables[0] = "`business_unit_list` " . " `b`";
            $arrTables[1] = "`opening_details` " . " `o`";

            $join_condition[0] = "b.buId= o.buId";

            $query = $this->db->select($selectfields);
            $this->db->distinct();
            $this->db->from($arrTables[0]);
            $this->db->join($arrTables[1], $join_condition[0], 'inner');

            $this->db->where('o.`statusId`', '3');
            $this->db->group_by('o.id');
            $this->db->having('sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != ', 0, false);
            $this->db->order_by($orderBy[0], "asc");
            $query = $this->db->get();
        }

        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }

    }

}
