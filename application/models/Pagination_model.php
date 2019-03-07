<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pagination_model extends CI_Model {
	function __construct() {
        $this->load->library('pagination');
	}
    public function record_count($table,$cond) 
    {        
        $this->db->where($cond);
        return $this->db->count_all_results($table);
    }
    public function fetch_data($limit,$table,$cond,$page,$order_by='') 
    {
        $page                               =   $page-1;
        if($page                            >   '0')
        {
            $start                          =   ($limit*$page);
        }  
        else
        {
            $start                          =   0;
        }
        $this->db->limit($limit,$start);
        $this->db->where($cond);
        if($order_by)
        {
            $this->db->order_by($order_by);	
        }
        $query                              =   $this->db->get($table);
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $data[]                     =   $row;
            }
            return $data;
        }
        return false;
    }
    public function get_pagination($table,$cond,$order_by='',$rec_per_page='')
    {
        $config                             =   array();
        $controller                         =   $this->router->fetch_class();
        $method                             =   $this->router->fetch_method();
        $config["base_url"]                 =   base_url($controller.'/'.$method);
        $total_row                          =   $this->record_count($table,$cond);
        $config["total_rows"]               =   $total_row;
        $config["per_page"]                 =   ($rec_per_page)?($rec_per_page):10;
        $config['use_page_numbers']         =   TRUE;
        $config['num_links']                =   2;
        $config['cur_tag_open']             =   '&nbsp;<li class="page-item active"><a>';
        $config['cur_tag_close']            =   '</a></li>';
        $config['next_link']                =   'Next';
        $config['prev_link']                =   'Previous';
        $this->pagination->initialize($config);
        if($this->uri->segment(3))
        {
            $page                           =   ($this->uri->segment(3));
        }
        else
        {
            $page                           =   1;
        }
        $data["results"]                    =   $this->fetch_data($config["per_page"],$table,$cond,$page,$order_by);
        $str_links                          =   $this->pagination->create_links();        
        $str_links                          =   str_replace('<a href=','<li class="page-item"><a class="getlnk" href=',$str_links);
        $data["links"]                      =   str_replace('</a>','</a></li>',$str_links);
        $data["page_limit"]                 =   $config["per_page"];
        $data["total_rows"]                 =   $config["total_rows"];
        return $data;
    }
    ###########################################################################
    ###########################################################################
    ###########################################################################      
    public function record_count_sql($sql) 
    {   
        $query 								=	$this->db->query($sql);  
        return $query->num_rows();        
    }
    public function fetch_data_sql($limit,$page,$sql) 
    {
        $page                               =   $page-1;
        if($page                            >   '0')
        {
            $start                          =   ($limit*$page);
        }  
        else
        {
            $start                          =   0;
        }
        $sql                                =   $sql.' LIMIT '.$start.','.$limit;
        $query                              =   $this->db->query($sql);        
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $data[]                     =   $row;
            }
            return $data;
        }
        return false;
    }
    public function get_pagination_sql($sql,$rec_per_page='')
    {
        $config                             =   array();
        $controller                         =   $this->router->fetch_class();
        $method                             =   $this->router->fetch_method();
        $config["base_url"]                 =   base_url($controller.'/'.$method);
        $total_row                          =   $this->record_count_sql($sql);
        $config["total_rows"]               =   $total_row;
        $config["per_page"]                 =   ($rec_per_page)?($rec_per_page):10;
        $config['use_page_numbers']         =   TRUE;
        $config['num_links']                =   2;
        $config['cur_tag_open']             =   '&nbsp;<li class="page-item active"><a>';
        $config['cur_tag_close']            =   '</a></li>';
        $config['next_link']                =   'Next';
        $config['prev_link']                =   'Previous';
        $this->pagination->initialize($config);
        if($this->uri->segment(3))
        {
            $page                           =   ($this->uri->segment(3));
        }
        else
        {
            $page                           =   1;
        }
        $data["results"]                    =   $this->fetch_data_sql($config["per_page"],$page,$sql);
        $str_links                          =   $this->pagination->create_links();        
        $str_links                          =   str_replace('<a href=','<li class="page-item"><a class="getlnk" href=',$str_links);
        $data["links"]                      =   str_replace('</a>','</a></li>',$str_links);
        $data["page_limit"]                 =   $config["per_page"];
        $data["total_rows"]                 =   $config["total_rows"];
        return $data;
    }
}
?>