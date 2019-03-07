<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_joining_date_temp_model extends CI_Model {
	function __construct() {
        $this->tableName        =   'ldt_joining_date_temp';
        $this->primaryKey       =   'ljdt_id';
	}  
    public function get_count($cond)
    {
        $query	                =	$this->db->where($cond)->get($this->tableName);
		$count                  =	$query->result();
		return count($count);
    }
    public function get_pending_doj($emp_id)
	{
        $this->db->select('new_doj');
		$this->db->where('emp_id',$emp_id);
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->new_doj;
        }
        else
        {
            return 0;
        }        
	}   		
	function delete($cond)
    {
		$this->db->where($cond);
		$this->db->delete($this->tableName); 
    } 
	public function insert($insArr)
	{	
		$this->db->query("INSERT IGNORE INTO ".$this->tableName." (emp_id, added_by, old_doj, new_doj) VALUES (".$insArr.")");
        return true;
	} 
    public function doj_approval_list()
    {    
        $this->load->model('pagination_model');
        $this->load->model('mdt_employees_model');
        $selEmpId   				=	$this->input->input_stream('selEmpId', TRUE);        
        $cond                       =   'emp_id !=0';
        if($selEmpId)
        {
            $cond                   =   $cond.' AND emp_id ='.$selEmpId;
        }
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'added_on',25);		
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {
            foreach($result_array   as  $resval)
            {	   
                if($resval->emp_id)
                {
                    $emp_name       =   $this->mdt_employees_model->get_emp_name($resval->emp_id);
                }
                else
                {
                    $emp_name       =   'NA';
                }
                if($resval->added_by)
                {
                    $add_emp_name   =   $this->mdt_employees_model->get_emp_name($resval->added_by);
                }
                else
                {
                    $add_emp_name   =   'NA';
                }
                $temp               =   array("emp_id"=>$resval->emp_id,"ljdt_id"=>$resval->ljdt_id,"added_on" =>$resval->added_on,"added_by"=>$resval->added_by,"old_doj"=>$resval->old_doj,"new_doj"=>$resval->new_doj,"emp_name"=>$emp_name,"add_emp_name"=>$add_emp_name);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }	   
        $data['selEmpId']               =   $selEmpId;
        $data['title']                  =   'DOJ Approval Pending';
        $data['subtitle']               =   '';
        $data['view']                   =   'hrm/doj_approval';		
        return $data;
    }    
    public function approve_doj()
    {        
		$ljdt_id                		=	$this->input->input_stream('ljdt_id', TRUE);
		$emp_id                   		=	$this->input->input_stream('emp_id', TRUE);
		$st                   		    =	$this->input->input_stream('st', TRUE);
		$ndoj                   	    =	$this->input->input_stream('ndoj', TRUE);
        if($ljdt_id                     && $st==1) // Approve
        {
            if($ndoj)
            {                
                $this->load->model('mdt_employees_model');
                $this->mdt_employees_model->update(array('joined_date'=>$ndoj),'emp_id='.$emp_id);
            }
        } 
        else if($ljdt_id                && $st==2) // Reject
        {
            // 
        }     
        if($emp_id)
        {
            $this->delete('emp_id='.$emp_id);
        }
        
    }
}