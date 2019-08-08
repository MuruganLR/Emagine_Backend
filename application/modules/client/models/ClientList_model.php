<?php
//require_once APPPATH.'config/database_constants.php';
class ClientList_model extends CI_Model
{

    public function getClientList($uniqSessionID, $userId)
    {
        $usrID = $this->checkUserID($userId, $uniqSessionID);

        if ($usrID != '0') {
            $query = "SELECT e.id, e.firstname, e.lastname, e.companyname, e.company_website, e.company_address, ct.city_name as city, s.state_name as state, c.country_name as country, e.pincode, e.contact_number, e.mobile_number, e.email, e.SPOC_name, e.SPOC_mobile_number, e.SPOC_email, e.industry, e.employee_number, e.password, e.status, e.employer_type FROM employer e left join countries c on c.id = e.country left join states s on s.id = e.state left join cities ct on ct.id = e.city WHERE e.employer_type = 'Rock Client' ORDER by e.id DESC";
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
}
