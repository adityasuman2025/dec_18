<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_model extends CI_Model 
{

	function __construct() {
	$this->db1 	 = $this->load->database('ic_indiacover_db', TRUE); 
	//$this->tableName = 'mdt_employees';
	}
	function check_empid()
	{

	   $this->db1->where("emp_id!=0");
		$result 		= $this->db1->get('indiacov_ic_indiacover_db.mdt_employees')->row();
        print_r($result);
		if($result)
		{
			return $result;
		}
		else{
			return "Employee Not Found";
		}
	}
}