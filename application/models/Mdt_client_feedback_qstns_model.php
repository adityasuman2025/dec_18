<?php
	class Mdt_client_feedback_qstns_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'mdt_client_feedback_qstns';
	        $this->load->model('pagination_model');
		}
	
	//function to get all the already existing feedback questions in the database
		public function feedback_qstn_records()
		{
			$this->db->select('*');
			$this->db->from('mdt_client_feedback_qstns');

			$query_run = $this->db->get();

			$result = $query_run->result_array();
			return $result;
		}

	//function to insert the feedback qstn in the databse
		public function add_feedback_qstn_in_db($qstn, $qstn_type)
		{
			$userid = $_SESSION['userid'];

			$data = array(
					'qstn'=> $qstn,
					'qstn_type'=> $qstn_type,
					'created_by' =>$userid,
					'created_on'=> date('Y-m-d'),
					'status' => 1
				);

			$query_run = $this->db->insert('mdt_client_feedback_qstns', $data);

			if($query_run)
				echo 1;
			else
				echo 0;
		}

		public function delt_feedback_qstn_in_db($qstn_id)
		{
			$this->db->where('qstn_id', $qstn_id);
			$query_run = $this->db->delete('mdt_client_feedback_qstns');

			if($query_run)
				echo 1;
			else
				echo 0;
		}

		public function edit_feedback_qstn_in_db($qstn_id, $qstn, $qstn_type)
		{
			$data = array(
					'qstn' => $qstn,
					'qstn_type' => $qstn_type
				);

			$this->db->where('qstn_id', $qstn_id);
			$query_run = $this->db->update('mdt_client_feedback_qstns', $data);

			if($query_run)
				echo 1;
			else
				echo 0;
		}
	}
?>