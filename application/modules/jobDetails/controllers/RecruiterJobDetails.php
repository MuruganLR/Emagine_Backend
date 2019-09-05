<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class RecruiterJobDetails extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RecruiterJobDetails_model');
    }

    public function joblist_get()
    {
        $jobList = array();
        $jobList = $this->RecruiterJobDetails_model->getJobList();
        $response = array("jobList" => $jobList);
        $this->response($response, 200);
    }

    public function recruiterloglist_get()
    {
        $recruiterLogList = array();
        $recruiterLogList = $this->RecruiterJobDetails_model->getRecruiterJobLogs();
        $response = array("recruiterLogList" => $recruiterLogList);
        $this->response($response, 200);
    }

    public function updateJobMaxCount_post()
    {
        $openingId = $this->post('openingId');
        $oldMaxCountRecruiter = $this->post('oldMaxCountRecruiter');
        $newMaxCountRecruiter = $this->post('newMaxCountRecruiter');
        $maxCount = $this->post('maxCount');
        $fixedShare = $this->post('fixedShare');
        $bonusShare = $this->post('bonusShare');
        $userName = $this->post('userName');
        $res = $this->RecruiterJobDetails_model->updateMaxCount($openingId, $oldMaxCountRecruiter, $newMaxCountRecruiter, $maxCount, $fixedShare, $bonusShare, $userName);
        $response = $res;
        $this->response($response, 200);
    }

    public function updateRecruiterMaxCount_post()
    {
        $openingId = $this->post('openingId');
        $recruiterId = $this->post('recruiterId');
        $maxCount = $this->post('maxCount');
        $userName = $this->post('userName');
        $res = $this->RecruiterJobDetails_model->updateMaxCountRecruiter($openingId, $recruiterId, $maxCount, $userName);
        $response = $res;
        $this->response($response, 200);
    }

    public function insertRateCardForVendor_post()
    {
        $openingId = $this->post('openingId');
        $buId = $this->post('buId');
        $fromDate = $this->post('fromDate');
        $tillDate = $this->post('tillDate');
        $fixedShare = $this->post('fixedShare');
        $fixedShareInPerc = $this->post('fixedShareInPerc');
        $bonusShare = $this->post('bonusShare');
        $bonusShareInPerc = $this->post('bonusShareInPerc');
        $createdBy = $this->post('createdBy');
        $res = $this->RecruiterJobDetails_model->insertRateCardForVendor($openingId, $buId, $fromDate, $tillDate, $fixedShare, $fixedShareInPerc, $bonusShare, $bonusShareInPerc, $createdBy);
        $response = $res;
        $this->response($response, 200);
    }
    
    public function deleteRateCardForVendor_get()
    {
        $id = $this->get('id');
        $res = $this->RecruiterJobDetails_model->deleteRateCardForVendor($id);
        $response = $res;
        $this->response($response, 200);
    }

}
