<?php
	class Ldt_audit_prog_process_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
            $this->tableName        =   'ldt_audit_prog_process';
		}

	//function to insert audit program proces list in the database
        public function insert_audit_prog_process($tracksheet_id, $process_data)
        {
        	//$query = "INSERT INTO ldt_audit_prog_process VALUES";

            $count =  count($process_data);

            $data = array(
                'tracksheet_id' => $tracksheet_id
            );

            if($count != 0)
            {
                foreach ($process_data as $key => $value) 
                {
                    $process_name = $value['process_name'];
                    $stage2_status = $value['stage2_status'];
                    $surv1_status = $value['surv1_status'];
                    $surv2_status = $value['surv2_status'];

                    $data['process_name'] = $process_name;
                    $data['stage2_status'] = $stage2_status;
                    $data['surv1_status'] = $surv1_status;
                    $data['surv2_status'] = $surv2_status;

                    $query_run = $this->db->insert('ldt_audit_prog_process', $data);

                    // $text = " ('', '$tracksheet_id', '$process_name', '$stage2_status', '$surv1_status', '$surv2_status'),";
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

    //getting the audit program process list of that tracksheet
        public function get_audit_prog_process_list($tracksheet_id)
        {
            $query = "SELECT * FROM ldt_audit_prog_process WHERE tracksheet_id = $tracksheet_id";
            $query_run = $this->db->query($query);
            $result = $query_run->result_array();

            return $result;
        }

    //function to delete any row from the ldt_audit_prog_process with given row_id
        public function delete_audit_prog_process_row($row_id)
        {
            $this->db->where('id', $row_id);
            $query_run = $this->db->delete('ldt_audit_prog_process');

            if($query_run)
                return 1;
            else
                return 0;
        }

    //function to update any row from the ldt_audit_prog_process with given row_id
        public function update_audit_prog_process_row($row_id, $audit_prog_proc_list_input, $stage2_selected, $surv1_selected, $surv2_selected)
        {
            $data = array(
                'process_name' =>$audit_prog_proc_list_input,
                'stage2_status' =>$stage2_selected,
                'surv1_status' =>$surv1_selected,
                'surv2_status' =>$surv2_selected
            );

            $this->db->where('id', $row_id);
            $query_run = $this->db->update('ldt_audit_prog_process', $data);
            
            if($query_run)
                return 1;
            else
                return 0;
        }
	}

?>