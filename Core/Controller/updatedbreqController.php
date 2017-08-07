<?php
	class updatedbreqController extends Eye {
		public function settimeupdate()
		{
           
        
         
			$db=$this->M();
          
		    $res=$db->select("sitetable",[
		    "statetwo",
		    "statethree",
		    "statefour",
		    "statefive",
		    "statesix",
		    "stateseven"
		    
		    ],["site_name[=]"=>"创客空间会议室"]
            );
           
           $db->update("sitetable",[
           "stateone"=>$res[0]["statetwo"]-0,
           "statetwo"=>$res[0]["statethree"]-0,
           "statethree"=>$res[0]["statefour"]-0,
           "statefour"=>$res[0]["statefive"]-0,
           "statefive"=>$res[0]["statesix"]-0,
           "statesix"=>$res[0]["stateseven"]-0,
           "stateseven"=>11111111111111
           ],["site_name[=]"=>"创客空间会议室"]
           );
          $res=$db->select("sitetable",[
		    "statetwo",
		    "statethree",
		    "statefour",
		    "statefive",
		    "statesix",
		    "stateseven"
		    
		    ],["site_name[=]"=>"创业园会议室"]
            );
           
           $db->update("sitetable",[
           "stateone"=>$res[0]["statetwo"]-0,
           "statetwo"=>$res[0]["statethree"]-0,
           "statethree"=>$res[0]["statefour"]-0,
           "statefour"=>$res[0]["statefive"]-0,
           "statefive"=>$res[0]["statesix"]-0,
           "statesix"=>$res[0]["stateseven"]-0,
           "stateseven"=>11111111111111
           ],["site_name[=]"=>"创业园会议室"]
           );
		   $res=$db->select("sitetable",[
		    "statetwo",
		    "statethree",
		    "statefour",
		    "statefive",
		    "statesix",
		    "stateseven"
		    
		    ],["site_name[=]"=>"创业园路演厅"]
            );
           
           $db->update("sitetable",[
           "stateone"=>$res[0]["statetwo"]-0,
           "statetwo"=>$res[0]["statethree"]-0,
           "statethree"=>$res[0]["statefour"]-0,
           "statefour"=>$res[0]["statefive"]-0,
           "statefive"=>$res[0]["statesix"]-0,
           "statesix"=>$res[0]["stateseven"]-0,
           "stateseven"=>11111111111111
           ],["site_name[=]"=>"创业园路演厅"]
           );
            
           
          }
        }

?>