<?php
//require_once APPPATH.'config/database_constants.php';
class RecruiterJobDetails_model extends CI_Model
{

    public function getJobList()
    {
        $noData = array();

        $SQL = "SELECT o.openingId, o.crStreamName, o.yrsOfExpMin, o.yrsOfExpMax, o.employmentType, o.noPositionsClosed, o.noPositionsTotal, o.statusName, o.statusId, o.max_count, o.max_per_recruiter, o.fixedShare, o.fixedShareInPerc, o.bonusShare, o.bonusShareInPerc FROM `opening_details` o where o.statusId=3 order by o.openingId desc";
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

    public function insertRateCardForVendor($openingId, $buId, $fromDate, $tillDate, $fixedShare, $fixedShareInPerc, $bonusShare, $bonusShareInPerc, $createdBy)
    {
        $sql = "insert into ratecard_buid_mapping(openingId, buId, fromDate, tillDate, fixedShare, fixedShareInPerc, bonusShare, bonusSharePerc, createdBy, updatedBy) values(" . $openingId . ", " . $buId . ",'" . $fromDate . "','" . $tillDate . "', " . $fixedShare . ", " . $fixedShareInPerc . ", " . $bonusShare . ", " . $bonusShareInPerc . ", '" . $createdBy . "', '" . $createdBy . "')";
        $query = $this->db->query($sql);

        if ($bonusShare > 0 || $bonusShareInPerc > 0) {
            $jobdet_qry = "select o.id from opening_details o where o.statusId=3 ";
            if ($openingId > 0) {
                $jobdet_qry = " and o.openingId = " . $openingId;
            }
            if ($buId > 0) {
                $jobdet_qry = " and o.buId = " . $buId;
            }
            $jobdet = $this->db->query($jobdet_qry);
            if ($bonusShareInPerc > 0) {
                $title = $bonusShareInPerc+"%";
            } else if ($bonusShare > 0) {
                $title = "₹"+$bonusShare;
            }
            $title = $title . " Bonus";
            $comment = $title;
            for ($j = 0; $j < sizeof($jobdet); $j++) {
                $sql = "insert into job_category_details(job_id, job_type_id, start_date, end_date, title, comment, converted_by) values(" . $jobdet[$j]->id . ", 1,'" . $fromDate . "','" . $tillDate . "', '" . $title . "', '" . $comment . "', 'A')";
                $query = $this->db->query($sql);
            }
        }
        return true;
    }

    public function deleteRateCardForVendor($id)
    {
        $sql = "delete from ratecard_buid_mapping where id = "+$id+"";
        $query = $this->db->query($sql);

        $sql = "select * from ratecard_buid_mapping where id = "+$id+"";
        $ratedet = $this->db->query($sql);
        for ($j = 0; $j < sizeof($ratedet); $j++) {
            $jobdet_qry = "select o.id from opening_details o where o.statusId=3 ";
            if ($ratedet[$j]->openingId > 0) {
                $jobdet_qry = " and o.openingId = " . $openingId;
            }
            $jobdet = $this->db->query($jobdet_qry);
            for ($i = 0; $i < sizeof($jobdet); $i++) {
                $del_qry = "delete from job_category_details where coverted_by = 'A' and job_id = ".$jobdet[$i]->id." and convert(start_date, DATE) = convert('".$ratedet[$j]->fromDate."', DATE) and end_date = convert('".$ratedet[$j]->tillDate."', DATE)";
                $res = $this->db->query($del_qry);
            }
        }
        return true;
    }

    // select jrs.recruiterId, r.company_name as recruiterName, jrs.openingId, o.openTitle, o.statusId, o.statusName, jrs.postedResumes, jrs.maxCount, jrs.updatedDate from jobid_recruiter_submissions jrs
    // left join opening_details o on o.openingId = jrs.openingId
    // left join recruiter r on r.id = jrs.recruiterId

}
