<?php
	class Ldt_app_rev_anzsic_codes_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_app_rev_anzsic_codes';
	        $this->load->model('pagination_model');
		}

	//getting the anzsic codes records of that tracksheet
		public function app_rev_anzsic_code_record($tracksheet_id)
		{
			$this->db->select('*');
			$this->db->from('ldt_app_rev_anzsic_codes');
			$this->db->where('tracksheet_id', $tracksheet_id);

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}

	//function to insert anzsic code records in the databse
		public function insert_app_rev_anzsic_codes_record($app_rev_form_id, $tracksheet_id, $scheme_system, $anzsic_codes)
	    {
	    	$data = array(
	    			'app_rev_form_id' => $app_rev_form_id,
	    			'tracksheet_id' => $tracksheet_id,
	    			'scheme_system' => $scheme_system
	    		);

	    	foreach ($anzsic_codes as $key => $anzsic_code) 
	        {
	        	$data['anzsic_code'] = $anzsic_code;

	        	$this->db->insert('ldt_app_rev_anzsic_codes', $data);
	        }        
	    }
	}
?>