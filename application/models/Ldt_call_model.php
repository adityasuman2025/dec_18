<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_call_model extends CI_Model
{
    function __construct() 
	{
		$this->tableusername = 'ldt_call_log';
         $this->load->model('pagination_model');
	}
    function getcompanyusers()
    {
        $this->db->select("user_id");
        $this->db->from('mdt_users');
        $this->db->where('mdt_users.company',$this->session->userdata('company'));
        $q1 = $this->db->get();
        $prdtarray  =   array();
        if($q1->num_rows() > 0) 
        {
            foreach (($q1->result()) as $row1) 
            {
                $prdtarray[] = $row1->user_id;
            }
            return $prdtarray;
        }
    }
    function getcall($cl_id)
    {        
        $this->db->select();
        $this->db->from('ldt_call_log');
        $this->db->where('cl_id', $cl_id);
        $result = $this->db->get()->row();
        return $result;
    }
    function getallcall()
    {        
        $this->db->select();
        $this->db->from('ldt_call_log');
        $result = $this->db->get();
        return $result->result();
    }
    function getcalllog_user($user_id)
    {
        $this->db->select('ldt_call_log.*, tbl_database.profile_username');
        $this->db->from('ldt_call_log');
        $this->db->where('user_id', $user_id);
        $this->db->join('tbl_database ', 'tbl_database.data_id = ldt_call_log.data_id', 'left');
        $result = $this->db->get();
        return $result->result();
    }
    function getcalllog_dats($did)
    {
        $this->db->select('ldt_call_log.*');
        $this->db->from('ldt_call_log');
        $this->db->where('data_id', $did);        
        $result = $this->db->get();
        //echo $this->db->last_query();
        return $result->result();
    }
    function getcalllog_dats_byuser($did,$user_id)
    {
        $this->db->select('ldt_call_log.*,sdt_fu_status.fu_name');
        $this->db->from('ldt_call_log');
        $this->db->where('data_id', $did);        
        $this->db->where('user_id', $user_id); 
        $this->db->join('sdt_fu_status', 'sdt_fu_status.fu_id = ldt_call_log.call_type', 'left');       
        $result = $this->db->get();
        //echo $this->db->last_query();
        return $result->result();
    }
    function insertcalllog()
    {        
        $insArr                         =   array();
        $fu_date                        =   strtotime($_REQUEST['fu_date']);
        $follow_up                      =   $_REQUEST['follow_up'];
        $starttime                      =   $this->input->post('starttime');
        $now                            =   date('H:i:s');
        $date_a                         =   new DateTime($starttime);
        $date_b                         =   new DateTime($now);
        $interval                       =   date_diff($date_a,$date_b);
        $call_duration                  =   $interval->format('%H:%i:%s');
        $insArr['user_id']              =   $_REQUEST['userId'];
        $insArr['data_id']              =   $_REQUEST['dailid'];
        $insArr['called_on_date']       =   date('Y-m-d');
        $insArr['called_on_time']       =   date('H:i:s');
        $insArr['call_note']            =   $_REQUEST['fu_note'];
        $insArr['call_duration']        =   $call_duration;
        $insArr['call_type']          =   $follow_up;
        $this->db->insert('ldt_call_log', $insArr);
        //echo $this->db->last_query();
        $insert_id                      =   $this->db->insert_id();
        return $insert_id;
    }
    function getallcallreport()
    {        
        $this->db->select('user_id, count(data_id) as call_count');
        $this->db->from('ldt_call_log');
        $this->db->group_by('user_id');
        $result = $this->db->get();
        return $result->result();
    }
    function datauserListingCallsummary($roleId,$usersselect="",$searchDate="",$searchDateto="")
    {
        $this->db->select('BaseTbl.user_id, BaseTbl.email, BaseTbl.username, ldt_call_log.call_type,count(ldt_call_log.call_type) typcnt, SEC_TO_TIME(SUM(TIME_TO_SEC(call_duration))) as Duration');
        $this->db->from('mdt_users as BaseTbl');
        $this->db->join('ldt_call_log', 'BaseTbl.user_id=ldt_call_log.user_id', 'right');       
        if(!empty($searchDate)  && empty($searchDateto)) 
        { 
            $likeCriteria1 = "(ldt_call_log.called_on_date  = '".date('Y-m-d', strtotime($searchDate))."')";
            $this->db->where($likeCriteria1);
        }
        else if(!empty($searchDateto)  && empty($searchDate)) 
        { 
            $likeCriteria1 = "(ldt_call_log.called_on_date  = '".date('Y-m-d', strtotime($searchDateto))."')";
            $this->db->where($likeCriteria1);
        }
        else if(!empty($searchDateto)  && !empty($searchDate)) 
        { 
            $likeCriteria1 = "(ldt_call_log.called_on_date  BETWEEN  '".date('Y-m-d', strtotime($searchDate))."' AND  '".date('Y-m-d', strtotime($searchDateto))."')";
            $this->db->where($likeCriteria1);
        }
        if(!empty($usersselect))
        {
            $this->db->where('ldt_call_log.user_id',$usersselect);
        }
        $this->db->where('BaseTbl.user_status', 1);
        $this->db->where('BaseTbl.user_id > 1');
        $this->db->group_by('BaseTbl.user_id'); 
        $this->db->group_by('ldt_call_log.call_type'); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result();  
        $customised_result          = array();
        foreach($result as $key)
        {
            $customised_result[$key->user_id]['user_id']                    =   $key->user_id;
            $customised_result[$key->user_id]['email']                      =   $key->email;
            $customised_result[$key->user_id]['username']                   =   $key->username;
            $customised_result[$key->user_id]['call_type'][$key->call_type] =   $key->typcnt;
            $customised_result[$key->user_id]['Duration'][]                 =   $key->Duration;
            
        }
        //print_r($customised_result);
        return $customised_result;
    }
}