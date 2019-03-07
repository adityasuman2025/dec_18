<?php
	class Mdt_questionnaire_qstns_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'mdt_questionnaire_qstns';
	        $this->load->model('pagination_model');
		}

	//function to add texts sample questions in db
		public function add_text_sample_qstns_in_db($page_id, $records)
		{
			$userid = $_SESSION['userid'];

			$data = array(
				'page_id' => $page_id,
				'added_by' => $userid,
				'added_on' => date('Y-m-d'),
				'status' => 1
				);

            $count =  count($records);
            $insert_id = 0;
            $query_run = 0;

            if($count != 0)
            {
                foreach ($records as $key => $value) 
                {
					$data['qstn_title'] = $value['qstn_title'];
					$data['qstn_data'] = $value['qstn_data'];
					$data['qstn_type'] = $value['qstn_type'];
					$data['qstn_help'] = $value['qstn_help'];
					$data['qstn_parent'] = $value['qstn_parent'];

					$query_run = $this->db->insert('mdt_questionnaire_qstns', $data);

					if($key ==0)
					{
						$insert_id = $this->db->insert_id();
					}
                }
               
				if($query_run)
					return $insert_id;
				else
					return 0;        
            } 
            else
            {
                return 1;
            }  		
		}
	
	//function to get all questions of a questionnaire form of a page_id
		public function get_questionnaire_form_qstns($page_id)
		{
			$this->db->select('*');
			$this->db->from('mdt_questionnaire_qstns');
			$this->db->where('page_id', $page_id);

			$query_run = $this->db->get();			
			$result = $query_run->result_array();

			return $result;
		}

	//function to delete a qstn from database
	    public function delete_qstn_from_db($qstn_id)
	    {
			$this->db->where('qstn_id', $qstn_id);
   			$this->db->delete('mdt_questionnaire_qstns'); 
	    }	

	//function to update a qstn from database
	    public function update_qstn_from_db($qstn_id, $qstn_title, $qstn_data, $qstn_type, $qstn_help)
	    {
	    	$userid = $_SESSION['userid'];

	    	$data = array(
	    		'qstn_title'=>$qstn_title,
	    		'qstn_data'=>$qstn_data,
	    		'qstn_type'=>$qstn_type,
	    		'qstn_help'=>$qstn_help,
	    		'edited_by'=>$userid,
	    		'edited_on'=> date('Y-m-d')
	    		);
	    	
	    	$this->db->where('qstn_id', $qstn_id);
			$query_run = $this->db->update('mdt_questionnaire_qstns', $data);

           	if($query_run)
				return 1;
			else
				return 0;   
	    }
	
	//function to add sample question in db
	    public function add_sample_qstn_in_db($page_id, $qstn_title, $qstn_data, $qstn_type, $qstn_parent, $qstn_help)
	    {
	    	$userid = $_SESSION['userid'];

	    	$data = array(
	    		'page_id'=>$page_id,
	    		'qstn_title'=>$qstn_title,
	    		'qstn_data'=>$qstn_data,
	    		'qstn_type'=>$qstn_type,
	    		'qstn_help'=>$qstn_help,
	    		'qstn_parent'=>$qstn_parent,
	    		'added_by'=>$userid,
	    		'added_on'=> date('Y-m-d')
	    		);

	    	$query_run = $this->db->insert('mdt_questionnaire_qstns',$data);

           	if($query_run)
				return 1;
			else
				return 0; 
	    }
	
	
	}
?>