<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_exit_form_answers_model extends CI_Model {
	function __construct() {
		$this->tableName            = 'ldt_exit_form_answers'; 
		$this->primaryKey           = 'efa_id';
	}   
    public function get_count($cond)
    {
        $query	                    =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                      =	$query->result();
		return count($count);
    }   
    public function update($arr,$cond)
	{
        $this->db->update($this->tableName,$arr,$cond);
        return true;
	}
    public function insert($arr)
	{
        $this->db->insert($this->tableName,$arr);
        return true;
	}
    public function get_exit_form_answers($emp_id)
	{
        $this->db->select('efa_question_id,efa_answer');
        $this->db->where('emp_id',$emp_id);
        $result                     =   $this->db->get($this->tableName);
        if($result                 !=   null)
        {
            return $result->result_array(); 
        }
        else
        {
            return '';
        } 
	}
    public function get_exit_form_answers_ques($emp_id)
	{
        $sql                        =   "SELECT efa_question_id,efa_answer,t2.efq_ques as efq_ques FROM ldt_exit_form_answers t1 JOIN (SELECT efq_ques,efq_id FROM sdt_exit_form_questions where efq_type=1) t2 ON t1.efa_question_id = t2.efq_id and emp_id = ".$emp_id;
        $result                     =   $this->db->query($sql);
        if($result                 !=   null)
        {
            return $result->result_array(); 
        }
        else
        {
            return '';
        } 
	}
    public function get_exit_form_answers_feedback($emp_id)
	{
        $sql                        =   "SELECT efa_question_id,efa_answer,efa_added_on,t2.efq_ques as efq_ques FROM ldt_exit_form_answers t1 JOIN (SELECT efq_ques,efq_id FROM sdt_exit_form_questions where efq_type=2) t2 ON t1.efa_question_id = t2.efq_id and emp_id = ".$emp_id;
        $result                     =   $this->db->query($sql)->row();
        if($result                 !=   null)
        {
            return $result; 
        }
        else
        {
            return '';
        } 
	}
    public function submit_exit_form()
	{        
        $efa_answer                =   $this->input->input_stream('efa_answer', TRUE);  
        $efa_question_id           =   $this->input->input_stream('efa_question_id', TRUE);  
        $emp_id                    =   $this->session->userdata('employee');  
        for($i=0;$i<count($efa_answer);$i++)
        {
            $arr                   =   array('emp_id'=>$emp_id,'efa_question_id'=>$efa_question_id[$i],'efa_answer'=>$efa_answer[$i]);
            $ins                   =    $this->insert($arr);
        }
	}
    public function submit_exit_feed_back()
	{        
        $efa_answer                =   $this->input->input_stream('efa_answer', TRUE);  
        $efa_question_id           =   $this->input->input_stream('efa_question_id', TRUE);  
        $emp_id                    =   $this->input->input_stream('emp_id', TRUE); 
        $arr                       =   array('emp_id'=>$emp_id,'efa_question_id'=>$efa_question_id,'efa_answer'=>$efa_answer,'efa_type'=>2);
        $ins                       =    $this->insert($arr);        
        //$mail_val			    	=	$mail_details->insertMailEntry($_POST['emp_id'],56,$_POST['lwd']);
        // Notification
       
	}
}