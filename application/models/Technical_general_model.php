<?php
	class Technical_general_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
		}

		public function query_runner($query)
		{
			$query_run = $this->db->query($query);

	        if($query_run)
	            return 1;
	        else 
	            return 0;
		}
		
	}
?>