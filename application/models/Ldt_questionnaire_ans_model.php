<?php
	class Ldt_questionnaire_ans_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_questionnaire_ans';
	        $this->load->model('pagination_model');
		}

	//functon to get the questionnaire answers for a tracksheet_id and page_id  
		public function get_questionnaire_form_anss($tracksheet_id, $page_id)
		{
			$this->db->select('*');
			$this->db->from('ldt_questionnaire_ans');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('page_id', $page_id);
			$this->db->order_by('ans_id', 'desc');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}

	//function to update answers in to the database according to their ans_id
		public function update_ans_in_db($records)
		{
			$userid = $_SESSION['userid'];

			$count = count($records);
			if($count != 0)
			{
				$c = 0;
				foreach ($records as $key => $record)
				{
					$ans_id = $record['ans_id'];
					$answer = $record['answer'];

					$data = array(
						'answer' => $answer,
						'edited_by' => $userid,
						'edited_on' => date('Y-m-d')
					);

					$this->db->where('ans_id', $ans_id);
					$query_run = $this->db->update('ldt_questionnaire_ans', $data);

					if($query_run)
						$c++;
				}

				if($c == 2 OR $c ==1)
					return 1;
				else
					return 0;
			}
			else
			{
				return 1;
			}
		}	

	//function to insert new answers in the database
		public function ins_ans_in_db($tracksheet_id, $page_id, $qstn_id, $records)
		{
			$userid = $_SESSION['userid'];

			$count = count($records);
			if($count != 0)
			{
				$c = 0;
				foreach ($records as $key => $record)
				{
					$ans_type = $record['ans_type'];
					$answer = $record['answer'];

					$data = array(
						'tracksheet_id' => $tracksheet_id,
						'page_id' => $page_id,
						'qstn_id' => $qstn_id,
						'ans_type' => $ans_type,
						'answer' => $answer,

						'created_by' => $userid,
						'created_on' => date('Y-m-d h:i:s'),
						'status' => 1
					);

					$query_run = $this->db->insert('ldt_questionnaire_ans', $data);

					if($query_run)
						$c++;
				}

				if($c == 2 OR $c ==1)
					return 1;
				else
					return 0;
			}
			else
			{
				return 1;
			}
		}
	
	//functon to get the questionnaire answers for a qstn_id
		public function get_questionnaire_form_qstn_anss($qstn_id)
		{
			$this->db->select('*');
			$this->db->from('ldt_questionnaire_ans');
			$this->db->where('qstn_id', $qstn_id);
			$this->db->order_by('ans_id', 'desc');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}
	}
?>