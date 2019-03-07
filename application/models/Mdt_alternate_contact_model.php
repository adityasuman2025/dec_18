<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_alternate_contact_model extends CI_Model {
	function __construct() {
		$this->tableName = 'mdt_alternate_contact';
	}
	public function get_contact_list($data_id)
	{
		$this->db->select('ac_id,contact');
		$this->db->where('status','1');
		$this->db->where('data_id',$data_id);
		$this->db->order_by('contact', 'ASC'); 
		$result                       =   $this->db->get($this->tableName)->result();
		 if($result                   !=   null)
        {
            return $result;
        }
        else
        {
            return '';
        }	
	}
    public function add_alt_contact()
    {      
        $insArr                         =   array();
        $userid                         =   $this->session->userdata('userid');
        $insArr['data_id']              =   $this->input->post('data_id');
        $insArr['contact']              =   $this->input->post('alt_number');
        $insArr['added_by']             =   $userid;
        $this->insert_ignore($this->tableName, $insArr);
        $insert_id                      =   $this->db->insert_id();
        return $insert_id;
    }
    function insert_ignore($table, $data, $exclude = array()) 
    {
        $fields                         = $values = array();
        if( !is_array($exclude) ) $exclude = array($exclude);
        foreach( array_keys($data) as $key ) {
            if( !in_array($key, $exclude) ) {
                $fields[] = "`$key`";
                $values[] = "'" . mysql_real_escape_string($data[$key]) . "'";
            }
        }
        $fields = implode(",", $fields);
        $values = implode(",", $values);
        $query =    "INSERT IGNORE INTO `$table` ($fields) VALUES ($values)";
        $this->db->query($query);
    }
}
?>