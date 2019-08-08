<?php
//require_once APPPATH.'config/database_constants.php';
class GetCVSourceListAPI_model extends CI_Model
{
	
	public function saveVendorDetails($input)
	{				
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');

   $this->db->trans_start();
      //$this->db->truncate('cv_source_list'); //Truncate does not support transaction ... so use delete from tbl
     // $this->db->empty_table('cv_source_list');  // work like delete from table //all records will get deleted.
    //echo"<pre>";print_r($input);die();
	for($i=0;$i<sizeof($input);$i++){	
		
		//	echo "<pre><br><br>";print_r($input[$i]);

		        if(isset($input[$i]['cvSourceDetails']['vendorName']))
		        $vnm =$input[$i]['cvSourceDetails']['vendorName'];
		 	   	else 
		 	   	$vnm='';
		 	   	
		 	   	if(isset($input[$i]['cvSourceDetails']['primaryEmail']))
		 	   		$pemail =$input[$i]['cvSourceDetails']['primaryEmail'];
		 	   	else
		 	   		$pemail='';
		 	   	
		 	   	
		 	   	
		 	   	if(isset($input[$i]['cvSourceDetails']['description']))
		 	   		$description =$input[$i]['cvSourceDetails']['description'];
		 	   	else
		 	   		$description='';
		 	   	
		 	   	
		 	   	if(isset($input[$i]['cvSourceDetails']['mobileNo']))
		 	   		$mobileNo =$input[$i]['cvSourceDetails']['mobileNo'];
		 	   	else
		 	   		$mobileNo='';
		 	   	
		 	   	
		 	   	if(!empty($input[$i]['cvSourceDetails']['secondaryEmailList']))
		 	   		$secondaryEmailList =implode(',',$input[$i]['cvSourceDetails']['secondaryEmailList']);
		 	   	else
		 	   		$secondaryEmailList='';
		 	   	
		 	   	
		 	   	if(isset($input[$i]['cvSourceDetails']['spSpecialization']))
		 	   		$spSpecialization =$input[$i]['cvSourceDetails']['spSpecialization'];
		 	   	else
		 	   		$spSpecialization='';
		 	   	
		 	   	
		 	   	if(isset($input[$i]['cvSourceDetails']['spType']))
		 	   		$spType =$input[$i]['cvSourceDetails']['spType'];
		 	   	else
		 	   		$spType='';
		 	   	
		 	   	
		 	   	if(isset($input[$i]['wowClient']) && $input[$i]['wowClient'] !='')
		 	   		$wowClient =$input[$i]['wowClient'];
		 	   	else
		 	   		$wowClient = 'false';
		 	   	
		 	   	
		   		$sql ="INSERT INTO cv_source_list (applicantSourceId,appSourceCatId,
		   				srcShortName,partnerCode,enabled,applicantSourceTitle,uploadEmail,vendorName,primaryEmail,
		   				description,mobileNo,secondaryEmailList,spSpecialization,spType,wowClient,
		   				API_called_date) values
	  	              ( '" .
	  	                     $input[$i]['applicantSourceId']. "','" .
	  	                     $input[$i]['appSourceCatId']. "','" . 
	  	                     $input[$i]['srcShortName']. "','" .	  	                     
	  	                     $input[$i]['partnerCode']. "','" .
	  	                     $input[$i]['enabled']. "','" .	  	                     			  	                     		
	  	                     $input[$i]['applicantSourceTitle']. "','" .
	  	                     $input[$i]['uploadEmail']. "','" .	  	                     
	  	                     $vnm. "','" .
	  	                     $pemail. "','" .	
	  	                     $description. "','" .
  	                     	 $mobileNo. "','" .
  	                         $secondaryEmailList. "','" .
  	                     	 $spSpecialization. "','" .  	                     
  	                     	 $spType. "','" .
  	                     	 $wowClient. "','" .       
	  	                     $dt.	  	                     
	  	              "' )	  	                     		
		         ON DUPLICATE KEY UPDATE 
	  	              		appSourceCatId = '". $input[$i]['appSourceCatId'] ."' , 
	  	              		srcShortName = '" .$input[$i]['srcShortName']."', 	  	              				
			  	            partnerCode = '". $input[$i]['partnerCode'] ."' , 
			  	            enabled = '" .$input[$i]['enabled']."', 			  	              		  					  	              				
	  	                    applicantSourceTitle = '". $input[$i]['applicantSourceTitle'] ."' , 
	  	      				uploadEmail = '" .$input[$i]['uploadEmail']."', 
	  	              		vendorName = '". $input[$i]['cvSourceDetails']['vendorName'] ."' , 
	  	              		primaryEmail = '" .$input[$i]['cvSourceDetails']['primaryEmail']."',	  	              						  	              			
	  	              		description = '". $description ."' , 
	  	      				mobileNo = '" .$mobileNo."', 
	  	              		secondaryEmailList = '". $secondaryEmailList ."' , 
	  	              		spSpecialization = '" .$spSpecialization."', 
	  	              		spType = '". $spType ."' , 
	  	              		wowClient = '" . $wowClient ."',	  	              				
	  	              		API_called_date = '".$dt."'";
		   		 		   		 
		   		$result = $this->db->query($sql);	 
				
				if($input[$i]['appSourceCatId'] == '6'){ //If user is vendor then add it in users table.
		   		   $this->createUser($input[$i]['srcShortName'], $input[$i]['partnerCode'],'Vendor');
				}
	   }
	
	//die();
	   
	  if($result === false){
	   	$this->db->trans_rollback();
	   }else{
	   	$this->db->trans_complete();
	   }
	   
	   
	        $id=$this->db->insert_id();
			return (string)$id;			
	}
	
	public function createUser($username,$password,$usertype){					
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');				
		$this->db->trans_start();						
		$sql ="INSERT INTO users (user_name,password,usertype,added_date) values
		          ( '" . $username . "','" . md5($password) . "','" . $usertype. "','" . $dt. "' )
		          ON DUPLICATE KEY UPDATE password = '". md5($password) ."', added_date = '".$dt."'";			 			 
		$result = $this->db->query($sql);					
		if($result === false){
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
		}		
		$id=$this->db->insert_id();
		return (string)$id;						
	}
	
	
	public function getTokenDetails(){	
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`access_token`,"") as access_token'			
		);
		$arrTables=array(
				'`token_details`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		$db_select_query=$this->db->get()->result_array();
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php