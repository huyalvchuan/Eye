<?php
	class receiveController extends Eye {
		public function receiveform()
		{
            $JSON=$this->I("json");
				
            $organization=$JSON->organization;
           
			$applytime=$JSON->apply_time;		
            $timebegin=$JSON->time_begin;
         
			$timeend=$JSON->time_end;		
            $appreason=$JSON->app_reason;
            $applicantname=$JSON->applicant_name;
            $place=$JSON->place;
          
            
            $applicantemail=$JSON->applicant_email;
            
            $applicantphone=$JSON->applicant_phone;
            
           	
           
         
			$db=$this->M();
			
	       
            

          
		    $res=$db->insert("application", [
            
            "organization"=>$organization,
           
			"apply_time"=>$applytime,		
            "time_begin"=>$timebegin,
         
			"time_end"=>$timeend,		
            "app_reason"=>$appreason,
            "applicant_name"=>$applicantname,
            "applicant_email"=>$applicantemail,
            
            "applicant_phone"=> $applicantphone,
            "place"=>$place,
            "application_state"=>0,
            "create_time"=>date('Y-m-d H:i:s')
              
             ]);
             
            
           
			if($res){
				
					$result['state']=SUCCESS;
				    echo json_encode($result);
			    }
			    else{
			    	
				$result['state']=USER_EXISTS;
				echo json_encode($result);
			 }
		}
		public function querysiteapplication()
		{
            $JSON=$this->I("json");
//			$siteid=$JSON->site_id;		
//          $sitename=$JSON->site_name;
//         
//			$siteadmin=$JSON->site_admin;		
//          $siteadminphone=$JSON->site_admin_phone;
//       
//			$siteaddress=$JSON->site_address;		
//          $state=$JSON->state;
        
         
			$db=$this->M();         
		    $res=$db->select("application",["apply_time"],["application_state[=]"=>0]
            );
            $date=date("Y-m-d");
            var_dump($date);
            
            echo json_encode($res);
          }
	}
			
		

?>