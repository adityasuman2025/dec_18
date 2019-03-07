<?php
	class Ldt_audit_plan_team_plan_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_audit_plan_team_plan';
		}

	//function to insert the team plan data into the database
        public function insert_audit_plan_team_plan($tracksheet_id, $level, $team_plan_array)
        {
        	//$query = "INSERT INTO ldt_audit_plan_team_plan VALUES";

            $count =  count($team_plan_array);
            $data = array(
                'tracksheet_id' =>$tracksheet_id,
                'level' =>$level
            );

            if($count != 0)
            {
                foreach ($team_plan_array as $key => $value) 
                {
					$date = $value['date'];
					$auditor_id = $value['auditor_id'];
					$type = $value['type'];

                    $data['date'] = $date;
                    $data['auditor_id'] = $auditor_id;
                    $data['type'] = $type;

                    $query_run = $this->db->insert('ldt_audit_plan_team_plan', $data);

					// $text = " ('', '$tracksheet_id', '$level', '$date', '$auditor_id', '$type'),";
					// $query = $query . $text;
                }

                //$query = rtrim($query,',');
                //$query_run = $this->db->query($query);

               return 1;      
            } 
            else
            {
                return 1;
            }    
        }
	
    //function to get audit plan team plan records
        public function get_audit_team_plan_records($tracksheet_id, $level)
        {
            $audit_plan_audit_team_query = "SELECT ldt_audit_plan_team_plan.*, mdt_users.username FROM ldt_audit_plan_team_plan, mdt_users, ldt_audit_certificate WHERE ldt_audit_plan_team_plan.tracksheet_id = $tracksheet_id AND ldt_audit_plan_team_plan.level = $level AND ldt_audit_certificate.ac_id = ldt_audit_plan_team_plan.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id";
            $audit_plan_audit_team_query_run = $this->db->query($audit_plan_audit_team_query);
            $get_audit_team_plan_records = $audit_plan_audit_team_query_run->result_array();

            return $get_audit_team_plan_records;
        } 

    //function to list all the distinct auditors of a audit plan for a stage of a tracksheet
        public function get_distinct_auditors_of_audit_plan($tracksheet_id, $level)
        {
            $query = "SELECT DISTINCT auditor_id FROM ldt_audit_plan_team_plan WHERE tracksheet_id = $tracksheet_id AND level = $level";

            $query_run = $this->db->query($query);
            $result_array = $query_run->result_array();

            return $result_array;
        }
    }
?>