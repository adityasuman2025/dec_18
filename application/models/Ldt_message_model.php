<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_message_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_message';
		$this->primaryKey = 'msg_id';
	} 
    public function message_inbox($user_id,$limit="")
	{ 
		$this->db->select();
		$this->db->where('msg_to',$user_id);
        if($limit)
        {
        $this->db->limit($limit);
        }
        $this->db->order_by($this->primaryKey, 'DESC');
		$result = $this->db->get($this->tableName);        
        return $result->result();
	}
    public function message_inbox_count($user_id)
	{ 
		$this->db->select();
		$this->db->where('msg_to',$user_id);
		$this->db->where('read_status',1);
		$result = $this->db->get($this->tableName);        
        return $result->num_rows();
	}
    public function message_outbox($user_id)
	{ 
		$this->db->select();
		$this->db->where('msg_from',$user_id);
		$result = $this->db->get($this->tableName);
        $this->db->order_by($this->primaryKey, 'DESC');        
        return $result->result();
	}  
    public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function updateReadstatus($rdmsg,$userid)
	{
        $this->db->set('read_status',2); 
        $this->db->where('read_status',1); 
        $this->db->where('msg_to',$userid); 
        $this->db->where_in('msg_id',$rdmsg); 
		$this->db->update($this->tableName);
        //echo $this->db->last_query();
        return true;
	}
}
?>