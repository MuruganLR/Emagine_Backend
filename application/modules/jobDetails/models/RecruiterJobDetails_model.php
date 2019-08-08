<?php
//require_once APPPATH.'config/database_constants.php';
class RecruiterJobDetails_model extends CI_Model
{

    public function getJobList()
    {
        $noData = array();

        $SQL = "SELECT o.openingId, o.crStreamName, o.yrsOfExpMin, o.yrsOfExpMax, o.employmentType, o.noPositionsClosed, o.noPositionsTotal, o.statusName, o.statusId, o.max_count, o.max_per_recruiter, o.fixedShare, o.bonusShare FROM `opening_details` o";
        $query = $this->db->query($SQL);

        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }

    }

    public function getRecruiterJobLogs()
    {
        $noData = array();

        $SQL = "select jrs.recruiterId, r.company_name as recruiterName, jrs.openingId, o.openTitle, o.statusId, o.statusName, jrs.postedResumes, jrs.maxCount, jrs.updatedDate from jobid_recruiter_submissions jrs
        left join opening_details o on o.openingId = jrs.openingId
        left join recruiter r on r.id = jrs.recruiterId";
        $query = $this->db->query($SQL);

        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }
    }

    public function updateMaxCount($openingId, $oldMaxCountRecruiter, $newMaxCountRecruiter, $maxCount, $fixedShare, $bounsShare, $userName)
    {
        $SQL = "update opening_details set max_count = " . $maxCount . ", max_per_recruiter = " . $newMaxCountRecruiter . ", fixedShare = " . $fixedShare . ", bonusShare = " . $bounsShare . ", updatedDate = now() where openingId = " . $openingId;
        $query = $this->db->query($SQL);

        $SQL = "update jobid_recruiter_submissions set maxCount = " . $newMaxCountRecruiter . ", updatedDate = now(), updatedBy = '" . $userName . "' where openingId = " . $openingId . " and maxCount = " . $oldMaxCountRecruiter;
        $query = $this->db->query($SQL);

        return true;
    }

    public function updateMaxCountRecruiter($openingId, $recruiterId, $maxCount, $userName)
    {
        $SQL = "update jobid_recruiter_submissions set maxCount = " . $newMaxCountRecruiter . ", updatedDate = now(), updatedBy = '" . $userName . "' where openingId = " . $openingId . " and recruiterId = " . $recruiterId;
        $query = $this->db->query($SQL);
        return true;
    }

    // select jrs.recruiterId, r.company_name as recruiterName, jrs.openingId, o.openTitle, o.statusId, o.statusName, jrs.postedResumes, jrs.maxCount, jrs.updatedDate from jobid_recruiter_submissions jrs
    // left join opening_details o on o.openingId = jrs.openingId
    // left join recruiter r on r.id = jrs.recruiterId

}
