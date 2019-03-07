<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_application_form_product_model extends CI_Model
{
	function __construct()
	{
		$this->tableName = 'ldt_application_form_product';
	}
	public function submit_application_form_product()
    {
            $app_form_id                        =   $this->input->input_stream('app_form_id', TRUE);
            $data_id                            =   $this->input->input_stream('data_id', TRUE);
            $data_array['data_id']              =   $this->input->input_stream('data_id', TRUE);
            $data_array['no_employee']          =   $this->input->input_stream('no_employee', TRUE);
            $data_array['local_market']         =   $this->input->input_stream('local_market', TRUE);
            $data_array['product_tech_name']    =   $this->input->input_stream('product_tech_name', TRUE);
            $data_array['product_ids']          =   implode (", ", $this->input->input_stream('product_ids', TRUE));
            $data_array['product_discription']  =   $this->input->input_stream('product_discription', TRUE);
            $data_array['product_application']  =   $this->input->input_stream('product_application', TRUE);
            $data_array['product_range']        =   $this->input->input_stream('product_range', TRUE);
            $data_array['design_detail']        =   $this->input->input_stream('design_detail', TRUE);
            $data_array['product_specification']=   $this->input->input_stream('product_specification', TRUE);
            $data_array['size']                 =   $this->input->input_stream('size', TRUE);
            $data_array['lenght']               =   $this->input->input_stream('lenght', TRUE);
            $data_array['breadth']              =   $this->input->input_stream('breadth', TRUE);
            $data_array['weaight']              =   $this->input->input_stream('weaight', TRUE);
            $data_array['testing']              =   $this->input->input_stream('testing', TRUE);
            $data_array['euro_or_local']        =   $this->input->input_stream('euro_or_local', TRUE);
            $data_array['recycling']            =   $this->input->input_stream('recycling', TRUE);
            $data_array['annual_energy']        =   $this->input->input_stream('annual_energy', TRUE);
            $data_array['row_meterials']        =   $this->input->input_stream('row_meterials', TRUE);
            $data_array['notes']                =   $this->input->input_stream('notes', TRUE);
            $convenient_date                    =   $this->input->input_stream('convenient_date', TRUE);
            $data_array['convenient_date']      =   date('Y-m-d',strtotime($convenient_date));
            $data_array['ms_consultant_name']   =   $this->input->input_stream('ms_consultant_name', TRUE);
            $data_array['ms_consultant_website']=   $this->input->input_stream('ms_consultant_website', TRUE);
            $data_array['name_signature']       =   $this->input->input_stream('name_signature', TRUE);
            $data_array['application_date']     =   date('Y-m-d',strtotime($this->input->input_stream('application_date', TRUE)));
            //$checkexist                         =   $this->get_application_form_product_by_data_id($data_id);
            if($app_form_id)
            {
                $data_array['edited_by']        =   $this->session->userdata('userid');
                $data_array['edited_on']        =   date('Y-m-d H:i:s');
                $this->db->where('app_form_id',$app_form_id);
        		$this->db->update($this->tableName, $data_array);
        		//echo $this->db->last_query();
            }
            else
            {
                $data_array['added_by']         =   $this->session->userdata('userid');
                $this->db->insert($this->tableName, $data_array); 
        		//echo $this->db->last_query();
                $app_form_id                        =    $this->db->insert_id();
            }
            return $data_id;
    }
    public function update_form($data_id,$data_array)
    {
        $data_array['edited_by']        =   $this->session->userdata('userid');
        $data_array['edited_on']        =   date('Y-m-d H:i:s');
        $this->db->where('data_id',$data_id);
		$this->db->update($this->tableName, $data_array);
        //echo $this->db->last_query();
    }
    public function get_application_form_product($app_form_id)
    {
        $this->db->select('*');
		$this->db->where('app_form_id',$app_form_id);
		$result        					=	$this->db->get($this->tableName)->row();    
		return $result;
    }
    public function get_application_form_product_by_data_id($data_id)
    {
        $this->db->select('*');
		$this->db->where('data_id',$data_id);
		$result        					=	$this->db->get($this->tableName)->row();   
        //echo $this->db->last_query();
		return $result;
    }
    public function get_application_form_product_with_status($form_status)
    {
        $this->db->select('data_id');
		$this->db->where('form_status',$form_status);
		$result        					=	$this->db->get($this->tableName);    
		return $result->result();
    }
    public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
        //echo $this->db->last_query();
		return count($count);
	}
}
?>