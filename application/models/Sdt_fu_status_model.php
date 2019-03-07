<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_fu_status_model extends CI_Model
{
    
    function get_fu_list()
    {        
        $this->db->select();
        $this->db->from('sdt_fu_status');
        $this->db->where('fu_status', 1);
        $this->db->where_in('fu_cat', array(1,2,3,4));
        $result = $this->db->get();
        $rtnarray   =   array();
        foreach($result->result()  as $key)
        {
            $rtnarray[$key->fu_id]  =   $key->fu_name;
        }
        //echo $this->db->last_query();
        return $rtnarray;
    }
    function get_fu_list_bde()
    {        
        $this->db->select();
        $this->db->from('sdt_fu_status');
        $this->db->where('fu_status', 1);
        $this->db->where_in('fu_cat', array(6));
        $result = $this->db->get();
        $rtnarray   =   array();
        foreach($result->result()  as $key)
        {
            $rtnarray[$key->fu_id]  =   $key->fu_name;
        }
        //echo $this->db->last_query();
        return $rtnarray;
    }
    function get_fu_list_all()
    {        
        $this->db->select();
        $this->db->from('sdt_fu_status');
        $this->db->where('fu_status', 1);
        $result = $this->db->get();
        $rtnarray   =   array();
        foreach($result->result()  as $key)
        {
            $rtnarray[$key->fu_id]  =   $key->fu_name;
        }
        return $rtnarray;
    }
    function get_fu_type($fu_id)
    {        
        $this->db->select('fu_type');
        $this->db->from('sdt_fu_status');
        $this->db->where('fu_id', $fu_id);
        $result = $this->db->get()->row();
        return $result->fu_type;
    }
}