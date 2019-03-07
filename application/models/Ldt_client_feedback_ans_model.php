<?php
	class Ldt_client_feedback_ans_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_client_feedback_ans';
	        $this->load->model('pagination_model');
		}

	//function to get client feedback records
		public function get_client_feedback_anss($tracksheet_id, $level)
		{
			$this->db->select('*');
			$this->db->from('ldt_client_feedback_ans');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('level', $level);

			$query_run = $this->db->get();
			$result = $query_run->result_array();
			return $result;
		}
		
	//function 	to insert feedback answers for a tracksheet_id in database
		public function insert_feedback_in_db($tracksheet_id, $level, $feedback_records)
		{
			$data['tracksheet_id'] = $tracksheet_id;
			$data['level'] = $level;

			foreach ($feedback_records as $key => $feedback_record)
			{
				$data['qstn_id'] = $feedback_record['qstn_id'];
				$data['answer'] = $feedback_record['answer'];
				$data['ans_type'] = $feedback_record['ans_type'];

				$this->db->insert('ldt_client_feedback_ans', $data);
			}

			return 1;
		}

	}
?>