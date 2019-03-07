<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ltd_application_documents_model extends CI_Model {
	function __construct() 
	{
		$this->tableName = 'ltd_application_documents';
	}
	public function get_document_by_owner($data_id)
	{
		$this->db->select('*');
        $this->db->where('data_id',$data_id);
		$this->db->where('status = 1');
		$this->db->order_by('doc_order ASC');
		$retval	                =	$this->db->get($this->tableName);    
        return $retval->result();
	}
    public function submit_documents()
    {
        $insArr                     =   array();
        $document                   =   "";
        $data_id                    =   $this->input->post('data_id');
        if($_FILES['document']['name'])
        {
            $file_element_name                  =   'document';
            $full_name                          =	$_FILES['document']['name'];
            $getext                             =	(explode('.', $full_name));
            $ext                                =	 end($getext);
            $custom_name                        =   "CUST".$data_id.'_'.date('ymd_His').".".$ext;
			  $config['upload_path']            = $this->config->item('img_upload_url').'Document/signed_copy';
			  //$config['allowed_types']          = 'gif|jpg|png|doc|docx|txt|xlsx|zip|pdf';
			  $config['allowed_types']          = 'gif|jpg|png';
			  $config['max_size']               = 1024 * 8;
			  $config['encrypt_name']           = FALSE;
			  $config['file_name']              = $custom_name;
			  $this->load->library('upload', $config);
			  if ($this->upload->do_upload($file_element_name))
			  {
				$document                      =   $custom_name;
			  }
			 @unlink($_FILES[$file_element_name]);
		} 
        $insArr['data_id']          =   $data_id;
        $insArr['doc_type']         =   $this->input->post('doc_type');
        $insArr['doc_name']         =   $this->input->post('doc_name');
        $insArr['doc_order']        =   $this->input->post('doc_order');
        $insArr['doc_note']         =   $this->input->post('doc_note');
        $insArr['doc_url']          =   $document;
        $this->db->insert($this->tableName, $insArr);
        $insert_id                      =   $this->db->insert_id();
        return $insert_id;
    }
}
?>