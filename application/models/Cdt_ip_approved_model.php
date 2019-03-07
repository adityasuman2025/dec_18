<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cdt_ip_approved_model extends CI_Model {
	function __construct() 
    {
        $this->tableName    =   'cdt_ip_approved';
        $this->primaryKey   =   'ip_id';
	}
    public function check_ip_approved($user_ip)
	{
	   if($this->config->item('ip_check')      ==  1)
       {
        $resval                            =    '';
        $this->load->model('tdt_user_roles_model');
        $roleDetails					   =	$this->tdt_user_roles_model->get_employee_roles($this->session->userdata('userid'));
		$this->db->select('ip_address, ip_roles');
		$this->db->where('ip_status','1');
		$this->db->where('ip_address',$user_ip);
		$result                            = $this->db->get($this->tableName)->result();
        if($result                         != '')
        {
            foreach($result as $apvdIP)
            {
                if($user_ip	==	$apvdIP->ip_address)
                {
                    $ip_roles				=   $apvdIP->ip_roles;
                    $ip_role_arr			=   explode(',',$ip_roles);
                    $ip_role_arr			=	array_map('trim',$ip_role_arr);
                    $roleDetails			=	array_map('trim',$roleDetails);
                    if($ip_role_arr)
                    {
                        $cnt				=   0;
                        foreach($roleDetails as $key)
                        {
                            if(in_array($key, $ip_role_arr))
                            {
                                $cnt		=   $cnt+1;
                            }
                        }
                        if($cnt				==  count($roleDetails))
                        {
                            $resval			=	1;
                        }
                        else
                        {
                            $resval			=	0;
                        }
                    }
                }
            }
        }
        }
        else
        {
            $resval			=	1;
        }
		return $resval;
	}  
    public function insert_ip_approved($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function update_ip_approved($up_arr,$cond)
	{
        $this->db->update($this->tableName, $up_arr, $cond);
        return 1;
	}
    function get_ip_details($ip_id)
	{
        $this->db->select('ip_address, ip_where, ip_roles');
		$this->db->where('ip_id',$ip_id);
        $result                            = $this->db->get($this->tableName)->result();
		$data								=	array();
        if($result                        != '')
        {
            foreach($result                as $det)
            {
                $data['ip_address']         =   $det->ip_address;
                $data['ip_where']           =   $det->ip_where;
                $data['ip_roles']           =   $det->ip_roles;
            }
        }
        return  $data;       
	}
	public function approved_ip_list()
	{
		$key                          			=   $this->input->input_stream('key', TRUE); 
        $where 				         			=	'ip_id != 0';		
		if($key)
		{
		$where 				         		=	$where." and (ip_address='".$key."' or ip_where like '%".$key."%')";
		}
		$get_pageList  		             		=   $this->pagination_model->get_pagination("cdt_ip_approved",$where,"ip_status",25);   
		return $get_pageList;
	}
	public function update_ip_details()
    {
        $ip_id                      =   $this->input->input_stream('ip_id', TRUE); 
        $ip_address                 =   $this->input->input_stream('ip_address', TRUE); 
        $ip_where                   =   $this->input->input_stream('ip_where', TRUE); 
        $role_arr                   =   $this->input->input_stream('role_id', TRUE); 
        $ip_roles                   =   implode(',', $role_arr);
        if($ip_id)
        {
            $ip_upd					= 	$this->update_ip_approved(array('ip_address'=>$ip_address,'ip_where'=>$ip_where,'ip_updatedOn'=>date('Y-m-d H:i:s'),'ip_roles'=>$ip_roles),'ip_id='.$ip_id);
            $action                 =   2;
            $m                      =   'IP updated sucessfully';
        }
        else
        {
            $ip_ins					= 	$this->insert_ip_approved(array('ip_address'=>$ip_address,'ip_where'=>$ip_where,'ip_added_on'=>date('Y-m-d H:i:s'),'ip_roles'=>$ip_roles));
            $action                 =   1;
            $m                      =   'IP added sucessfully';
        }
       // $log_arr                    =   array("ip_address"=>$ip_address, "ip_where"=>$ip_where, "iph_added_on"=>date('Y-m-d H:i:s'), "iph_added_by"=>$this->session->userdata('employee'), "iph_action"=>$action);
        //$ins_log                    =   $this->ip_log_history_model->insert_ip_log_history($log_arr);
        return $m;
    }
	public function change_ip_details()
    {
       $ip_id                      =   $this->input->input_stream('pkey', TRUE);
       $ip_status                  =   $this->input->input_stream('status', TRUE);   
		if($ip_status 				==	1)
		{
			$status					=	2;
            $action                 =   3; 
		}
		else
		{
			$status					=	1;
            $action                 =   4; 
		}
        $update_status              =   $this->update_ip_approved(array("ip_status"=>$status,"ip_updatedOn"=>date('Y-m-d H:i:s')),"ip_id=".$ip_id);
        //$details                    =   $this->get_ip_details($ip_id);
        /*$log_arr                    =   array("ip_address"=>$details['ip_address'], "ip_where"=>$details['ip_where'], "iph_added_on"=>date('Y-m-d H:i:s'), "iph_added_by"=>$this->session->userdata('employee'), "iph_action"=>$action);
        $ins_log                    =   $this->ip_log_history_model->insert_ip_log_history($log_arr); 
        $this->session->set_flashdata('success_message', 'Status Changed Successfully!!!');*/
        //redirect(base_url('settings/approved_ip_list'));
    }
}