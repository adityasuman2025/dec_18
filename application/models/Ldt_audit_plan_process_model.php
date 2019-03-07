<?php
	class Ldt_audit_plan_process_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_audit_plan_process';
		}

	//function to insert the team plan data into the database
        public function insert_audit_prog_proc_list($tracksheet_id, $level, $process_array)
        {
        	$data = array(
                'tracksheet_id' => $tracksheet_id,
                'level' => $level
                );

            $count =  count($process_array);
            if($count != 0)
            {
                foreach ($process_array as $key => $value) 
                {
					$date_str = $value['date']; 
                    $date_old = strtotime($date_str);
                    $date = date('Y-m-d',$date_old);

                    $time_from = $value['time_from'];
                    $time_to = $value['time_to'];
                    $process_name = $value['process_name'];

					$auditor_id = $value['auditor_id'];
					$type = $value['type'];

                    $data['date'] = $date;
                    $data['time_from'] = $time_from;
                    $data['time_to'] = $time_to;
                    $data['process_name'] = $process_name;

                    $data['auditor_id'] = $auditor_id;
                    $data['type'] = $type;

                    $query_run = $this->db->insert('ldt_audit_plan_process', $data);					
                }
             
                return 1;    
            } 
            else
            {
                return 1;
            }    
        }
	
    //getting the audit program process list of that tracksheet
        public function get_audit_plan_process_list($tracksheet_id, $level)
        {
            $query = "SELECT ldt_audit_plan_process.*, GROUP_CONCAT(ldt_audit_plan_process.type) grouped_type, GROUP_CONCAT(mdt_users.username) grouped_username FROM ldt_audit_plan_process, ldt_audit_certificate, mdt_users WHERE tracksheet_id = $tracksheet_id AND level = $level AND ldt_audit_certificate.ac_id = ldt_audit_plan_process.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id GROUP BY process_name ORDER BY ldt_audit_plan_process.id";
            
            $query_run = $this->db->query($query);
            $result = $query_run->result_array();
            return $result;
        }
    }
?>