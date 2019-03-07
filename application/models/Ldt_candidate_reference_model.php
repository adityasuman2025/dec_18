<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_candidate_reference_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_candidate_reference';
		$this->primaryKey = 'cr_id';
	}
    public function insert_from_add_candidate($cr_ref_id)
	{
        $empref_name                    =   $this->input->post('empref_name');
        $empref_email                   =   $this->input->post('empref_email');
        $empref_number                  =   $this->input->post('empref_number');
        if($empref_number)
        {
        foreach($empref_number          as  $key=>$val)
        {
            if($val)
            {
                $insArr                         =   array();
                $insArr['cr_name']              =   ucwords($empref_name[$key]);
                $insArr['cr_email']             =   $empref_email[$key];
                $insArr['cr_contact']           =   $empref_number[$key];
                $insArr['cr_reference_type']    =   2;
                $insArr['cr_ref_id']            =   $cr_ref_id;
                if($empref_name[$key]   && $empref_number[$key])
                {
                $this->db->insert($this->tableName, $insArr);
                }
            }
       }
        return $this->db->insert_id();
        }
	}
}
?>