<?php
//require_once APPPATH.'config/database_constants.php';
require APPPATH . 'third_party/PHPMailerAutoload.php';
class ForgotPassword_model extends CI_Model
{
	
	public function getForgotPwdDetails($input)
	{		
		//$pwd = md5(trim($input['password']));		
		//session_start();
		$email ='';
		if(!empty($input))	{
			$email = $input['forgotPasswordmail'];
			$loginType = $input['loginType'];
			
			if($loginType == 'Admin')
			{
				
				$select_query=array();
				$select =   array(
						'IFNULL (`id`,"") as id',
						'IFNULL (`user_name`,"") as username', //md5(trim($password)
						'IFNULL (`password_val`,"") as password'
				);
				$arrTables=array(
						'`users`'
				);
				
				$this->db->select($select);
				$this->db->from($arrTables);
				$this->db->where('`user_name`', $input['forgotPasswordmail']);				
				$db_select_query=$this->db->get()->result_array();				
				
			}
			
			if($loginType == 'Vendor')
			{
							
				$selectfields[0]="IFNULL (u.`id`,'') as id";
				$selectfields[1]="IFNULL (u.`user_name`,'') as username";
				$selectfields[2]="IFNULL (c.`partnerCode`,'') as password";
				
				$arrTables[0] =  "`users` "." `u`";
				$arrTables[1] = "`recruiter` "." `r`";
				$arrTables[2] = "`cv_source_list` "." `c`";
				
				$join_condition[0]="r.srcShortName= u.user_name";
				$join_condition[1]="r.srcShortName= c.srcShortName";
				
				$query=$this->db->select($selectfields);		
		        $this->db->from($arrTables[0]);
		        $this->db->join($arrTables[1],$join_condition[0],'inner');
		        $this->db->join($arrTables[2],$join_condition[1],'inner');
				
				$this->db->where('r.`email`', $input['forgotPasswordmail']);
			
				$db_select_query=$this->db->get()->result_array();
						
			}
			
		}
			
		
		
		if (!empty($db_select_query)){			
						
			$data=array(
					'username'  =>$db_select_query[0]['username'],
					'password'  =>$db_select_query[0]['password']
					);
			
			
			$templateNm = 'mailPwdToUSer.html';			
			$mail = $this->sendPassWordMail($data,$templateNm,$email);						
		}
		else{
			$data='No data';
			return $data;		
		}
	}
	
	public function sendPassWordMail($data,$template_html,$emailTo){	
		
		try {
			$module_name=$this->router->fetch_module();
			$this->load->library('Mail');
			$mail_template_options=$this->config->item('mail_template_options');
		
			$this->mail->setMailBody($data, $template_html ,'HTML',$module_name);
			$this->mail->addAddress($emailTo);
			$this->mail->setFrom('new.vendorpartner@emagine.co.in','Emagine');
			$this->mail->Subject =('Forgot Password Details');
			$this->mail->sendMail();
			return $data;
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