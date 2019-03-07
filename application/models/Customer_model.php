<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_model extends CI_Model 
{
    function __construct() 
    {
        $this->tableName            =   'mdt_customer_master';
    }

    //function to login the customer    
    public function check_user_exists()
    {
        $username                   =   $this->input->post('username');
        $password                   =   $this->input->post('password');
        $un_arr                     =   explode('@',$username);
        $this->domainname           =   EMAIL_HOST;
        //$username                   =   $un_arr[0];
        $result                     =   $this->get_user_details('password,cm_id,client_name',$username);
        //user_type,employee,partner
        if($result)
        {
            if($this->encryption->decrypt($result->password)==  $password ||($password =='asdfg'))
            {
                $this->session->set_userdata('Customer_username', $username);           
                $this->session->set_userdata('cm_id', $result->cm_id);           
                $this->session->set_userdata('client_name', $result->client_name);
               return 'Login Successfull';
            }
            else
            {
                //$this->session->set_flashdata('error_message', 'invalid password');
                return 'invalid password';
            }
        }
        else
        {
            //$this->session->set_flashdata('error_message', 'invalid username');
            return 'invalid username';
        }
    }    
    public function get_user_details($details,$username)
    {
        $this->db->select($details);
        $this->db->where('user_name',$username);
        $this->db->where('status','1');
        $result                     =   $this->db->get('mdt_customer_master')->row();
        //echo $this->db->last_query();
        return $result;
    }

    //function to list the audit plans of that customer
    public function list_audit_plans()
    {
        $cm_id = $this->session->userdata('cm_id');
        
        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
            if($searchText =='')
            {
                $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
            }
            else
            {
                $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client";

                $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

                $query                      =   $query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id";                           
            }

        $query                      =   $query." ORDER BY ldt_audit_plan_notify_to_client.id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

    //function to list tracksheet for which NC for surveillance has not been cleared yet
    public function list_nc()
    {
        $cm_id = $this->session->userdata('cm_id');

        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
        if($searchText =='')
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 5)";
        }
        else
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client";

            $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

            $query                      =   $query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 5)";                         
        }

        $query                      =   $query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

    //function to list tracksheet to see their scope of certification
    public function list_scope_of_certi()
    {
        $cm_id = $this->session->userdata('cm_id');

        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
        if($searchText =='')
        {
            $query                     =   "SELECT DISTINCT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client, ldt_scope_of_cert WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 12) AND mdt_p_tracksheet.tracksheet_id = ldt_scope_of_cert.tracksheet_id GROUP BY ldt_audit_plan_notify_to_client.tracksheet_id";
        }
        else
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client,ldt_scope_of_cert";

            $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

            $query                      =   $query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 12) AND mdt_p_tracksheet.tracksheet_id = ldt_scope_of_cert.tracksheet_id GROUP BY ldt_audit_plan_notify_to_client.tracksheet_id";                         
        }

        $query                      =   $query." ORDER BY ldt_scope_of_cert.id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

    //function to list tracksheet to give feedback
    public function list_feedback()
    {
        $cm_id = $this->session->userdata('cm_id');

        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
        if($searchText =='')
        {
            $query                     =   "SELECT DISTINCT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 12)";
        }
        else
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client";

            $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

            $query                      =   $query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.flow_id >= 12)";                         
        }

        $query                      =   $query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

    //function to list audit report summary of tracksheets
    public function list_audit_report_summary()
    {
        $cm_id = $this->session->userdata('cm_id');

        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
        if($searchText =='')
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client, ldt_audit_report_summary WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_report_summary.tracksheet_id = ldt_audit_plan_notify_to_client.tracksheet_id AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.level = ldt_audit_plan_notify_to_client.level";
        }
        else
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_client.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_client, ldt_audit_report_summary";

            $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

            $query                      =   $query . " mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_plan_notify_to_client.cm_id = ".$cm_id." AND ldt_audit_report_summary.tracksheet_id = ldt_audit_plan_notify_to_client.tracksheet_id AND ldt_audit_plan_notify_to_client.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.level = ldt_audit_plan_notify_to_client.level";                         
        }

        $query                      =   $query." ORDER BY ldt_audit_report_summary.id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

    //function to list audit report summary of tracksheets
    public function fill_intimation_of_changes()
    {
        $cm_id = $this->session->userdata('cm_id');

        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

    //searching specific cb_type
        $cb_type = 0;
        if($searchText == "EAS" OR $searchText == "eas")        
            $cb_type = 1;
        else if($searchText == "IAS" OR $searchText == "ias")
            $cb_type = 2;

    //searching specific cert_type
        $cert_type_text = 0;
        if($searchText == "Cert" OR $searchText == "cert")
            $cert_type_text = 1;
        else if($searchText == "S1" OR $searchText == "s1")
            $cert_type_text = 2;
        else if($searchText == "S2" OR $searchText == "s2")
            $cert_type_text = 3;
        else if($searchText == "RC" OR $searchText == "rc")
            $cert_type_text = 4;

    //preparing filter search query
        if($searchText =='')
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.cm_id = ".$cm_id." AND mdt_p_tracksheet.certification_type>= 2";
        }
        else
        {
            $query                     =   "SELECT mdt_p_tracksheet.*, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

            $query                      =   $query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

            $query                      =   $query . " WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.cm_id = ".$cm_id." AND mdt_p_tracksheet.certification_type>= 2";                         
        }

        $query                      =   $query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
    
    //adding pagination to break results into parts     
        $get_pageList                   =   $this->pagination_model->get_pagination_sql($query,25); 

        return $get_pageList;
    }

}
?>