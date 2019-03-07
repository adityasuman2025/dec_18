<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_hiring_request_model extends CI_Model {
	function __construct() 
    {
		$this->tableName = 'tdt_hiring_request';
        $this->load->model('pagination_model');
	}
	public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
		return count($count);
	}	
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function update($arr,$cond)
	{
		$this->db->update($this->tableName,$arr,$cond);
        return true;
	}
	public function hiring_request()
	{  
        $hhr_status   		        =	$this->input->post('hhr_status', TRUE);
		$where 					    =	'hhr_id != 0';
        if($hhr_status              ==   null && $this->session->flashdata())
        {
            $hhr_status             =   $this->session->flashdata('hhr_status');
        }        
        if($hhr_status)
        {
            $where                  =   $where.' AND hhr_status='.$hhr_status;
        }
        $data  			            =   $this->pagination_model->get_pagination($this->tableName,$where,"hhr_added_on DESC",10);                  
        $result_array				=	$data['results'];
        $data['results']            =   array();
        if($result_array           !=  null)
        {
            $this->load->model('mdt_users_model');
            $this->load->model('ldt_career_opening_model');
            $this->load->model('ldt_recruitment_process_model');
            foreach($result_array   as  $resval)
            {	   
                $added_by_name      =   '';
                $pos_name           =   '';
                $pos_status         =   '';
                $status_cnt         =   '';
                if($resval->hhr_added_by)
                {                   
                    $added_by_name  =   $this->mdt_users_model->get_employee_name($resval->hhr_added_by);
                }
                if($resval->opID)
                {
                    $details        =   $this->ldt_career_opening_model->get_career_opening_required_details($resval->opID,'opStatus,opPosition');
                    if($details)
                    {
                        $pos_name   =   $details->opPosition;     
                        $pos_status =   $details->opStatus;     
                    }                    
                    $status_cnt     =   $this->ldt_recruitment_process_model->get_interview_status_count($resval->opID);     
                }                
                $temp               =   array("added_by_name"=>$added_by_name,"position_name"=>$pos_name,"position_status"=>$pos_status,"opID" =>$resval->opID,"hhr_added_on"=>$resval->hhr_added_on,"hhr_num_positions"=>$resval->hhr_num_positions ,"hhr_filled_positions"=>$resval->hhr_filled_positions,"hhr_status"=>$resval->hhr_status,"hhr_comment"=>$resval->hhr_comment,"hhr_id"=>$resval->hhr_id,"status_cnt"=>$status_cnt);
                $data['results']    = (array) $data['results'];
                array_push($data['results'], $temp);
            }		  
        }   
        $data['hhr_status']         =   $hhr_status;
        return $data;        
	}
	public function hiring_request_added_by_list()
	{  
		$where 					    =	'hhr_id != 0 AND hhr_added_by='.$this->session->userdata('userid');        		  $hhr_status   		      =	  $this->input->post('hhr_status', TRUE);
        if($hhr_status)
        {
            $where                  =   $where.' AND hhr_status='.$hhr_status;
        }     	
        $data  			            =   $this->pagination_model->get_pagination($this->tableName,$where,"hhr_added_on DESC",10);                  
        $result_array				=	$data['results'];
        $data['results']            =   array();
        if($result_array           !=  null)
        {
            $this->load->model('mdt_users_model');
            $this->load->model('ldt_career_opening_model');
            foreach($result_array   as  $resval)
            {	   
                $added_by_name      =   '';
                if($resval->hhr_added_by)
                {                   
                    $added_by_name  =   $this->mdt_users_model->get_employee_name($resval->hhr_added_by);
                }
                if($resval->opID)
                {
                    $position_name  =   $this->ldt_career_opening_model->get_career_opening_position($resval->opID);
                }                
                $temp               =   array("added_by_name"=>$added_by_name,"position_name"=>$position_name,"opID" =>$resval->opID,"hhr_added_on"=>$resval->hhr_added_on,"hhr_num_positions"=>$resval->hhr_num_positions ,"hhr_filled_positions"=>$resval->hhr_filled_positions,"hhr_status"=>$resval->hhr_status,"hhr_comment"=>$resval->hhr_comment,"hhr_id"=>$resval->hhr_id);
                $data['results']    = (array) $data['results'];
                array_push($data['results'], $temp);
            }		  
        }        
        return $data;        
	}
	public function add_hiring_request()
	{  		      
        $this->load->model('ldt_career_opening_model');
        return $data                =   $this->ldt_career_opening_model->get_career_opening_names();
	}	
	public function add_new_hiring_request()
	{  	   
        $insert_career              =   '';
        $pos   		        		=	$this->input->post('pos', TRUE);
        $op_num_positions           =	$this->input->post('op_num_positions', TRUE);
        $hhr_comment                =	$this->input->post('comment', TRUE);
        $this->load->model('ldt_career_opening_model');
        if($pos                     ==  "New")
        {
            $opDescription          =	$this->input->post('opDescription', TRUE);
            $opPosition   	 		=	$this->input->post('opPosition', TRUE);
            $opExperience	        =	$this->input->post('opExperience', TRUE);
            $opLocation	            =	$this->input->post('opLocation', TRUE);
            $insert_career          =   $this->ldt_career_opening_model->insert(array('opPosition'=>$opPosition,'opDescription'=>$opDescription,'opExperience'=>$opExperience,'opLocation'=>$opLocation,'opStatus'=>3,'op_num_positions'=>$op_num_positions));            
            $opID                   =	$insert_career;
        }
        else
        {
            $opID                   =	$pos;
            $opDescription          =	$this->input->post('opDescription', TRUE);
            if($opDescription)
            {          
                $details            =   $this->ldt_career_opening_model->get_career_opening_required_details($opID,'opDescription,opPosition,opExperience,opLocation');
                if($details)
                {
                    $string1 = str_replace(' ', '', $details->opDescription);
                    $string2 = str_replace(' ', '', $opDescription);
                    $string1 = stripslashes($string1);
                    $string2 = stripslashes($string2);
                    $string1 = str_replace('\"', '', $string1);
                    $string2 = str_replace('\"', '', $string2);
                    $string1 = str_replace('/"', '', $string1);
                    $string2 = str_replace('/"', '', $string2);
                    $string1 = trim($string1);
                    $string2 = trim($string2); 
                    $string1 = str_replace("\n", '', $string1);
                    $string2 = str_replace("\n", '', $string2);
                    $string1 = str_replace(PHP_EOL, '', $string1);
                    $string2 = str_replace(PHP_EOL, '', $string2);                       
                    $string1 = trim(preg_replace('/\s\s+/', ' ', $string1));
                    $string2 = trim(preg_replace('/\s\s+/', ' ', $string2));                    
                    $string1 = preg_replace( "/\r|\n/", "", $string1 );
                    $string2 = preg_replace( "/\r|\n/", "", $string2 );
                    //echo $string1.$string2;
                    if(strcmp($string1 ,$string2)   ==  0)
                    {
                      // same
                    }
                    else
                    {
                        $insert_career    =   $this->ldt_career_opening_model->insert(array('opPosition'=>$details->opPosition,'opDescription'=>$opDescription,'opExperience'=>$details->opExperience,'opLocation'=>$details->opLocation,'opStatus'=>3,'op_num_positions'=>$op_num_positions));                        
                    }
                }
            }            
            else
            {                
                $update_op              =   $this->ldt_career_opening_model->update_custom(array('op_num_positions'=>'op_num_positions+'.$op_num_positions),'opID='.$opID);
            }
        }
        if($insert_career)
        {
            $op_job_id		    =	substr(str_shuffle('SKYHIDE'),0,4).''.sprintf("%04s",$insert_career);
            $update_op          =   $this->ldt_career_opening_model->update(array('op_job_id'=>$op_job_id),'opID='.$insert_career);
        }        
        $this->insert(array('opID'=>$opID,'hhr_added_by'=>$this->session->userdata('userid'),'hhr_comment'=>$hhr_comment,'hhr_num_positions'=>$op_num_positions));
       redirect(base_url('hrm/hiring_request_added_by_list'));
	}	
    public function cancel_hiring_request()
    {
        $hhr_status             =	$this->input->post('hhr_status', TRUE);
        $hhr_id                 =	$this->input->post('hhr_id', TRUE);
        $opID                   =	$this->input->post('opID', TRUE);
        $pos                    =	$this->input->post('posi', TRUE);
        $hhr_rej_comment        =	$this->input->post('hhr_rej_comment', TRUE);
        $update                 =   $this->update(array('hhr_status'=>$hhr_status,'hhr_rej_comment'=>$hhr_rej_comment),'hhr_id='.$hhr_id);
        $this->load->model('ldt_career_opening_model');
        if($hhr_status          ==  3)  // cancel
        {            
            $opStatus           =   4;
        }
        else if($hhr_status     ==  4)  // Reject
        {
            $opStatus           =   5;
        }
        $update_co              =   $this->ldt_career_opening_model->update_custom(array('op_num_positions'=>'op_num_positions-'.$pos),'opID='.$opID,array('opStatus'=>$opStatus));
    }
    public function update_hiring_request()
    {
        $hhr_id                 =	$this->input->post('hhr_id', TRUE);
        $hhr_num_positions      =	$this->input->post('hhr_num_positions', TRUE);
        $hhr_comment            =	$this->input->post('hhr_comment', TRUE);
        $opID                   =	$this->input->post('opID', TRUE);
        $pp                     =	$this->input->post('pp', TRUE);
        $pos                    =   $hhr_num_positions - $pp;
        if($pos                >=   0)
        {
            $pos                =   '+'.$pos;
        }
        $update                 =   $this->update(array('hhr_num_positions'=>$hhr_num_positions,'hhr_comment'=>$hhr_comment),'hhr_id='.$hhr_id);
        $this->load->model('ldt_career_opening_model');        
        $update_co              =   $this->ldt_career_opening_model->update_custom(array('op_num_positions'=>'op_num_positions'.$pos),'opID='.$opID);
    }
}
?>