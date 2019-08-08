<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class ClientList extends REST_Controller
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
        $this->load->model('ClientList_model');
        $res = $this->ClientList_model->getClientList($uniqSessionID, $userId);
        $this->response($res, 200);
    }
}
