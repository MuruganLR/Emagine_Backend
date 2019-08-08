<?php
//require_once APPPATH.'config/database_constants.php';
class RateCard_model extends CI_Model
{

    public function getRateCardList($uniqSessionID, $userId)
    {
        $usrID = $this->checkUserID($userId, $uniqSessionID);

        if ($usrID != '0') {
            $query = "SELECT * from RateCard ORDER by RateCode DESC";
            $result = $this->db->query($query);
            $data = array();
            if ($result->num_rows() > 0) {
                foreach ($result->result_array() as $row) {
                    $data[] = $row;
                }
            }
            return $data;
        } else {
            return 'Session MisMatch';
        }
    }

    public function insertRateCard($Percentage, $Amount, $ValidFrom, $ValidTill, $Remarks, $C_ID, $uniqSessionID, $userId)
    {
        $usrID = $this->checkUserID($userId, $uniqSessionID);
        if ($usrID != '0') {
            $query = "insert into RateCard(Percentage, Amount, ValidFrom, ValidTill, Remarks, C_ID, M_ID) values(" . $Percentage . ", " . $Amount . ", '" . $ValidFrom . "', '" . $ValidTill . "', '" . $Remarks . "', '" . $C_ID . "', '" . $C_ID . "')";
            $result = $this->db->query($query);
            return true;
        } else {
            return 'Session MisMatch';
        }
    }

    public function checkUserID($userId, $uniqSessionID)
    {

        $select_query = array();
        $select = array(
            'IFNULL (`id`,"") as id',
            'IFNULL (`user_name`,"") as username',
        );
        $arrTables = array(
            '`users`',
        );

        $this->db->select($select);
        $this->db->from($arrTables);
        //if(!empty($input['id']))
        $this->db->where('`id`', $userId)->where('uniqueId', $uniqSessionID);

        $db_select_query = $this->db->get()->result_array();

        if (!empty($db_select_query)) {
            return $db_select_query[0]['id'];
        } else {
            return '0';
        }
    }
    public function getCurrentRateCard()
    {
        $data = array();
        $qry = "select * from RateCard where now() between validfrom and validtill order by RateCode desc limit 1";
        $result = $this->db->query($qry);
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
