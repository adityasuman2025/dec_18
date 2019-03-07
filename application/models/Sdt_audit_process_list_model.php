<?php
	class Sdt_audit_process_list_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
            $this->tableName        =   'sdt_audit_process_list';
		}

	//function to get the process list of specific scheme system
		public function get_default_process_list($scheme_system)
		{
			$query = "SELECT * FROM sdt_audit_process_list WHERE scheme_system = '$scheme_system'";
			$query_run = $this->db->query($query);

			$result = $query_run->result_array();
			return $result;
		}
	}
?>