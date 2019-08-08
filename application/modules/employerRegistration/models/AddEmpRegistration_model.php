<?php
//require_once APPPATH.'config/database_constants.php';
require APPPATH . 'third_party/PHPMailerAutoload.php';
class AddEmpRegistration_model extends CI_Model
{

    public function AddRegistrationDetails($input)
    {
        $id = '';
        $password = '';
        $password_hash = '';

        if (!empty($input['employer_type']) && $input['employer_type'] == 'Rock Client') {
            $password = rand(100000, 999999);
            $password_hash = md5($password);
        } else {
            $input['employer_type'] = "";
        }

        $registration_data = array(
            'firstname' => $input['first_name'],
            'lastname' => $input['last_name'],
            'companyname' => $input['company_name'],
            'company_website' => $input['company_website'],
            'company_address' => $input['company_address'],
            'city' => $input['city'],
            'state' => $input['state'],
            'country' => $input['country'],
            'pincode' => $input['pincode'],
            'contact_number' => $input['contact_number'],
            'mobile_number' => $input['mobile_number'],
            'email' => $input['email'],
            'SPOC_name' => $input['SPOC_name'],
            'SPOC_mobile_number' => $input['SPOC_mobile_number'],
            'SPOC_email' => $input['SPOC_email'],
            'industry' => $input['industry'],
            'employee_number' => $input['employee_number'],
            'employer_type' => $input['employer_type'],
            'password' => $password_hash,
        );

        $customer_table = array(
            '`employer` ',
        );

        $this->db->set($registration_data);
        if (!empty($input['id'])) {
            $result = $this->db->update($customer_table[0], $registration_data, array('id' => $input['id']));
            $id = $input['id'];
        } else {
            $result = $this->db->query("select * from employer where email = '" . $registration_data["email"] . "'");
			if ($result->num_rows() > 0) {
				return "Email ID is already registered";
			}
            $result = $this->db->insert($customer_table[0], $registration_data);
            $id = $this->db->insert_id();
            $registration_data["password"] = $password;
            $mail = $this->sendRegistrationMailToEmployer($registration_data);
            $mail = $this->sendMailToEmagineCilentId($registration_data);
        }
        return (string) $id;
    }

    public function sendRegistrationMailToEmployer($data)
    {
        try {

            $this->load->library('email');
            $this->load->helper('email_config_helper');
            $config = set_email_configuration();
            $this->email->initialize($config);

            $template_html = 'registrationMailToEmployer.php';
            $body = $this->load->view($template_html, $data, true);

            $this->email->from('new.clientpartner@emagine.co.in', 'EmagineX');
            $this->email->to($data['email']);
            $this->email->message($body);
            if ($data['employer_type'] == 'Rock Client') {
                $this->email->Subject('RoCk Client - Registered Successfully');
            } else {
                $this->email->Subject('Employer - Registered Successfully');
            }
            $this->email->send();

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }

    }

    public function sendMailToEmagineCilentId($data)
    {
        try {

            $this->load->library('email');
            $this->load->helper('email_config_helper');
            $config = set_email_configuration();
            $this->email->initialize($config);

            $template_html = 'mailToEmagineCilentId.php';
            $body = $this->load->view($template_html, $data, true);

            $this->email->from('new.clientpartner@emagine.co.in', 'EmagineX');
            $this->email->to('new.clientpartner@emagine.co.in');
            $this->email->message($body);
            if ($data['employer_type'] == 'Rock Client') {
                $this->email->Subject('RoCk Client - Registered Successfully');
            } else {
                $this->email->Subject('Employer - Registered Successfully');
            }
            $this->email->send();

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }

    }

}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php
