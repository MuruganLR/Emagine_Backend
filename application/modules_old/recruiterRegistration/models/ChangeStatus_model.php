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
			$templateNm = 'mailToUserOnReject.html';
			$mail = $this->sendApplicationStatusChangeMail($registration_data,$templateNm,$userEmail);  //send mail to User when record get rejected
			}
			if($input['status']=='Approve') {
				$templateNm = 'mailToUserOnApprove.html';
			$mail = $this->sendApplicationStatusChangeMail($registration_data,$templateNm,$userEmail);  //send mail to User when record get approved					
			}
				
			
			
			
			$id=$input['id'];
		}
		
		return (string)$id;
		
			
	}
	
	
	public function sendApplicationStatusChangeMail($data,$template_html,$emailTo){
		try {
			$module_name=$this->router->fetch_module();
			$this->load->library('Mail');
			$mail_template_options=$this->config->item('mail_template_options');
				
			$this->mail->setMailBody($data, $template_html ,'HTML',$module_name);
			$this->mail->addAddress($emailTo);
			$this->mail->setFrom('madhura.kulkarni@talentserv.co.in','Emagine');
			$this->mail->Subject =('Application Status');
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