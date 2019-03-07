<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_employee_bank_account_log_model extends CI_Model {
	function __construct() {
		$this->tableName = 'tdt_employee_bank_account_log';
		$this->primaryKey = 'acc_id';
	}
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
}