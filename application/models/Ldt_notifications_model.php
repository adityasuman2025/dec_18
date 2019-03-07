<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_notifications_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_notifications';
		$this->primaryKey = 'notification_id';
	} 
    public function notifications_inbox($user_id,$limit="")
	{ 
	   //SELECT * FROM ldt_notifications WHERE (to_user = $user_id) or (comp_structure_id = 4 and dept_id ="") or (comp_structure_id = "" and dept_id =1) or (comp_structure_id = 4 and dept_id = 2 )
       $dept_id                     =   $this->session->userdata('department');
       $comp_structure_id           =   $this->session->userdata('office');
        $this->db->select();
        $this->db->or_group_start()   
                ->where('to_user',$user_id)
				->where('notification_unique_status = 1')
                ->group_end();
         if($dept_id        && $comp_structure_id) 
         {      
                $this->db->or_group_start()
                ->where('comp_structure_id',$comp_structure_id)
                ->where('dept_id is NULL',null, false)
				->where('notification_unique_status = 1')
                ->group_end()  
                ->or_group_start()
                ->where('comp_structure_id is NULL', null, false)
                ->where('dept_id',$dept_id)
				->where('notification_unique_status = 1')
                ->group_end() 
                ->or_group_start()
                ->where('comp_structure_id',$comp_structure_id)
                ->where('dept_id',$dept_id)
				->where('notification_unique_status = 1')
                ->group_end(); 
          }  
        if($limit)
        {
        $this->db->limit($limit);
        }    
        $this->db->order_by($this->primaryKey, 'DESC');
		$result = $this->db->get($this->tableName);  
       // echo $this->db->last_query();      
        return $result->result();
	}
    public function notifications_inbox_count($user_id,$last_notification_read)
	{ 
        $dept_id                     =   $this->session->userdata('department');
        $comp_structure_id           =   $this->session->userdata('office');
		$this->db->select();
        $this->db->group_start();
		$this->db->or_group_start()   
                ->where('to_user',$user_id)
                ->group_end();
         if($dept_id        && $comp_structure_id) 
         {      
                $this->db->or_group_start()
                ->where('comp_structure_id',$comp_structure_id)
                ->where('dept_id is NULL',null, false)
				->where('notification_unique_status = 1')
                ->group_end()  
                ->or_group_start()
                ->where('comp_structure_id is NULL', null, false)
                ->where('dept_id',$dept_id)
				->where('notification_unique_status = 1')
                ->group_end() 
                ->or_group_start()
                ->where('comp_structure_id',$comp_structure_id)
                ->where('dept_id',$dept_id)
				->where('notification_unique_status = 1')
                ->group_end(); 
          } 
        $this->db->group_end(); 
		$this->db->where('notification_time > ',$last_notification_read);
		$result = $this->db->get($this->tableName); 
        //echo $this->db->last_query();        
        return $result->num_rows();
	}
    public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        //echo $this->db->last_query();      
        return $this->db->insert_id();
	}
	public function getPendingNotificationList($notification_status)
	{
		$where                  		   = "notification_status = 2";
		$notification_status               =   $this->input->post('notification_status', TRUE); 
		if($notification_status)
        {
            $where                          =  " notification_status ='".$notification_status."'";
        }
		$get_notifList  					=   $this->pagination_model->get_pagination("ldt_notifications",$where,"notification_id desc",25);	
        return $get_notifList;  
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->last_query();
		$this->db->update($this->tableName, $up_arr);        
    }
	public function change_notification_status()
	{
		$uparr               =  array();
		$notification_status =	$this->input->post('notification_status');
		$uparr['notification_status'] =	$notification_status;
		$uparr['notification_approvedBy'] =	$this->session->userdata('employee');
		$uparr['notification_approvedOn'] =	date('Y-m-d H:i:s');
	 	$notification_id     =	$this->input->post('notification_id'); 
        $this->update($uparr,'notification_id='.$notification_id);
		$this->session->set_flashdata('success_message',($notification_status==1)?"Notification Approved Successfully...!!":(($notification_status==2)?"Notification Status Successfully Changed as Pending":"News Rejected Successfully...!!"));
		redirect(base_url('hrm/notification_dashboard'));	
		
	}		
    public function add_notifications(){
        $notification_id                   =   $this->input->input_stream('notification_id',TRUE);
        $notification_title                =   $this->input->input_stream('notification_tittle',TRUE);
        $message_notification              =   $this->input->input_stream('message_notification', TRUE); 
        $notification_image_path           =    $this->input->input_stream('notification_image_path', TRUE); 
        $notification_video_url            =    $this->input->input_stream('notification_video_url', TRUE); 
    }
    public function dashboard_message()
    {
        $dept_id                      	   =    $this->session->userdata('department');
        $qry                               =    "dept_id = ".$dept_id." and  notification_status = 1 and notification_unique_status = 2"; 
        $get_notifList                     =    $this->pagination_model->get_pagination("ldt_notifications",$qry,"notification_approvedOn desc",25);
        return $get_notifList;	   
    }
    public function dashboard_message_header($limit="")
    { 
        $dept_id                      	   =     $this->session->userdata('department');
        $this->db->select();
        $this->db->or_group_start()   
        ->where('comp_structure_id is NULL', null, false)
        ->where('dept_id',$dept_id)
        ->where('notification_status = 1')
        ->where('notification_unique_status = 2')
        ->group_end();
        if($limit)
        {
            $this->db->limit($limit);
        }    
        $this->db->order_by('notification_approvedOn', 'DESC');
        $result                             =   $this->db->get($this->tableName);   
        return $result->result();
    }
    public function getAllNotificationList($notification_status)
    {
		
        $where                               = "notification_id != 0 and from_employee =".$this->session->userdata('employee');
		if($notification_status)
		{
			 $where                         = $where.' and notification_status ='.$notification_status;
		}
        $get_notifList                       = $this->pagination_model->get_pagination("ldt_notifications",$where,"notification_id desc",25);  
        return $get_notifList;  
    }
    public function messageDb_inbox_count($last_notification_read)
    {
        $dept_id                   =   $this->session->userdata('department');
        $this->db->select();
        $this->db->or_group_start()   
        ->where('comp_structure_id is NULL', null, false)
        ->where('dept_id',$dept_id)
        ->where('notification_status = 1')
        ->where('notification_unique_status = 2')
        ->group_end(); 
        $this->db->where('notification_approvedOn > ',$last_notification_read);
        $result = $this->db->get($this->tableName); 
        //echo $this->db->last_query();        
        return $result->num_rows();
    }
	public function get_dbmsg_details($notification_id)
	{
		$this->db->where('notification_id='.$notification_id);
		$result =   $this->db->get($this->tableName)->row(0);         
		return $result;
	}
}
?>