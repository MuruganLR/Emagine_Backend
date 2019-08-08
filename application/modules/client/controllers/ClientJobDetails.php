<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class ClientJobDetails extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $topCompanyDtls = array();
        $allData = array();
        $this->load->model('ClientJobDetails_model');

        $jobDtl = $this->ClientJobDetails_model->clientjobDetails();

        $response = array('jobDetails' => $jobDtl);

        $this->response($response, 200);
    }
}
