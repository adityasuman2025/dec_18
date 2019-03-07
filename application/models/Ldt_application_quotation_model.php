<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_application_quotation_model extends CI_Model
{
	function __construct()
	{
		$this->tableName = 'ldt_application_quotation';
	}
	public function submit_quotation()
    {
            $aq_id                                      =   $this->input->input_stream('aq_id', TRUE);
            $data_array['data_id']                      =   $this->input->input_stream('data_id', TRUE);
            $data_array['scheme']                       =   $this->input->input_stream('scheme', TRUE);
            $data_array['stage_1_manday']               =   $this->input->input_stream('stage_1_manday', TRUE);
            $data_array['stage_2_manday']               =   $this->input->input_stream('stage_2_manday', TRUE);
            $data_array['surveillance_manday']          =   $this->input->input_stream('surveillance_manday', TRUE);
            $data_array['reduction_in_manday']          =   $this->input->input_stream('reduction_in_manday', TRUE);
            $data_array['application_Fee']              =   $this->input->input_stream('application_Fee', TRUE);
            $data_array['application_Fee_gst']          =   $this->input->input_stream('application_Fee_gst', TRUE);
            $data_array['application_Fee_total']        =   $this->input->input_stream('application_Fee_total', TRUE);
            $data_array['application_Fee_remark']       =   $this->input->input_stream('application_Fee_remark', TRUE);
            $data_array['stage_1_assessment_fee']       =   $this->input->input_stream('stage_1_assessment_fee', TRUE);
            $data_array['stage_1_assessment_gst']       =   $this->input->input_stream('stage_1_assessment_gst', TRUE);
            $data_array['stage_1_assessment_total']     =   $this->input->input_stream('stage_1_assessment_total', TRUE);
            $data_array['stage_1_assessment_remark']    =   $this->input->input_stream('stage_1_assessment_remark', TRUE);
            $data_array['stage_2_assessment_fee']       =   $this->input->input_stream('stage_2_assessment_fee', TRUE);
            $data_array['stage_2_assessment_gst']       =   $this->input->input_stream('stage_2_assessment_gst', TRUE);
            $data_array['stage_2_assessment_total']     =   $this->input->input_stream('stage_2_assessment_total', TRUE);
            $data_array['stage_2_assessment_remark']    =   $this->input->input_stream('stage_2_assessment_remark', TRUE);
            $data_array['fee_total']                    =   $this->input->input_stream('fee_total', TRUE);
            $data_array['gst_total']                    =   $this->input->input_stream('gst_total', TRUE);
            $data_array['total_total']                  =   $this->input->input_stream('total_total', TRUE);
            $data_array['total_remark']                 =   $this->input->input_stream('total_remark', TRUE);
            $data_array['fee_1st_Surveillance']         =   $this->input->input_stream('fee_1st_Surveillance', TRUE);
            $data_array['remark_1st_Surveillance']      =   $this->input->input_stream('remark_1st_Surveillance', TRUE);
            $data_array['fee_2nd_Surveillance']         =   $this->input->input_stream('fee_2nd_Surveillance', TRUE);
            $data_array['remark_2nd_Surveillance']      =   $this->input->input_stream('remark_2nd_Surveillance', TRUE);
            
            $data_array['temp1_fee']                    =   $this->input->input_stream('temp1_fee', TRUE);
            $data_array['temp1_gst']                    =   $this->input->input_stream('temp1_gst', TRUE);
            $data_array['temp1_total']                  =   $this->input->input_stream('temp1_total', TRUE);
            $data_array['temp1_remark']                 =   $this->input->input_stream('temp1_remark', TRUE);
            
            $data_array['temp2_fee']                    =   $this->input->input_stream('temp2_fee', TRUE);
            $data_array['temp2_gst']                    =   $this->input->input_stream('temp2_gst', TRUE);
            $data_array['temp2_total']                  =   $this->input->input_stream('temp2_total', TRUE);
            $data_array['temp2_remark']                 =   $this->input->input_stream('temp2_remark', TRUE);
            
            if($aq_id)
            {
                $data_array['edited_by']        =   $this->session->userdata('userid');
                $data_array['edited_on']        =   date('Y-m-d H:i:s');
                $data_array['approve_status']   =   1;
                $this->db->where('aq_id',$aq_id);
        		$this->db->update($this->tableName, $data_array);
        		//echo $this->db->last_query();
            }
            else
            {
                $data_array['added_by']         =   $this->session->userdata('userid');
                $this->db->insert($this->tableName, $data_array); 
        		//echo $this->db->last_query();
                $aq_id                          =    $this->db->insert_id();
            }
            return $aq_id;
    }
    public function update_quotation($aq_id,$data_array)
    {
        $data_array['edited_by']        =   $this->session->userdata('userid');
        $data_array['edited_on']        =   date('Y-m-d H:i:s');
        $this->db->where('aq_id',$aq_id);
		$this->db->update($this->tableName, $data_array);
        //echo $this->db->last_query();
    }
    public function get_quotation($aq_id)
    {
        $this->db->select('*');
		$this->db->where('aq_id',$aq_id);
		$result        					=	$this->db->get($this->tableName);    
		return $result->result();
    }
    public function get_quotation_withscheem($data_id,$scheme)
    {
        $this->db->select('*');
		$this->db->where('data_id',$data_id);
		$this->db->where('scheme',$scheme);
		$result        					=	$this->db->get($this->tableName);    
		return $result->result();
    }
    public function get_quotation_by_dataid($data_id)
    {
        $this->db->select('*');
		$this->db->where('data_id',$data_id);
		$result        					=	$this->db->get($this->tableName);    
		return $result->result();
    }
    public function get_application_quotation_with_status($quotation_status)
    {
        $this->db->select('*');
		$this->db->where('approve_status',$quotation_status);
		$result        					=	$this->db->get($this->tableName);    
		return $result->result();
    }
    
	
}
?>