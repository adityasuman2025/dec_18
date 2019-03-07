<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_pay_group_model extends CI_Model {
	function __construct() {
		$this->tableName = 'sdt_pay_group';
	}		public function get_paygroup_dropdown()	{			$where			=	'pg_id!=0';		$this->db->select('pg_id as id,pg_code as name');		$this->db->where($where);		$this->db->order_by('pg_code', 'ASC'); 		$result             =   $this->db->get($this->tableName)->result_array();		return $result;	}		public function get_pg_code($pg_id)	{			$this->db->select('pg_code');				$this->db->where('pg_id',$pg_id);				$result = $this->db->get($this->tableName)->row();		        if($result)        {            return $result->pg_code;        }		else        {            return '';        }	}		public function get_pg_det($pg_id)	{			$this->db->select('*');				$this->db->where('pg_id',$pg_id);				$result = $this->db->get($this->tableName)->row();		        if($result)        {            return $result;        }		else        {            return '';        }	}
}
?>