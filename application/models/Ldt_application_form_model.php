<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_application_form_model extends CI_Model
{
	function __construct()
	{
		$this->tableName = 'ldt_application_form';
	}
	public function submit_application_form()
    {
            $form_id                            =   $this->input->input_stream('form_id', TRUE);
            $data_id                            =   $this->input->input_stream('data_id', TRUE);
            $data_array['data_id']              =   $this->input->input_stream('data_id', TRUE);
            $data_array['alternate_mobile']     =   $this->input->input_stream('alternate_mobile', TRUE);
            $data_array['web_site']             =   $this->input->input_stream('web_site', TRUE);
            $data_array['contact_person']       =   $this->input->input_stream('contact_person', TRUE);
            $data_array['scheme']               =   implode (", ", $this->input->input_stream('scheme', TRUE));
            $data_array['Scope']                =   $this->input->input_stream('Scope', TRUE);
            $data_array['Exclusions']           =   $this->input->input_stream('Exclusions', TRUE);
            $data_array['activites']            =   $this->input->input_stream('activites', TRUE);
            $data_array['sub_contractors']      =   $this->input->input_stream('sub_contractors', TRUE);
            $data_array['type_of_work']         =   $this->input->input_stream('type_of_work', TRUE);
            $data_array['total_work']           =   $this->input->input_stream('total_work', TRUE);
            $data_array['carried_out']          =   $this->input->input_stream('carried_out', TRUE);
            $data_array['process_outsourced']   =   $this->input->input_stream('process_outsourced', TRUE);
            $data_array['legal_obligation']     =   $this->input->input_stream('legal_obligation', TRUE);
            $data_array['Product_Process']      =   $this->input->input_stream('Product_Process', TRUE);
            $data_array['number_of_haccp']      =   $this->input->input_stream('number_of_haccp', TRUE);
            $data_array['working_hours']        =   $this->input->input_stream('working_hours', TRUE);
            $data_array['annual_energy']        =   $this->input->input_stream('annual_energy', TRUE);
            $data_array['facilities_processes'] =   $this->input->input_stream('facilities_processes', TRUE);
            $data_array['number_of_energy']     =   $this->input->input_stream('number_of_energy', TRUE);
            $data_array['power_consumed']       =   $this->input->input_stream('power_consumed', TRUE);
            $data_array['number_information_systems']   =  $this->input->input_stream('number_information_systems', TRUE);
            $data_array['number_of_it_platforms']       =   $this->input->input_stream('number_of_it_platforms', TRUE);
            $data_array['number_of_servers_used']       =   $this->input->input_stream('number_of_servers_used', TRUE);
            $data_array['number_of_workstations']       =   $this->input->input_stream('number_of_workstations', TRUE);
            $data_array['remote_users']         =   $this->input->input_stream('remote_users', TRUE);
            $data_array['number_of_networks']   =   $this->input->input_stream('number_of_networks', TRUE);
            $data_array['remote_working']       =   $this->input->input_stream('remote_working', TRUE);
            $data_array['certification_program']=   $this->input->input_stream('certification_program', TRUE);
            $data_array['third_party_certification']    =   $this->input->input_stream('third_party_certification', TRUE);
            $convenient_date                    =   $this->input->input_stream('convenient_date', TRUE);
            $data_array['convenient_date']      =   date('Y-m-d',strtotime($convenient_date));
            $data_array['ms_consultant_name']   =   $this->input->input_stream('ms_consultant_name', TRUE);
            $data_array['ms_consultant_website']=   $this->input->input_stream('ms_consultant_website', TRUE);
            $data_array['name_signature']       =   $this->input->input_stream('name_signature', TRUE);
            $data_array['application_date']     =   date('Y-m-d',strtotime($this->input->input_stream('application_date', TRUE)));
            
            $checkexist                         =   $this->get_application_form_by_data_id($data_id);
            
            if($form_id)
            {
                $data_array['edited_by']        =   $this->session->userdata('userid');
                $data_array['edited_on']        =   date('Y-m-d H:i:s');
                $this->db->where('form_id',$form_id);
        		$this->db->update($this->tableName, $data_array);
        		//echo $this->db->last_query();
            }
            elseif(count($checkexist)>0)
            {
                $data_array['edited_by']        =   $this->session->userdata('userid');
                $data_array['edited_on']        =   date('Y-m-d H:i:s');
                $this->db->where('data_id',$data_id);
        		$this->db->update($this->tableName, $data_array);
        		//echo $this->db->last_query();
            }
            else
            {
                $data_array['added_by']         =   $this->session->userdata('userid');
                $this->db->insert($this->tableName, $data_array); 
        		//echo $this->db->last_query();
                $form_id                        =    $this->db->insert_id();
            }
            $department                         =   $this->input->input_stream('department', TRUE);
            $dep_emp                            =   $this->input->input_stream('dep_emp', TRUE);
            if($department  && $form_id)
            {
                $dep_count      =   count($department);
                if($dep_count)
                {
                    $this->db->where('form_id', $form_id);
                    $this->db->delete('ldt_application_department');
                    $new_dep_array  =   array();
                    for($i=0;$i<$dep_count;$i++)
                    {
                         
                        $new_dep_array['form_id']           =   $form_id;
                        $new_dep_array['department_name']   =   $department[$i];
                        $new_dep_array['employee_count']    =   $dep_emp[$i];
                        $new_dep_array['added_by']          =   $this->session->userdata('userid');
                        if($department[$i] && $dep_emp[$i])
                        {
                        $this->db->insert('ldt_application_department',$new_dep_array);
                        }
                    }
                }
                //department
            }
            //siteAddress
            $siteAddress                        =   $this->input->input_stream('siteAddress', TRUE);
            
            if($siteAddress  && $form_id)
            {
                $pe_s1e                         =   $this->input->input_stream('pe_s1e', TRUE);
                $pe_s2e                         =   $this->input->input_stream('pe_s2e', TRUE);
                $pe_s3e                         =   $this->input->input_stream('pe_s3e', TRUE);
                $pe_total                       =   $this->input->input_stream('pe_total', TRUE);
                
                $pt_s1e                         =   $this->input->input_stream('pt_s1e', TRUE);
                $pt_s2e                         =   $this->input->input_stream('pt_s2e', TRUE);
                $pt_s3e                         =   $this->input->input_stream('pt_s3e', TRUE);
                $pt_total                       =   $this->input->input_stream('pt_total', TRUE);
                
                $cw_s1e                         =   $this->input->input_stream('cw_s1e', TRUE);
                $cw_s2e                         =   $this->input->input_stream('cw_s2e', TRUE);
                $cw_s3e                         =   $this->input->input_stream('cw_s3e', TRUE);
                $cw_total                       =   $this->input->input_stream('cw_total', TRUE);
                
                $site_count      =   count($siteAddress);
                if($site_count)
                {
                    $this->db->where('form_id', $form_id);
                    $this->db->delete('ldt_application_site');
                    $site_array  =   array();
                    for($j=0;$j<$site_count;$j++)
                    {
                         
                        $site_array['form_id']          =   $form_id;
                        $site_array['site_address']     =   $siteAddress[$j];
                        $site_array['permanent_emp']    =   $pe_s1e[$j].','.$pe_s2e[$j].','.$pe_s3e[$j].','.$pe_total[$j];
                        $site_array['part_time_emp']    =   $pt_s1e[$j].','.$pt_s2e[$j].','.$pt_s3e[$j].','.$pt_total[$j];
                        $site_array['contract_emp']     =   $cw_s1e[$j].','.$cw_s2e[$j].','.$cw_s3e[$j].','.$cw_total[$j];
                        $site_array['added_by']         =   $this->session->userdata('userid');
                        if($siteAddress[$j])
                        {
                        $this->db->insert('ldt_application_site',$site_array);
                        }
                    }
                }
                //department
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
    public function get_application_form($form_id)
    {
        $this->db->select('*');
		$this->db->where('form_id',$form_id);
		$result        					=	$this->db->get($this->tableName)->row();    
		return $result;
    }
    public function get_application_form_by_data_id($data_id)
    {
        $this->db->select('*');
		$this->db->where('data_id',$data_id);
		$result        					=	$this->db->get($this->tableName)->row();    
		return $result;
    }
    public function get_applicacation_department_detail($form_id)
    {
        $this->db->where('form_id',$form_id);
		$result        					=	$this->db->get('ldt_application_department');    
		return $result->result();
    }
    public function get_applicacation_site_detail($form_id)
    {
        $this->db->where('form_id',$form_id);
		$result        					=	$this->db->get('ldt_application_site');    
		return $result->result();
    }
    public function get_application_form_with_status($form_status)
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