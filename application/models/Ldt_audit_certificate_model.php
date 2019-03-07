<?php
	class Ldt_audit_certificate_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
            $this->tableName        =   'ldt_audit_certificate';
		}

	//get anzsic code suggestion
	    public function get_auditor_sugg($scheme_system ,$anzsic_codes)
	    {
            $anzsic_code_s =  implode(",", $anzsic_codes);

			$query = "SELECT ldt_audit_certificate.*, mdt_users.username  FROM ldt_audit_certificate, mdt_users  WHERE scheme_system = '" . $scheme_system . "' AND anzsic_code IN (" . $anzsic_code_s . ") AND mdt_users.user_id = ldt_audit_certificate.user_id ORDER BY anzsic_code";

	        $query = $this->db->query($query);
	        $result = $query->result_array();  
	        return $result;
   		}
        public function get_certificate_by_user($user_id)
    	{
    		$this->db->order_by("anzsic_code","asc");
            $this->db->where('user_id',$user_id);
    		$result = $this->db->get($this->tableName);
    		return $result->result();
    	}
        public function insert_data()
    	{
    	   $insArray['anzsic_code']		=	$this->input->post('anzsic_sugg_input');
    	   $insArray['valid_from']		=	date('Y-m-d', strtotime($this->input->post('valid_from')));
    	   $insArray['scheme_system']	=	$this->input->post('scheme_system');
    	   $insArray['user_id']		    =	$this->input->post('user_id');
    	   $this->db->insert($this->tableName, $insArray);
                        
    		$this->session->set_flashdata('success_message', 'Skill Added Successfully');
            return $this->db->insert_id();	
    	}

    //to get all technical expert sorted according to code
        public function get_tech_exp($anzsic_codes)
        {
            $query = "SELECT ldt_audit_certificate.*, mdt_users.username  FROM ldt_audit_certificate, mdt_users  WHERE anzsic_code IN (" . implode(", ", $anzsic_codes) . ") AND mdt_users.user_id = ldt_audit_certificate.user_id AND ldt_audit_certificate.type = 2 ORDER BY anzsic_code";

            $query = $this->db->query($query);
            $result = $query->result_array();  
            return $result;
        }

    //to get all auditors sorted according to scheme system
        public function get_auditors($scheme_system)
        {
            $query = "SELECT ldt_audit_certificate.*, mdt_users.username  FROM ldt_audit_certificate, mdt_users  WHERE scheme_system = '" . $scheme_system . "' AND mdt_users.user_id = ldt_audit_certificate.user_id AND ldt_audit_certificate.type = 1 ORDER BY anzsic_code";

            $query = $this->db->query($query);
            $result = $query->result_array();  
            return $result;
        }
	
    }

?>