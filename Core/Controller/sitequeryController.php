<?php
	class sitequeryController extends Eye {
		public function querysiteapplication()
		{
            //$JSON=$this->I("json");
//			$siteid=$JSON->site_id;		
//          $sitename=$JSON->site_name;
//         
//			$siteadmin=$JSON->site_admin;		
//          $siteadminphone=$JSON->site_admin_phone;
//       
//			$siteaddress=$JSON->site_address;		
//          $state=$JSON->state;
            
            
			$db=$this->M();
			
	        
           
            
          
            
           
          
		    $res=$db->select("application",[
		    "organization",
		    "apply_time",
		    "time_begin",
		    "time_end",
		    "app_reason",
		    "applicant_name",
		    "applicant_phone",
		    "place",
		    "app_id",
		    "applicant_email"
		    ],[
		    "application_state[=]"=>0
		   
		    ]
            );
			//date_default_timezone_set('PRC'); 
            $i=0;
            foreach ($res as $key => $value) {
            	//(int)floor((strtotime($enddate)-strtotime($startdate)))
				//var_dump(date("Y-m-d"));
            	 if((int)floor((strtotime($value['apply_time'])-strtotime(date("Y-m-d")))/86400)>=0)
            	{
            		$siteapplications[$i]=$value;
            		$i++;
            		
            		
            	}
            	
            }
			

            echo (json_encode($siteapplications));
            
            
          }
          public function updatestate(){
          	$JSON=$this->I("json");
          	
          	$apply_time=$JSON->apply_time;
          	
          	
			$time_begin=$JSON->time_begin;		
            $time_end=$JSON->time_end;
           
			
            $place=$JSON->place;
         
			$app_id=$JSON->app_id;		
            $applicant_email=$JSON->applicant_email;
           
         
			$db=$this->M();
          	$res_application=$db->update("application", [
              "application_state"=>1
            ],[
            "app_id[=]"=>$app_id
               ]
            );
			//var_dump(date("Y-m-d"));
            $daynum=(int)floor((strtotime($apply_time)-strtotime(date("Y-m-d")))/86400)+1;
            //var_dump($daynum);
            switch($daynum){
				case 1:
				   
				   $res=$db->select("sitetable", [
                     "stateone"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['stateone']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "stateone"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				    
				break;
				case 2:
				$res=$db->select("sitetable", [
                     "statetwo"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['statetwo']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "statetwo"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				    break;
				case 3:
				
				
				
				
				$res=$db->select("sitetable", [
                     "statethree"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['statethree']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "statethree"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				    break;
				case 4:
				$res=$db->select("sitetable", [
                     "statefour"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['statefour']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "statefour"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				
				
				
				
				    break;
				case 5:
				
				$res=$db->select("sitetable", [
                     "statefive"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['statefive']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "statefive"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				
				
				
				    break;
				case 6:
				
				
				
				$res=$db->select("sitetable", [
                     "statesix"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['statesix']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "statesix"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				    break;
	
				case 7:
				
				
				
				$res=$db->select("sitetable", [
                     "stateseven"
                 ],[
                 "site_name"=>$place
                 ]
            ); 
             $state=$res[0]['stateseven']-pow(10,$time_begin-9);
             $res_sitetable=$db->update("sitetable", [
                   "stateseven"=>$state
                   ],[
                   "site_name[=]"=>$place
                   ]
                   );
				    break;
				case 8:
				
				    break;
				
				
			}
	                //var_dump($applicant_email);
		$mailer = $this->doaction("mailer");
		$body = "同学你好，你所预定的".$apply_time."的".$place."已预订成功，请你们在使用会议室的时候爱惜会议室的用品，保持会议室的卫生，并在离开时请将会议室物品复原，谢谢~";
		$subject="场地预定通知";
		$mailer->doaction($applicant_email," ",$body,$subject,"校团委科创部");
            
            
            
            
            
            
           
          }
          public function disallow(){
          	$JSON=$this->I("json");
          	$applicant_email=$JSON->applicant_email;
          	$apply_time=$JSON->apply_time;
          	$place=$JSON->place;
          	$mailer = $this->doaction("mailer");
		$body = "同学你好，你所预定的".$apply_time."的".$place."已被申请，请另选时间，谢谢~";
		$subject="场地预定通知";
		$mailer->doaction($applicant_email," ",$body,$subject,"校团委科创部");
          	
          }
          public function gettimes(){
          	$JSON=$this->I("json");
          	
          	$daynumber=$JSON->daynumber;
          	 $sitename=$JSON->site_name;
          	$db=$this->M();
          	
			switch($daynumber){
				case 1:
				   $res=$db->select("sitetable", [
                     "stateone"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    
				    break;
				case 2:
				$res=$db->select("sitetable", [
                     "statetwo"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				case 3:
				$res=$db->select("sitetable", [
                     "statethree"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				case 4:
				$res=$db->select("sitetable", [
                     "statefour"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				case 5:
				$res=$db->select("sitetable", [
                     "statefive"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				case 6:
				$res=$db->select("sitetable", [
                     "statesix"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
	
				case 7:
				$res=$db->select("sitetable", [
                     "stateseven"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				case 8:
				$res=$db->select("sitetable", [
                     "stateeight"
                 ],[
                 "site_name"=>$sitename
                 ]
            ); 
				    break;
				
				
			}
			
			
			
			switch($daynumber){
				case 1:
				   $state=$res[0]['stateone']-0;
				   break;
				case 2:
				   $state=$res[0]['statetwo']-0;
				   break;
				case 3:
				   $state=$res[0]['statethree']-0;
				   break;
				case 4:
				   $state=$res[0]['statefour']-0;
				   break;
				case 5:
				   $state=$res[0]['statefive']-0;
				   break;
				case 6:
				   $state=$res[0]['statesix']-0;
				   break;
				case 7:
				   $state=$res[0]['stateseven']-0;
				   break;
				   
			}
		
			
			$magnitude=0;
			for( $i=13;$i>=0;$i--){
			       
				if((int)floor($state/pow(10,$i))==1){
					$timequantum[$magnitude]['startpoint']=$i+9;
					$timequantum[$magnitude]['endpoint']=$i+10;
					
					$magnitude++;
					
					$state=$state-pow(10,$i);
				}
			}
			$this->mylog($timequantum);
			
		echo (json_encode($timequantum));
           
           
			
          
           
         
			
          	
          }
            public function getapplysuccess(){
          	$JSON=$this->I("json");
          	$apply_time=$JSON->apply_time;
        
            $db=$this->M();
			
	        $res=$db->select("application",[
		    "organization",
		    "apply_time",
		    "time_begin",
		    "time_end",
		    "app_reason",
		    "applicant_name",
		    "applicant_phone",
		    "place",
		    "applicant_email"
		    ],[
		    "application_state[=]"=>1,
		    //"apply_time[<]"=>date("Y-m-d")
		    ]
            );
            $i=0;
            foreach ($res as $key => $value) {
            	//(int)floor((strtotime($enddate)-strtotime($startdate))/86400)
            	 if($value['apply_time']==$apply_time)
            	{
            		$siteapplysuccess[$i]=$value;
            		$i++;
            		
            		//var_dump($value);
            	}
            	
            }

            echo (json_encode($siteapplysuccess));
          	
          	
          }
     }
			
		

?>