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
			
			$mail = $this->sendRegistrationMailToVendor($registration_data);
			$mail = $this->sendMailToEmagineVendorId($registration_data);
			
		}	
		return (string)$id;
		
			
	}
	
	
	public function sendRegistrationMailToVendor($data){
		try {
			$module_name=$this->router->fetch_module();
			$this->load->library('Mail');
			$mail_template_options=$this->config->item('mail_template_options');
				
			$template_html = 'registrationMailToVendor.html';
	
			$this->mail->setMailBody($data, $template_html ,'HTML',$module_name);
			$this->mail->addAddress($data['email']);
			$this->mail->setFrom('madhura.kulkarni@talentserv.co.in','Emagine');
			$this->mail->Subject =('Vendor - Registerd Successfully');
			$this->mail->sendMail();
		}
		catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		}
	
	}
	
	
	public function sendMailToEmagineVendorId($data){
		try {
			$module_name=$this->router->fetch_module();
			$this->load->library('Mail');
			$mail_template_options=$this->config->item('mail_template_options');
	
			$template_html = 'mailToEmagineVendorId.html';
	
			$this->mail->setMailBody($data, $template_html ,'HTML',$module_name);
			//$this->mail->addAddress('new.clientpartner@emagine.co.in');
			$this->mail->addAddress('new.vendorpartner@emagine.co.in');
			$this->mail->setFrom('madhura.kulkarni@talentserv.co.in','Emagine');
			$this->mail->Subject =('Vendor - Registerd Successfully');
			$this->mail->sendMail();
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