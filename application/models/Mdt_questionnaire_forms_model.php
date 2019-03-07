<?php
	class Mdt_questionnaire_forms_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'mdt_questionnaire_forms';
	        $this->load->model('pagination_model');
		}

	//function to add a new questionnaire into the database
		public function add_new_questionnaire_db($scheme_id, $level, $page_title, $page_intro)
		{
			$userid = $_SESSION['userid'];

			$data = array(
		        'scheme_id'=>$scheme_id,
		        'level'=>$level,
		        'page_title'=>$page_title,
		        'page_intro'=>$page_intro,
		        'created_by'=>$userid,
		        'created_on'=> date('Y-m-d'),
		        'status'=>1
		    );

		    $query_run = $this->db->insert('mdt_questionnaire_forms',$data);
			$insert_id = $this->db->insert_id();

			if($query_run)
				echo $insert_id;
			else
				echo 0;			
		}
	
	//function to render the list questionnaire page
		public function list_questionnaire()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_questionnaire_forms.*, sdt_schemes.scheme_name FROM mdt_questionnaire_forms, sdt_schemes WHERE sdt_schemes.scheme_id = mdt_questionnaire_forms.scheme_id";
			}
			else
			{
				$query                     =   "SELECT mdt_questionnaire_forms.*, sdt_schemes.scheme_name FROM mdt_questionnaire_forms, sdt_schemes";

				$query						=	$query . " WHERE (mdt_questionnaire_forms.level  = '". $searchText . "' OR mdt_questionnaire_forms.page_intro LIKE '%" . $searchText . "%' OR mdt_questionnaire_forms.page_title LIKE '%" . $searchText . "%' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND sdt_schemes.scheme_id = mdt_questionnaire_forms.scheme_id";
			}

	        $query						=	$query." ORDER BY page_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to get records of a questionnaire form on the basis of page_id
		public function get_questionnaire_form_records($page_id)
		{
			$this->db->select('*');
			$this->db->from('mdt_questionnaire_forms');
			$this->db->where('page_id', $page_id);
			
			$query_run = $this->db->get();			
			$result = $query_run->result_array();

			return $result;
		}

	//function to update the page details in db
	    public function update_page_details_db($page_id, $page_title, $page_intro)
	    {
	    	$userid = $_SESSION['userid'];

	    	$data = array(
	    		'page_title'=> $page_title,
	    		'page_intro'=> $page_intro,
	    		'edited_by'=> $userid,
	    		'edited_on'=> date('Y-m-d')
	    		);

	    	$this->db->where('page_id', $page_id);
			$query_run = $this->db->update('mdt_questionnaire_forms', $data);

			if($query_run)
				echo 1;
			else
				echo 0;			
	    }	

	    public function get_questionnaire_form($scheme_id, $level)
	    {
	    	$this->db->select('page_id');
			$this->db->from('mdt_questionnaire_forms');
			$this->db->where('scheme_id', $scheme_id);
			$this->db->where('level', $level);
			$this->db->order_by("page_id", "dsc");
			
			$query_run = $this->db->get();			
			$result = $query_run->result_array();

			return $result;
	    }
	}
?>