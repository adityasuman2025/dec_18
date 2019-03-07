<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cdt_pages_model extends CI_Model 
{
	function __construct() 
	{
		$this->tableName = 'cdt_pages';
         $this->load->model('pagination_model');
	}
	public function pagelist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$page_module					=	$this->input->input_stream('page_module', TRUE); 
		$Controller					    =	$this->input->input_stream('Controller', TRUE); 
		$method					        =	$this->input->input_stream('method', TRUE); 
		$submodule					    =	$this->input->input_stream('submodule', TRUE); 
		$where 							=	'page_id != 0';
		if($key!='' && $page_module!='' && $Controller!='')
		{
			$where						=	$where." AND page_name LIKE '%".$key."%'  AND page_module = '".$page_module."' AND Controller = '".$Controller."' AND Controller = '".$Controller."' AND submodule = '".$submodule."' AND method = '".$method."' ";
		}
		if($key)
		{
			$where						=	$where." AND page_name LIKE '%".$key."%'";
		}
		if($page_module)
		{
			$where						=	$where." AND page_module = '".$page_module."' ";
		}	
        if($Controller)
		{
			$where						=	$where." AND page_controller  LIKE '%".$Controller."%'";
		}	
        if($method)
		{
			$where						=	$where." AND page_method   LIKE '%".$method."%'";
		}	
        if($submodule)
		{
			$where						=	$where." AND page_sub_module   LIKE '%".$submodule."%'";
		}			
		$get_pageList  					=   $this->pagination_model->get_pagination("cdt_pages",$where,"page_id ASC",25);	
        return $get_pageList;
	}
	public function insert_pages($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function get_page_details($page_id)
	{
		$this->db->where('page_id='.$page_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
	public function update($up_arr,$cond)
	{
        $this->db->update($this->tableName,$up_arr,$cond);
        return true;
	}
    public function getpagelistformenue($pagelistArray="")
	{
		$this->db->where_in('page_id', $pagelistArray);
        $this->db->where('page_status', 1);
		$page_list  					=   $this->db->get($this->tableName);
        return $page_list;
	}
    public function get_page_sub_module()
	{
		$tsx 							=	$this->input->post('txt');
		$where 							=	'page_id != 0 AND page_status = 1 AND page_module ='.$this->input->post('txt');
        $this->db->distinct();
		$this->db->select('page_sub_module');
		$this->db->where('page_module',$tsx);
		//$this->db->where('page_id !=0');
		$page_list  					=   $this->db->get($this->tableName,$where);
        $returndata                     =   array();
        foreach($page_list->result() as $key)
        {
            $returndata[]               =   $key->page_sub_module;
        }
        return $returndata;
	}	
    public function get_all_modules()
    {
        $data                           =   '';
        $this->db->distinct(); 
        $this->db->select('page_module');
        $this->db->where('page_status','1');
        $result                         =   $this->db->get($this->tableName)->result(); 
        if($result                     != null) 
        {
            foreach($result             as $row) 
            {
                $data[]                 =   $row;
            }            
        }
        return $data;
    }     
	public function get_sub_modules_by_module($mod)
	{
        $this->db->distinct(); 
        $this->db->select('page_sub_module');
		$this->db->where('page_module',$mod);
        $this->db->where('page_status','1');       
        $this->db->order_by('page_sub_module ASC');
		$result                         =   $this->db->get($this->tableName)->result(); 
        return $result;
	} 
	public function get_page_id_by_sub_module($submod,$mod)
	{
        $this->db->select('page_id,page_name');
		$this->db->where('page_sub_module',$submod);
		$this->db->where('page_module',$mod);
        $this->db->where('page_status','1');
        $this->db->order_by('page_name ASC');
		$result                          =   $this->db->get($this->tableName)->result(); 
        return $result;
	}
    public function getPageIdfromCTRLmdl($ctrl,$mdl)
	{
        $this->db->select('page_id');
		$this->db->where('page_controller',$ctrl);
        $mdl                            =   ($mdl)?$mdl:'index';
		$this->db->where('page_method',$mdl);
		$result                          =   $this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->page_id;
        }
		else
        {
            return 0;
        }
	}
}
?>