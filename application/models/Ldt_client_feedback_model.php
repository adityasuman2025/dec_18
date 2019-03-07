<?php
	class Ldt_client_feedback_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_client_feedback';
	        $this->load->model('pagination_model');
		}

	//function to get client feedback records
		public function get_client_feedback($tracksheet_id, $level)
		{
			$this->db->select('*');
			$this->db->from('ldt_client_feedback');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('level', $level);

			$query_run = $this->db->get();
			$result = $query_run->result_array();
			return $result;
		}
		
	//function to get all the tracksheets for which feedback has been filled
		public function list_filled_feedback_tracksheets()
		{
			$this->db->select('tracksheet_id');
			$this->db->from('ldt_client_feedback');
			
			$query_run = $this->db->get();
			$result = $query_run->result_array();
			return $result;
		}	

	//function  to insert feedback details for a tracksheet_id in database
	    public function insert_tracksheet_feedback($tracksheet_id, $level, $suggestion, $date)
	    {
	    	$data = array(
	    			'tracksheet_id' =>$tracksheet_id,
	    			'level' =>$level,
	    			'suggestion' =>$suggestion,
	    			'date' =>$date,
	    			'status' => 1,
	    		);

	    	$query_run = $this->db->insert('ldt_client_feedback', $data);

	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }
	}
?>