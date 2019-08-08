<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'modules/rest/libraries/REST_Controller.php';
class GetJobDetails extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $topCompanyDtls = array();
        $allData = array();
        $this->load->model('GetJobDetails_model');

        $jobDtl = $this->GetJobDetails_model->getjobDetails();

        $compnyfilter = $this->GetJobDetails_model->getDataToFilter('compnyName');

        $localityfilter = $this->GetJobDetails_model->getDataToFilter('Locality');

        $salaryfilter = $this->GetJobDetails_model->getDataToFilter('Salary');

        $jobTypefilter = $this->GetJobDetails_model->getDataToFilter('JobType');

        $careerStreamfilter = $this->GetJobDetails_model->getDataToFilter('CareerStream');

        $hotJobFilter = $this->GetJobDetails_model->getDataToFilter('HotJob');

        $jobDtls = array('jobData' => $jobDtl, 'companyFilter' => $compnyfilter, 'localityfilter' => $localityfilter, 'salaryfilter' => $salaryfilter, 'jobTypefilter' => $jobTypefilter, 'careerStreamfilter' => $careerStreamfilter, 'hotJobFilter' => $hotJobFilter);

        $response = array('jobDetails' => $jobDtls);

        $this->response($response, 200);
    }

    public function vendorPipeline_get()
    {
        $vendorId = $this->get('vendorId');

        $this->load->model('GetJobDetails_model');
        $pipelineDtl = $this->GetJobDetails_model->getVendorPipeline($vendorId);
        $response = array('vendor_pipeline' => $pipelineDtl);
        $this->response($response, 200);
    }
}
