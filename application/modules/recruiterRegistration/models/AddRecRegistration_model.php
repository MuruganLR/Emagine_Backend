<?php
//require_once APPPATH.'config/database_constants.php';
require APPPATH . 'third_party/PHPMailerAutoload.php';
class AddRecRegistration_model extends CI_Model
{
	
	public function AddRecRegistrationDetails($input)
	{		
		$id='';
		$registration_data=array(		
				'company_name'  =>$input['company_name'],
//				'company_website' =>$input['company_website'],	
//				'company_address'  =>$input['company_address'],					
				'city'  =>$input['city'],
				'team_size' =>$input['team_size'],
				'specialised_sector' =>$input['specialised_sector'],							
//				'registration_number' =>$input['registration_number'],
				'GST_number' =>$input['GST_number'],
				'owner_name' =>$input['owner_name'],
				'contact_number' =>$input['contact_number'],
				'email' =>$input['email'],				
				'SPOC_name' =>$input['SPOC_name'],
				'SPOC_mobile_number' =>$input['SPOC_mobile_number'],
				'SPOC_email' =>$input['SPOC_email']																
		//		'password'  =>$input['password']				
		);
			
		
		$customer_table=array(				
				'`recruiter` '			
		);

		//$arrtables[0]="`" . ACADEMY_CENTER_PLAYER_TABLE. "` "." `p`";
		$this->db->set($registration_data);	
		if(!empty($input['id']))
		{
			$result = $this->db->update($customer_table[0], $registration_data, array('id'=>$input['id']));
			$id=$input['id'];
		}
		else
		{
			$result =$this->db->insert($customer_table[0],$registration_data);
			$id=$this->db->insert_id();
			
			$mail = $this->sendMailToEmagineVendorId($registration_data);
			$mail = $this->sendRegistrationMailToVendor($registration_data);			
			
		}	
		return (string)$id;
		
			
	}
	
	
	public function sendRegistrationMailToVendor($data){
		try {
			
			$this->load->library('email');
			$this->load->helper('email_config_helper');
			$config = set_email_configuration();
			$this->email->initialize($config);
			$template_html = 'registrationMailToVendor.php';
			$body = $this->load->view($template_html,$data,TRUE);
		
			$this->email->from('new.vendorpartner@emagine.co.in', 'EmagineX');
			$this->email->to($data['email']);
			$this->email->message($body);
			$this->email->Subject('Vendor - Registerd Successfully');
			$this->email->send();			
		
		}
		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		}
	
	}
	
	
	public function sendMailToEmagineVendorId($data){
		try {
			$this->load->library('email');
			$this->load->helper('email_config_helper');
			$config = set_email_configuration();
			$this->email->initialize($config);
			$template_html = 'mailToEmagineVendorId.php';
			$body = $this->load->view($template_html,$data,TRUE);
			
			$this->email->from('new.vendorpartner@emagine.co.in','EmagineX');
			$this->email->to('new.vendorpartner@emagine.co.in');
			$this->email->message($body);
			$this->email->Subject('Vendor - Registerd Successfully');
			$this->email->send();						
		}
		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		}	
	}
		
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php