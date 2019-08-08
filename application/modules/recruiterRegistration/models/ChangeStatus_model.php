<?php
//require_once APPPATH.'config/database_constants.php';
require APPPATH . 'third_party/PHPMailerAutoload.php';
class ChangeStatus_model extends CI_Model
{
	
	public function ChangeStatus($input)
	{		
		//print_r($input);die();
		$id='';
		$registration_data=array(
				'id'  =>$input['id'],
				'status' =>$input['status']										
		);			
		
		$customer_table=array(				
				'`recruiter` '			
		);

		$this->db->set($registration_data);	
		if(!empty($input['id']))
		{
			$result = $this->db->update($customer_table[0], $registration_data, array('id'=>$input['id']));
			
//Not Req for now to sen mail to Emagine Admin			
// 			$emailTo = 'new.vendorpartner@emagine.co.in';
// 			$template = 'mailStatusToAdmin.html';
// 			$mail = $this->sendContactDataMail($contact_data,$template,$emailTo);  //send mail to Emagine Admin
			
			
			$userEmail = $input['emailid'];
			
			if($input['status']=='Reject'){
			$templateNm = 'mailToUserOnReject.php';
			$mail = $this->sendApplicationStatusChangeMail($registration_data,$templateNm,$userEmail);  //send mail to User when record get rejected
			}
			if($input['status']=='Approve') {
				$templateNm = 'mailToUserOnApprove.php';
			$mail = $this->sendApplicationStatusChangeMail($registration_data,$templateNm,$userEmail);  //send mail to User when record get approved					
			}
													
			$id=$input['id'];
		}
		
		return (string)$id;
		
			
	}
	
	
	public function sendApplicationStatusChangeMail($data,$template_html,$emailTo){
		try {			
			$this->load->library('email');
			$this->load->helper('email_config_helper');
			$config = set_email_configuration();
			$this->email->initialize($config);
			$body = $this->load->view($template_html,$data,TRUE);
			
			$this->email->from('new.vendorpartner@emagine.co.in', 'EmagineX');
			$this->email->to($emailTo);
			$this->email->message($body);
			$this->email->Subject('Application Status');
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