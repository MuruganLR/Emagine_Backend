<?php
//require_once APPPATH.'config/database_constants.php';
class GetPipelineAPI_model extends CI_Model
{

    public function callPipelineAPI($tokenData)
    {
        $this->load->model('ApiLog_model');
        $transaction = array();
        $this->load->library('curl'); //Ps4822Jl  rohit.naik@talentserv.co.in

        $url = "https://emagine.mynexthire.com/employer/api/client/analytics/get/v2?access_token=" . $tokenData;
        $request_headers = array();
        $ch = curl_init();
        //[2,3,4,5,6,7] -- confirm which buid jobs need to be fetched? and get this list from db
        $jdata = '{"objectType":"VENDOR_APP_SUBMISSION_DETAILS"}';
        //echo "<pre><br>jadata".$jdata;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
            $this->ApiLog_model->insertApiLog("Pipeline", $url, curl_error($ch), false);
            return false;
        } else {
            $pipeline = json_decode($data, true);
            //echo "<pre><br>";print_r($transaction);
            curl_close($ch);
            $this->ApiLog_model->insertApiLog("Pipeline", $url, "", true);
            if (!empty($pipeline) && !isset($pipeline['error'])) {
                $result = $this->savePipelineDetails($pipeline);
                $this->updateJobPipelineDetails();
            }
            return true;
        }
    }

    public function cmp($a, $b)
    {
        if ($a['openingId'] == $b['openingId']) {
            return 0;
        }
        return ($a['openingId'] < $b['openingId']) ? -1 : 1;
    }
    
    public function savePipelineDetails($pipeline)
    {
        var_dump(count($pipeline));
        if (count($pipeline) > 0) {
            $this->db->truncate('pipeline_count');
            foreach ($pipeline as $row) {
                $this->db->insert('pipeline_count', $row);
            }
        }
    }

    public function updateJobPipelineDetails(){
        $this->db->truncate('job_pipeline_details');   
        
        $qry = "insert into `job_pipeline_details`(openingId, sourceId) select DISTINCT openingId, sourceId from pipeline_count";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set sourcing = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 700 AND p.stepId < 800),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set screening = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 800 AND p.stepId < 900),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set assessment = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 900 AND p.stepId < 1000),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set negotiation = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 1000 AND p.stepId < 1100),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set offered = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 1100 AND p.stepId < 1200),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set joined = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 1200 AND p.stepId < 1300),0);";
        $this->db->query($qry);

        $qry = "update job_pipeline_details set `exit` = IFNULL((select sum(p.applicationsCount) from pipeline_count p where p.openingId = job_pipeline_details.openingId and job_pipeline_details.sourceId = p.sourceId and p.stepId > 1300),0);";
        $this->db->query($qry);
    }

    public function getTokenDetails()
    {
        $select = array(
            'IFNULL (`id`,"") as id',
            'IFNULL (`access_token`,"") as access_token',
        );
        $arrTables = array(
            '`token_details`',
        );

        $this->db->select($select);
        $this->db->from($arrTables);
        $db_select_query = $this->db->get()->result_array();
        if (!empty($db_select_query)) {
            return $db_select_query;
        } else {
            return false;
        }

    }

}
