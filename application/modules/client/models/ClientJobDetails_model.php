<?php
//require_once APPPATH.'config/database_constants.php';
class ClientJobDetails_model extends CI_Model
{

    public function clientjobDetails()
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
        $selectfields[23] = "IFNULL(o.max_per_recruiter,0) as max_per_recruiter";
        $selectfields[24] = "IFNULL(o.max_count,0) as max_count";
        $selectfields[25] = "IFNULL(jpd.sourcing,0) as sourcing_count";
        $selectfields[26] = "IFNULL(jpd.screening,0) as screening_count";
        $selectfields[27] = "IFNULL(jpd.assessment,0) as assessment_count";
        $selectfields[28] = "IFNULL(jpd.negotiation,0) as negotiation_count";
        $selectfields[29] = "IFNULL(jpd.offered,0) as offered_count";
        $selectfields[30] = "IFNULL(jpd.joined,0) as joined_count";
        $selectfields[31] = "IFNULL(jpd.exit,0) as exit_count";
        $selectfields[32] = "IFNULL (o.`noPositionsTotal`,'') as noPositionsTotal";
        $selectfields[33] = "IFNULL (o.`noPositionsClosed`,'') as noPositionsClosed";

        $arrTables[0] = "`business_unit_list` " . " `b`";
        $arrTables[1] = "`opening_details` " . " `o`";
        $arrTables[2] = "`job_category_details`" . " `jcd`";
        $arrTables[3] = "`job_types` " . " `jt`";
        $arrTables[4] = "`job_pipeline_details` " . " `jpd`";

        $join_condition[0] = "b.buId= o.buId";
        $join_condition[1] = "jcd.job_id= o.id";
        $join_condition[2] = "jcd.job_type_id= jt.id";
        $join_condition[3] = "jpd.openingId= o.openingId";

        $query = $this->db->select($selectfields);

        $this->db->from($arrTables[0]);
        $this->db->join($arrTables[1], $join_condition[0], 'inner')->join($arrTables[2], $join_condition[1], 'left', false)->join($arrTables[3], $join_condition[2], 'left', false)->join($arrTables[4], $join_condition[3], 'left', false);
        $this->db->where('o.`statusId`', '3')->where('(o.`noPositionsTotal`- o.`noPositionsClosed`) !=', 0);
        $this->db->group_by('o.id');
        // $this->db->order_by('jt.id', "desc"); // to get hot job first
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
}
