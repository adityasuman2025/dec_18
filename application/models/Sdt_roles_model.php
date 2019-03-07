<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_roles_model extends CI_Model {
	function __construct() {
        $this->tableName                        =   'sdt_roles';
        $this->primaryKey                       =   'ur_id';
	}
    public function get_role_in_time($ur_id)
    {
        $retval                                 =   '';  
        $this->db->select('ur_punch_time');
        $this->db->where('ur_id',$ur_id);
        $resval                                 =   $this->db->get($this->tableName)->result();        
        if($resval                             !=   null)
        {
            foreach($resval as  $res)
            {
                $retval                         =   $res->ur_punch_time;
            }
        }
        return $retval;
    }
	public  function getRoleName($ur_id)
	{
		$this->db->select('ur_name');
		$this->db->where('ur_id='.$ur_id);
		$result =   $this->db->get($this->tableName)->row();        
		return $result->ur_name;
	}
	public function get_to_be_present_days($ur_id)
	{
		$this->db->select('ur_working_days');
		$this->db->where('ur_id',$ur_id);
		$result =   $this->db->get($this->tableName)->row();        
		return ($result->ur_working_days)?($result->ur_working_days):'1,2,3,4,5,6';
	}    
	public function update($up_arr,$cond)
	{
		$this->db->update($this->tableName, $up_arr, $cond);
        return 1;
	} 
	public function insert($arr)
	{
		$this->db->insert($this->tableName, $arr);
		
        return $this->db->insert_id();
	} 
	public function get_role_row($ur_id)
	{
		$this->db->where('ur_id',$ur_id);
		$result                                 =   $this->db->get($this->tableName)->row(); 
		return $result;
	}
	public function role_list()
	{
        $key                                    =   $this->input->input_stream('key', TRUE); 
        if($key                                 ==  null    && $this->session->flashdata())
        {
            $key                                =   $this->session->flashdata('key');
        }
        $where 				         			=	'ur_id != 0';
        if($key                                !=  '')
		{
			$where				           		=	$where." AND ur_name LIKE '%".$key."%'";
		}		
		$get_pageList  		             		=   $this->pagination_model->get_pagination("sdt_roles",$where,"ur_status, ur_id",25);   
		return $get_pageList;
	}
	public function add_role()
	{
		$insArray								=	array();
		$upArray								=	array();	
        if($this->uri->segment(3))
		{
			$ur_id				                =	$this->uri->segment(3);
		}
        else
        {
            $ur_id                              =   '';
        } 
		$key      								=	$this->input->post('key',TRUE);
        $this->load->model('cdt_permission_role_page_model');
		if($ur_id)
		{            
			$upArray['ur_name']				    =	$this->input->post('ur_name');
			$update  							=	$this->update($upArray,"ur_id=".$ur_id);	
            ## Permission  
            $page_act_arr						=	$this->input->post('permission');
            $delete_page_act                    =   $this->cdt_permission_role_page_model->delete("role_id=".$ur_id);
            // delete and insert section
            if(is_array($page_act_arr))
            {
            foreach($page_act_arr as $page_act)
            { 
                $page_act_val					= 	explode("-",$page_act);
                $radetails						=	$this->cdt_permission_role_page_model->insert(array('page_id'=>$page_act_val[0],'role_id'=>$ur_id)); 
            }
            }
			$this->session->set_flashdata('success_message', 'Role Updated Successfully');
                       
		}
		else
		{            
			$insArray['ur_name']				=	$this->input->post('ur_name');
			$new_ur_id  						=	$this->insert($insArray);
	        ## Permission              
            $page_act_arr						=	$this->input->post('permission');
            if(is_array($page_act_arr))
            {
            foreach($page_act_arr as $page_act)
            { 
                $page_act_val					= 	explode("-",$page_act);
                $role_details->page_id			=	$page_act_val[0];	 
                $radetails						=	$this->cdt_permission_role_page_model->insert(array('page_id'=>$page_act_val[0],'role_id'=>$new_ur_id));
            }
            }
			$this->session->set_flashdata('success_message', 'Role Added Successfully');            
                       
		}
        $this->session->set_flashdata('key',$key);        
		redirect(base_url('settings/role_list'));
	}
	public function change_role_status()
	{
	 	$ur_id			    		            =	$this->input->post('pkey', TRUE);
	 	$status                  			    =	$this->input->input_stream('status', TRUE);
        if($status                              ==  1)
        {
            $ur_status                          =   2;
        }
        else
        {
            $ur_status                          =   1;
        }        
        $this->update(array("ur_status"=>$ur_status),"ur_id=".$ur_id);
        $this->session->set_flashdata('success_message', 'Status changed successfully');           
        
	}
    function calculate_deduction_factor($in,$out,$ur_id='')
	{   
        if($ur_id)
        {
            $ur_punch_time		        	    =	$this->get_role_in_time($ur_id);
        }
        $ur_punch_time                          =   ($ur_punch_time)?($ur_punch_time):'09:06:00';
		$dedf		                            =	0;
        ////////////////////////////////////// in time deduction ////////////////////////////////////
        if($ur_punch_time                       ==  '10:06:00'  || $ur_punch_time         ==  '09:36:00')
        {
            $eleven_cond                        =   '11:30:00';
        }
        else
        {
            $eleven_cond                        =   '11:00:00';
        }        
        /////////////////////////////////////////////////////////////////////////////////////////////////
        if((strtotime($in) >= strtotime($ur_punch_time)) && (strtotime($in) < strtotime($eleven_cond))) // punched in between 9:06 and 11:30 (-0.25)
        {
            $dedf 	                            =	'0.25';	 // deduct quarter
        }
        else if((strtotime($in) >= strtotime($eleven_cond)) && (strtotime($in) <= strtotime('14:00:00'))) // punched in between  11:31 and 2:00 (-0.5)
        {
            $dedf                              	=	'0.5';	// deduct half
        }	
        else if(strtotime($in) > strtotime('14:00:00')) // punched in after 2 pm (-0.5)
        {
            $dedf                              	=	'0.5'; 	// deduct half
        }
        else if((strtotime($in) == strtotime('00:00:00')) || $in == '')
        {
            $dedf                              	=	'0.5';	// deduct half
        }
        /////////////////////////////////// EARLY PUNCH OUT /////////////////////////////         
        if($ur_punch_time                       ==  '10:06:00')                     // LATE IN TIME
        {
            if(strtotime($in)                   >=  strtotime('10:06:00'))
            {                
                if((strtotime($out) >= strtotime('13:00:00')) && (strtotime($out) <= strtotime('16:00:00'))) // punch out before 4.00 (-0.5)
                {
                    $dedf                       =	$dedf + '0.5';	// deduct half
                }
                else if((strtotime($out) >= strtotime('16:01:00')) && (strtotime($out) < strtotime('18:00:00')))  // punch out between 4.00 and  6.00 (-0.25)
                {
                    $dedf                       =	$dedf + '0.25';	  // deduct quarter
                }
                else if((strtotime($out) == strtotime('00:00:00')) || (strtotime($out) < strtotime('13:00:00')) || $out == '')  // not punched out or punched out before 1 pm (-0.5)
                {
                    $dedf                       =	$dedf + '0.5';  // deduct half
                }
            }
            else
            {
                $secs                           =   strtotime("09:00:00")-strtotime("00:00:00");
                $act_out_time                   =   date("H:i:s",strtotime($in)+$secs);
                if((strtotime($out) >= strtotime('13:00:00')) && (strtotime($out) <= strtotime('16:00:00'))) // punch out before 4.00 (-0.5)
                {
                    $dedf	                    =	$dedf + '0.5';	// deduct half
                }
                else if((strtotime($out) >= strtotime('16:00:01')) && (strtotime($out) < strtotime($act_out_time)))  // punch out between 4.00 and  6.00 (-0.25)
                {
                    $dedf	                    =	$dedf + '0.25';	  // deduct quarter
                }
                else if((strtotime($out) == strtotime('00:00:00')) || (strtotime($out) < strtotime('13:00:00')) || $out == '')  // not punched out or punched out before 1 pm (-0.5)
                {
                    $dedf	                    =	$dedf + '0.5';  // deduct half
                }
            }            
        }
        else if($ur_punch_time                  ==  '09:36:00')
        {
                          
                if((strtotime($out) >= strtotime('13:00:00')) && (strtotime($out) <= strtotime('16:00:00'))) // punch out before 4.00 (-0.5)
                {
                    $dedf	                    =	$dedf + '0.5';	// deduct half
                }
                else if((strtotime($out) >= strtotime('16:01:00')) && (strtotime($out) < strtotime('18:30:00')))  // punch out between 4.00 and  6.00 (-0.25)
                {
                    $dedf	                    =	$dedf + '0.25';	  // deduct quarter
                }
                else if((strtotime($out) == strtotime('00:00:00')) || (strtotime($out) < strtotime('13:00:00')) || $out == '')  // not punched out or punched out before 1 pm (-0.5)
                {
                    $dedf	                    =	$dedf + '0.5';  // deduct half
                }           
        }
        else 
        {
            if((strtotime($out) >= strtotime('13:00:00')) && (strtotime($out) <= strtotime('16:00:00'))) // punch out before 4.00 (-0.5)
            {
                $dedf	                        =	$dedf + '0.5';	// deduct half
            }
            else if((strtotime($out) >= strtotime('16:01:00')) && (strtotime($out) < strtotime('18:00:00')))  // punch out between 4.00 and  6.00 (-0.25)
            {
                $dedf	                        =	$dedf + '0.25';	  // deduct quarter
            }
            else if((strtotime($out) == strtotime('00:00:00')) || (strtotime($out) < strtotime('13:00:00')) || $out == '')  // not punched out or punched out before 1 pm (-0.5)
            {
                $dedf	=	$dedf + '0.5';  // deduct half
            }
        }        
		return $dedf;
	}       
	public function deduction_timings()
	{        
	 	$ur_id					                =	$this->input->post('ur_id', TRUE);
	 	$ur_punch_time  		                =	$this->input->post('ur_punch_time', TRUE);     
		$this->update(array("ur_punch_time"=>$ur_punch_time),' ur_id ='.$ur_id);
        return true;
	}     
	public function to_be_present_days()
	{        
	 	$ur_id				       	            =	$this->input->post('ur_id', TRUE);
	 	$valu                 		            =	$this->input->post('valu', TRUE);            
        $value                                  =   ltrim($valu,',');        
		$this->update(array("ur_working_days"=>$value),' ur_id ='.$ur_id);
        return true;
	} 
	public function get_all_roles()
	{
        $data               = '';
		$this->db->select('ur_id,ur_name');
		$this->db->where('ur_status','1');
		$result             =   $this->db->get($this->tableName)->result();
        if($result         !=   null)
        {
            foreach ($result as $row) 
            {
                $data[]     =   $row;
            }
            return $data;
        }
		
		return $data;
	}
	public function get_role_details()
	{
        
        $cond                   =   "ur_status=1 AND ur_name != ''";
		$this->db->select('ur_id,ur_name');		
		$this->db->where($cond);
		$result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
	public  function getRole()
	{
		$this->db->select('ur_id');
		$this->db->where('ur_status=1');
		$result =   $this->db->get($this->tableName)->row();        
		return $result->ur_id;
	}
}