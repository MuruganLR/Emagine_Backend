<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class GetPipelineAPI extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $tokenData = array();
        $this->load->model('GetPipelineAPI_model');
        $tokenData = $this->GetPipelineAPI_model->getTokenDetails();
        $limitSize = 0;
        if (!empty($tokenData) && isset($tokenData[0]['access_token'])) {
            $tokenData = $this->GetPipelineAPI_model->callPipeLineAPI($tokenData[0]['access_token']);
        }
    }
}
