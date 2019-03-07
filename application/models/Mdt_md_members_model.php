<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_md_members_model extends CI_Model
{
	function __construct()
	{
		$this->tableName = 'md_members';
	}
	public function check_member_exist($mem_mobile)
    {
		$this->db->where("mem_mobile =",$mem_mobile);
		$result = $this->db->get($this->tableName)->row();
        return $result;
    }
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
		//echo $this->db->last_query();exit;
        return $this->db->insert_id();
	}
	public function full_query($sql)
	{  
		return $insert_update 	=	$this->db->query($sql);
	}
	public function get_md_member_name($mem_id)
	{
		$retval		=	"";
		$this->db->select('mem_fname');
		$this->db->where('mem_id',$mem_id);
		$result = $this->db->get($this->tableName)->row();
		if($result !=    null)
		{
			$retval =   $result->mem_fname;
		}
		return $retval;
	}
	public function md_member_name_by_mobile($mem_mobile)
	{
		$retval		=	"";
		$this->db->select('mem_fname');
		$this->db->where('mem_mobile',$mem_mobile);
		$result = $this->db->get($this->tableName)->row();
		if($result !=    null)
		{
			$retval =   $result->mem_fname;
		}
		return $retval;
	}
	public function md_member_name_by_email($mem_email)
	{
		$retval		=	"";
		$this->db->select('mem_fname');
		$this->db->where('mem_email',$mem_email);
		$result = $this->db->get($this->tableName)->row();
		if($result !=    null)
		{
			$retval =   $result->mem_fname;
		}
		return $retval;
	}
/* 	public function totalDownload($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                          =	$query->result();
		return count($count);
	} */
	public function totalDownload($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		return $query->num_rows();
	}
	public function wda_users($stdate,$endate,$mem_state="",$mem_city="")	
	{
		$this->load->model('old_pagination_model');
		$today                           =   date('Y-m-d');
		$stdate                          =   $this->input->input_stream('stdate', TRUE)?$this->input->input_stream('stdate', TRUE):$today; 
		$endate                          =   $this->input->input_stream('endate', TRUE)?$this->input->input_stream('endate', TRUE):$today; 
		$mem_state                       =   $this->input->input_stream('mem_state', TRUE); 
		$mem_city                        =   $this->input->input_stream('mem_city', TRUE); 
		$wda_status                      =   $this->input->input_stream('wda_status', TRUE); 
		$cond							 =	" wda_status = 1 ";
		if($mem_state)
		{
			$cond						.=	" and mem_state =" .$mem_state;
		}
		if($mem_city)
		{
			$cond						.=	" and mem_city =" .$mem_city;
		}
		if($wda_status == 1)
		{
			$cond 						 =	 $cond." AND date(wda_last_seen) between  '".$stdate."' and  '".$endate."'";
		}
		if($wda_status == 2)
		{
			$cond 						 =	 $cond." AND date(wda_addedon) between  '".$stdate."' and  '".$endate."'";
		}
		$query 					         =	"Select mem_id,mem_fname,mem_city,mem_state,device_model,device_id,device_brand,mem_lname,mem_mobile,mem_email,wda_first_login ,wda_last_seen as wda_last_login From $this->tableName where  ".$cond."";
		if($wda_status == 3)
		{
		$query 					         =	"Select mem_id,mem_fname,mem_city,mem_state,device_model,device_id,device_brand,mem_lname,mem_mobile,mem_email,wda_first_login ,wda_last_seen as wda_last_login From $this->tableName where  ".$cond."";
		}
		//echo $query;
		$wda_list  						=   $this->old_pagination_model->get_pagination_sql($query,25);	
		//echo $this->db->last_query();
        return $wda_list;
	}
	public function lead_details($mem_mobile="",$mem_email="")
	{
		$this->load->model('old_pagination_model');
		//$this->load->model('Sdt_products_model');
		//$this->load->model('Old_leadTransEngine_model');
		$today                           =   date('Y-m-d');
		$stdate                          =   $this->input->input_stream('stdate', TRUE)?$this->input->input_stream('stdate', TRUE):$today; 
		$endate                          =   $this->input->input_stream('endate', TRUE)?$this->input->input_stream('endate', TRUE):$today; 
		$mem_state                       =   $this->input->input_stream('mem_mobile', TRUE); 
		$mem_city                        =   $this->input->input_stream('mem_email', TRUE); 
		//$lteStatus                       =   $this->input->input_stream('lteStatus', TRUE);
		//$wda_status                      =   $this->input->input_stream('wda_status', TRUE); 
		$where                           =   " where l.L_Id = m.mem_id AND l.R_Id = r.R_Id ";
		//$where1                          = "WHERE a.mem_id = m.mem_id";
		if($mem_mobile)
		{
			$cond						.=	" and mem_mobile =" .$mem_mobile;
		}
		if($mem_email)
		{
			$cond						.=	" and mem_email =" .$mem_email;
		}
		if($wda_status == 1)
		{
			$cond 						 =	 $cond." AND date(wda_last_seen) between  '".$stdate."' and  '".$endate."'";
		}
		if($wda_status == 2)
		{
			$cond 						 =	 $cond." AND date(wda_addedon) between  '".$stdate."' and  '".$endate."'";
		}
		$query 					         =	"Select mem_id,mem_fname,mem_lname,mem_mobile,mem_email,wda_first_login ,wda_last_seen as wda_last_login From $this->tableName where  ".$cond."";
		if($wda_status == 3)
		{
		$query 					         =	"Select mem_id,mem_fname,mem_lname,mem_mobile,mem_email,wda_first_login ,wda_last_seen as wda_last_login From $this->tableName where  ".$cond."";
		}
	}
	public function get_md_members_fields($fields,$cond)
	{
		$this->db->select($fields);
		$this->db->where($cond);
		$result = $this->db->get($this->tableName)->row();
		return $result;
	}
}
?>