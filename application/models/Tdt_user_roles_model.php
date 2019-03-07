<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_user_roles_model extends CI_Model {
	function __construct() {
        $this->tableName    =   'tdt_user_roles';
        $this->primaryKey   =   'role_id';
	}
    public function get_emp_primary_role($user_id)
	{
        $retval             =   '';
        $this->db->select('ur_id');
        $this->db->where('user_id',$user_id);
        $this->db->where('role_type','1');
        $this->db->where('role_status','1');
        $resval             =   $this->db->get($this->tableName)->result();        
        if($resval         !=   null)
        {
            foreach($resval as $res)
            {
                $retval     =   $res->ur_id;
            }
        }        
        return $retval;
	}  
	public function checkEmpRole($user_id,$cond)
	{
		$query 				=	$this->db->select($user_id)
												->from($this->tableName)
												->where($cond)
												->get();
		return  $query->result_array();
	}
    public function get_employee_roles($user_id) 
	{
		$retval				=	array();
		$today				=	date('Y-m-d');
        $this->db->select('role_type,ur_id,role_from,role_to');
        $this->db->where('user_id',$user_id);
        $this->db->where('role_status','1');
        $resval             =   $this->db->get($this->tableName)->result(); 
        if($resval         !=   null)
        {
            foreach($resval as $key)
            {
                if($key->role_type==1)
                {
                    array_push($retval,$key->ur_id);
                }
                else
                {
                    $from_dt=	strtotime($key->role_from);
                    $to_dt	=	strtotime($key->role_to);
                    $now 	= 	strtotime($today);
                    if($from_dt <= $now && $to_dt >= $now)
                    {
                        array_push($retval,$key->ur_id);
                    }
                } 
            }
        }       
        return $retval;
	}
    public function role_informations() // not changed
	{
		$ur_id    					    =	$this->input->input_stream('id', TRUE);
		$user_type					    =	$this->input->input_stream('user_type', TRUE);
        if($user_type                   ==  1)
        {
            $sql                        =   "SELECT t4.*,employee_name as nm FROM mdt_employees t3 JOIN(SELECT t1.user_id, role_type, ur_id, user_type FROM tdt_user_roles t1 JOIN mdt_users t2 ON t1.user_id=t2.user_id AND ur_id = ".$ur_id." AND role_status = 1 GROUP BY t1.user_id, role_type, ur_id) t4 ON t4.user_id
= t3.emp_id AND t4.user_type = 1 AND employee_status = 1";
        }
        else
        {
            $sql                        =   "SELECT t4.*,fellow_name as nm FROM mdt_partners t3 JOIN(SELECT t1.user_id, role_type, ur_id, user_type FROM tdt_user_roles t1 JOIN mdt_users t2 ON t1.user_id=t2.user_id AND ur_id =".$ur_id." AND role_status = 1 GROUP BY t1.user_id, role_type, ur_id) t4 ON t4.user_id
= t3.fellow_id AND t4.user_type = 2 AND fellow_status = 1";
        }
		$get_pageList  			    =   $this->pagination_model->get_pagination_sql($sql,10); 
        return $get_pageList;
	}
    public function get_emp_role_count($ur_id)
	{
        $sql                                    =   "SELECT employee_name as nm FROM mdt_employees t3 JOIN(SELECT t1.user_id, role_type, ur_id, user_type FROM tdt_user_roles t1 JOIN mdt_users t2 ON t1.user_id=t2.user_id AND ur_id = ".$ur_id." AND role_status = 1 GROUP BY t1.user_id, role_type, ur_id) t4 ON t4.user_id
= t3.emp_id AND t4.user_type = 1 AND employee_status = 1";
        $query                                  =   $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            $count                              =   $query->num_rows();
            $names                              =   '';
            $data                               =   $query->result_array();
            $i                                  =   0;
            foreach($data                       as  $val)
            {
                $i++;
                $names                          =   $names.' <br> '.$val['nm'];
                if($i                           >   20)  
                {    
                    $names                      =   $names.'...';
                    break;
                }
            }
            $names                              =   ltrim($names, " <br>");
            return array('0'=>$names,'1'=>$count);
        }
	} 
    public function get_vendor_role_count($ur_id)
	{
        $sql                                    =   "SELECT fellow_name as nm FROM mdt_partners t3 JOIN(SELECT t1.user_id, role_type, ur_id, user_type FROM tdt_user_roles t1 JOIN mdt_users t2 ON t1.user_id=t2.user_id AND ur_id =".$ur_id." AND role_status = 1 GROUP BY t1.user_id, role_type, ur_id) t4 ON t4.user_id
= t3.fellow_id AND t4.user_type = 2 AND fellow_status = 1";
        $query                                  =   $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            $count                              =   $query->num_rows();
            $names                              =   '';
            $data                               =   $query->result_array();
            $i                                  =   0;
            foreach($data                       as  $val)
            {
                $i++;
                $names                          =   $names.' <br> '.$val['nm'];
                if($i                           >   20)  
                {    
                    $names                      =   $names.'...';
                    break;
                }
            }
            $names                              =   ltrim($names, " <br>");
            return array('0'=>$names,'1'=>$count);
        }
	} 
	public function insert_user_role($user_id)
	{
		$insert_array				=	array();
		$insert_array['user_id']	=	$user_id;
		$insert_array['ur_id']		=	$this->input->input_stream('roles', TRUE);
		$insert_array['role_type']	=	'1';
		$this->db->insert($this->tableName, $insert_array);
        return $this->db->insert_id();
	}
    public function get_users_all_roles($user_id) 
	{
        $this->db->where('user_id',$user_id);
        $resval             =   $this->db->get($this->tableName)->result(); 
        return $resval;
	}
	public function add_user_role()
	{
		$insert_array				=	array();
		$insert_array['user_id']	=	$this->input->input_stream('user_id', TRUE);
		$insert_array['ur_id']		=	$this->input->input_stream('user_roles', TRUE);
		$role_type					=	$this->input->input_stream('user_role_type', TRUE);
		$insert_array['role_type']	=	$role_type;
        $fi                         =   '';
        $va                         =   '';
		if($role_type	            ==	2)
		{
			$insert_array['role_from']	=	date('Y-m-d',strtotime($this->input->input_stream('role_from',TRUE)));
			$insert_array['role_to']	=	date('Y-m-d',strtotime($this->input->input_stream('role_to',TRUE)));
            $fi                         =   ',emp_role_from,emp_role_to';
            $va                         =   ',"'.$insert_array['role_from'].'","'.$insert_array['role_to'].'"';
		}
		$ins                       =   $this->db->insert($this->tableName, $insert_array);
        $ins_id                    =   $this->db->insert_id();
        
        return $ins_id;
	}
	public function update_role_status()
	{
		$role_id 			= 	$this->input->post("role_id");
        $status				= 	$this->input->post("role_status");
		if($status	        ==	1)
		{
			$status	        =	2;
		}
		elseif($status	    ==	2)
		{
			$status	        =	1;
		}
        $titledata 			= 	array('role_status' => $status);
        $this->db->where('role_id', $role_id);
        $this->db->update($this->tableName, $titledata);
        
	}	
    public function get_users_in_role($roles_in)
	{
        $this->db->select('user_id');
        $this->db->where('role_status','1');
        $this->db->where_in('ur_id',$roles_in,FALSE);
        $this->db->where('(role_to >= "'.date('Y-m-d').'" OR role_to = "0000-00-00" OR role_to IS NULL )');
        $resval             =   $this->db->get($this->tableName)->result_array(); 
        $res                =   array();
        foreach($resval     as $val)
        {
            array_push($res,$val['user_id']);
        }
        if(count($res))
        {
            $res            =   implode(',',$res);
        }
        else
        {
            $res            =   '';
        }
        return $res;
    }
    /*public function get_roles_for_old_hrm($emp_id)
	{
		$this->db->where('role_status','1');
		$this->db->where('user_id',$emp_id);
		$resval             =   $this->db->get($this->tableName)->result();
        if($resval         !=   null)
        {
           return $resval;
        }   
		else
		{
			return '';
		}	
	}    */
}