<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class RateCard extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $getData = $this->get();
        if (isset($getData['uniqSessionID']) && $getData['uniqSessionID'] != '') {
            $uniqSessionID = $getData['uniqSessionID'];
        } else {
            $uniqSessionID = '';
        }

        if (isset($getData['userID']) && $getData['userID'] != '') {
            $userId = $getData['userID'];
        } else {
            $userId = '';
        }

        $topCompanyDtls = array();
        $allData = array();
        $this->load->model('RateCard_model');
        $res = $this->RateCard_model->getRateCardList($uniqSessionID, $userId);
        $this->response($res, 200);
    }

    public function insertRateCard_post()
    {
        $uniqSessionID = $this->get('uniqSessionID');
        $userID = $this->get('userID');
        $Percentage = $this->post('Percentage');
        $Amount = $this->post('Amount');
        $ValidFrom = $this->post('ValidFrom');
        $ValidTill = $this->post('ValidTill');
        $Remarks = $this->post('Remarks');
        $C_ID = $this->post('C_ID');
        $this->load->model('RateCard_model');
        $res = $this->RateCard_model->insertRateCard($Percentage, $Amount, $ValidFrom, $ValidTill, $Remarks, $C_ID, $uniqSessionID, $userId);
        $response = $res;
        $this->response($response, 200);
    }

    public function getCurrentRateCard_get(){
        $this->load->model('RateCard_model');
        $res = $this->RateCard_model->getCurrentRateCard();
        $response = $res;
        $this->response($response, 200);
    }
}
