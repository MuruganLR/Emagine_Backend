<?php
//require_once APPPATH.'config/database_constants.php';
require APPPATH . 'third_party/PHPMailerAutoload.php';
class SaveContact_model extends CI_Model
{
	
	public function saveContactDetails($input)
	{		
		
		$id='';
		$contact_data=array(
				'name'  =>$input['contactname'],
				'email' =>$input['contactemail'],
				'subject'  =>$input['subject'],
				'message'  =>$input['message'],
				'partnerType'  =>$input['partnerType']
		);
			
		$contact_table=array(
				'`contact_form` '
		);
		
		$this->db->set($contact_data);
		$result =$this->db->insert($contact_table[0],$contact_data);
		$id=$this->db->insert_id();
		
		
		if($input['partnerType'] == 'vendorPartner')
			$emailTo = 'new.vendorpartner@emagine.co.in';		
											
		if($input['partnerType'] == 'clientPartner')
			$emailTo = 'new.clientpartner@emagine.co.in';
		
		$contact_data['emailTo'] = $emailTo;
		$template = 'mailQueryAdmin.html';
		$mail = $this->sendContactDataMail($contact_data,$template,$emailTo);  //send mail to Emagine Admin
		
		
		$userEmail = $input['contactemail'];
		$templateNm = 'mailToUser.html';
		$mail = $this->sendContactDataMail($contact_data,$templateNm,$userEmail);  //send mail to User who added contact details
			
		return (string)$id;
		
	}
	
	
	public function sendContactDataMail($data,$template_html,$emailTo){
		try {
			$module_name=$this->router->fetch_module();
			$this->load->library('Mail');
			$mail_template_options=$this->config->item('mail_template_options');
					
			$this->mail->setMailBody($data, $template_html ,'HTML',$module_name);
			$this->mail->addAddress($emailTo);
			$this->mail->setFrom('madhura.kulkarni@talentserv.co.in','Emagine');
			$this->mail->Subject =('Contact Us Details');
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