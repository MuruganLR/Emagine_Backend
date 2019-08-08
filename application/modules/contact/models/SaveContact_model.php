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
                $emailToTemp ='k.sparsh@emagine.co.in';
		$template = 'mailQueryAdmin.php';
		$mail = $this->sendContactDataMail($contact_data,$template,$emailToTemp);  //send mail to Emagine Admin
		
		
		$userEmail = $input['contactemail'];
		$templateNm = 'mailToUser.php';
		$mail = $this->sendContactDataMail($contact_data,$templateNm,$userEmail);  //send mail to User who added contactdetails
			
		return (string)$id;
		
	}
	
	
	public function sendContactDataMail($data,$template_html,$emailTo){
	
               try {
                        
                         $this->load->library('email');
                         $this->load->helper('email_config_helper');
			 $config = set_email_configuration();
                         $this->email->initialize($config);
                         $body = $this->load->view($template_html,$data,TRUE);

			if($data['partnerType'] == 'clientPartner')
				$setFromId = 'new.clientpartner@emagine.co.in';
			else
  				$setFromId = 'new.vendorpartner@emagine.co.in';			
                         	

                        $this->email->from($setFromId, 'EmagineX');
                        $this->email->to($emailTo);						
                        $this->email->message($body);
			$this->email->Subject('Contact Us Details');
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