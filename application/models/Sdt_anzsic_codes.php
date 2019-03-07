<?php
	class sdt_anzsic_codes extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
		}

	//get anzsic code suggestion
	    public function get_anzsic_sugg($scheme_system ,$anzsic_sugg_input)
	    {
	    	if($scheme_system == "fsms")
	    	{
	    		$query = "SELECT * FROM sdt_" . $scheme_system . "_anzsic_code WHERE sub_category LIKE '%" . $anzsic_sugg_input . "%' OR example LIKE '%" . $anzsic_sugg_input . "%'";   	 
	    	}
	    	else if($scheme_system == "enms")
	    	{
	    		$query = "SELECT * FROM sdt_" . $scheme_system . "_anzsic_code WHERE example LIKE '%" . $anzsic_sugg_input . "%' OR typical_energy_use LIKE '%" . $anzsic_sugg_input . "%'"; 
	    	}
	    	else
	    	{
	    		$query = "SELECT * FROM sdt_" . $scheme_system . "_anzsic_code WHERE code_description LIKE '%" . $anzsic_sugg_input . "%' OR group_name LIKE '%" . $anzsic_sugg_input . "%' OR code_expl LIKE '%" . $anzsic_sugg_input . "%'";   	        
	    	}
	       
	        $query = $this->db->query($query);
	        $result = $query->result_array();  
	        return $result;
   		}
	}

?>