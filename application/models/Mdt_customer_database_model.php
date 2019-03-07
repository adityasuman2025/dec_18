<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_customer_database_model extends CI_Model
{
    function __construct() 
	{
		$this->tableName = 'mdt_customer_database';
         $this->load->model('pagination_model');
	}
    function databaseList($futype="",$multy_level_user_id="")
    {
        $searchText                     =   $this->input->input_stream('searchText', TRUE);
        $StatesSearch                   =   $this->input->post('StatesSearch');
        $datype                         =   $this->input->post('datype');
        $user                           =   $this->input->post('usersselect'); 
        $dateselected                   =   $this->input->post('dateselected'); 
        $typedata                       =   $this->input->post('typedata'); 
        $sql                            =   "SELECT mdt_customer_database.*, t1.username as data_owner, t2.username AS field_executive_name 
                                            FROM mdt_customer_database 
                                            LEFT JOIN mdt_users as t1 ON t1.user_id = mdt_customer_database.lead_owner 
                                            LEFT JOIN mdt_users as t2 ON t2.user_id = mdt_customer_database.field_executive";
        if($datype)
        {
            $sql						=	$sql." LEFT JOIN sdt_fu_status  ON sdt_fu_status.fu_id = mdt_customer_database.follow_up_status ";
        }
        $sql                            =	$sql." WHERE data_id != 0 AND mdt_customer_database.data_status IN (1,2) ";
		if($searchText!='')
		{
			$sql						=	$sql." AND (mobile_number  = '".$searchText."' OR  profile_name  LIKE '%".$searchText."%') ";
		}
		if($futype!=''    && $futype!='ALL')
		{
			$sql						=	$sql." AND follow_up_status  = '".$futype."' ";
		}
        if($datype)
        {
            if($datype                  ==  3)
            {
                $sql					=	$sql." AND ( sdt_fu_status.fu_cat  > 4  OR sdt_fu_status.fu_cat = 3 )";
            }
            else{
                $sql						=	$sql." AND sdt_fu_status.fu_cat  = '".$datype."' ";
            }
        }
        if($dateselected)
        {
            $sql						=	$sql." AND DATE(data_added_on)  = '".date('Y-m-d' , strtotime($dateselected))."' ";
        }
        if($typedata            ==  'NDO')
        {
            $sql						=	$sql." AND lead_owner  = 0";
        }
        else if($typedata       ==  'NCM')
        {
            $this->db->where('last_called_by', '0');
            $sql						=	$sql." AND last_called_by  = 0";
        }
        if($user)
        {
            $sql						=	$sql." AND mdt_customer_database.lead_owner =".$user;
        }
        if($StatesSearch)
        {
            $sql						=	$sql." AND mdt_customer_database.state_id =".$StatesSearch;
        }
        if($multy_level_user_id)
        {
            array_push($multy_level_user_id,$this->session->userdata('userid'));            
            $comaval                    =   implode (", ", $multy_level_user_id);
            $sql						=	$sql." AND (mdt_customer_database.data_added_by in ('".$comaval."')  OR mdt_customer_database.lead_owner in ('".$comaval."')  OR mdt_customer_database.last_called_by in ('".$comaval."')  OR mdt_customer_database.field_executive in ('".$comaval."') )
            ";
        }			
        $sql                            =	$sql." ORDER BY follow_up_date ";
        //echo $sql;        
		//$get_pageList  					=   $this->pagination_model->get_pagination("mdt_customer_database",$where,"data_id ASC",25);	
		$get_pageList  					=   $this->pagination_model->get_pagination_sql($sql,100);	
        return $get_pageList;
    }
    function addNewdata($self_upload="")
    {      
        $insArr                         =   array();
        $userid                         =   $this->session->userdata('userid');
        $insArr['profile_name']         =   $this->input->post('profile_name');
        $insArr['mobile_number']        =   $this->input->post('mobile_number');
        $insArr['profile_email']        =   $this->input->post('profile_email');
        $insArr['profile_address']      =   $this->input->post('profile_address');
        $insArr['state_id']             =   $this->input->post('md_state');
        $insArr['city_id']              =   $this->input->post('md_city');
        $insArr['data_added_by']        =   $userid;
        if($self_upload)
        {
            $insArr['lead_owner']       =   $self_upload;
        }
        $this->insert_ignore('mdt_customer_database', $insArr);
        $insert_id                      =   $this->db->insert_id();
        return $insert_id;
    }
    public function update_followup($data_id,$follow_up_status)
    {
        $data_array['follow_up_status'] =   $follow_up_status;
        $data_array['data_updated_on']  =   date('Y-m-d H:i:s');
        $this->db->where('data_id',$data_id);
		$this->db->update($this->tableName, $data_array);
        //echo $this->db->last_query();
    }
    public function update($data_id,$data_array)
    {
        $this->db->where('data_id',$data_id);
		$this->db->update($this->tableName, $data_array);
    }
    function updatedata()
    {
        $updArr                         =   array();
        $updArr['profile_name']         =   $this->input->post('profile_name');
        $updArr['mobile_number']        =   $this->input->post('mobile_number');
        $updArr['profile_email']        =   $this->input->post('profile_email');
        $updArr['profile_address']      =   $this->input->post('profile_address');
        $updArr['state_id']             =   $this->input->post('md_state');
        $updArr['city_id']              =   $this->input->post('md_city');
        $data_id                        =   $this->input->post('data_id');
        $this->db->where('data_id', $data_id);
        if($data_id)
        {
        $this->db->update('mdt_customer_database', $updArr); 
        }
        return TRUE;
    }
    function updatedatastatus()
    {
        $data_id                        =   $this->input->post('data_id');
        if($this->input->post('status')    ==  1)
        {
            $data_status    =   2;
        }
        elseif($this->input->post('status')    ==  2)
        {
            $data_status    =   1;
        }
        elseif($this->input->post('status')    ==  5)
        {
            $data_status    =   5;
        }
        $updArr         =   array('data_status'=>$data_status, 'data_updated_on'=>date('Y-m-d H:i:s'));
        if(is_array($data_id))
        {
            $this->db->where_in('data_id', $data_id);
        }
        else{
            $this->db->where('data_id', $data_id);
        }
        $this->db->update('mdt_customer_database', $updArr); 
       //echo $this->db->last_query();
        return TRUE;
    }
    function getdatabase($data_id)
    {        
        $this->db->select();
        $this->db->from('mdt_customer_database');
        $this->db->where('data_id', $data_id);
        $query = $this->db->get();
        $result = $query->result();     
        return $result;
    }
    function Add_bulkData($insArr)
    {
        $this->insert_ignore($this->tableName, $insArr);
        $insert_id                      =   $this->db->insert_id();
        return ($insert_id)?$insert_id:0;
    }
    function insert_ignore($table, $data, $exclude = array()) 
    {
        $fields = $values = array();
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
    function updateFollowup()
    {
        $fu_date                            =   strtotime($this->input->post('fu_date'));
        $dailid                             =   $this->input->post('dailid');
        $follow_up                          =   $this->input->post('follow_up');
        $futype                             =   "";
        $this->load->model('sdt_fu_status_model');
        if($follow_up)
        {
        $futype                             =   $this->sdt_fu_status_model->get_fu_type($follow_up);
        }
        if($futype                          ==  2)
        {
            $callstatus                     =   $follow_up;
        }
        else
        {
            $callstatus                     =   4;
        }
        $updArr                             =   array();
        $updArr['follow_up_status']         =   ($follow_up)?$follow_up:6;
        $updArr['last_called_by']           =   $this->session->userdata('userid');
        $updArr['text_note']                =   $this->input->post('fu_note');
        $updArr['profile_address']                =   $this->input->post('profile_address');
        if($this->input->post('profile_email'))
        {
        $updArr['profile_email']            =   $this->input->post('profile_email');
        }
        $updArr['call_date']                =   date('y-m-d');
        $updArr['call_time']                =   date('H:i:s');
        $updArr['follow_up_date']           =   date('Y-m-d', $fu_date);//strtotime already in fu_date
        $updArr['follow_up_time']           =   date('H:i:s', $fu_date);//strtotime already in fu_date
        $updArr['call_status']              =   $callstatus;
        $this->db->where('data_id', $dailid);
        $this->db->update('mdt_customer_database', $updArr); 
        //echo $this->db->last_query();
        return TRUE;
    }
    function getdatabaseDetail($data_id)
    {        
        $this->db->select('mdt_customer_database.*,mdt_users.username as call_made,sdt_state.state_name,sdt_city.city_name,sdt_fu_status.fu_cat,sdt_fu_status.fu_name,sdt_fu_status.fu_type');
        $this->db->from('mdt_customer_database');
        $this->db->where('data_id', $data_id);
        $this->db->join('sdt_state', 'sdt_state.state_id = mdt_customer_database.state_id', 'left');
        $this->db->join('sdt_city', 'sdt_city.city_id = mdt_customer_database.city_id', 'left');
        $this->db->join('mdt_users', 'mdt_users.user_id = mdt_customer_database.last_called_by', 'left');
        $this->db->join('sdt_fu_status', 'sdt_fu_status.fu_id = mdt_customer_database.follow_up_status', 'left');
        $query = $this->db->get();
        $result = $query->result();  
        //echo $this->db->last_query();   
        return $result;
    }
    function get_leads_by_owner($lead_owner)
    {        
        $searchText                 =   $this->input->post('searchText');
        $fu_date                    =   $this->input->post('fu_date');
        $call_date                  =   $this->input->post('call_date');
        $this->db->select('data_id,mobile_number,profile_name,profile_email,follow_up_date,follow_up_time,follow_up_status,last_called_by, call_date, call_time, text_note,sdt_fu_status.fu_cat,sdt_fu_status.fu_name');
        $this->db->from('mdt_customer_database');
        $this->db->join('sdt_fu_status', 'sdt_fu_status.fu_id = mdt_customer_database.follow_up_status', 'left');
        $this->db->where('lead_owner', $lead_owner);
        $this->db->where('data_status', 1);
        if(!empty($searchText)) {            
            $likeCriteria = "(mdt_customer_database.mobile_number  = '".$searchText."'
                            OR  mdt_customer_database.profile_name  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if($fu_date)
        {
            $this->db->where('mdt_customer_database.follow_up_date', date('Y-m-d' , strtotime($fu_date)));
        }
        if($call_date)
        {
            $this->db->where('mdt_customer_database.call_date', date('Y-m-d' , strtotime($call_date)));
        }
        $this->db->order_by('follow_up_date','ASC');
        $query = $this->db->get();
        $result = $query->result();     
        //echo $this->db->last_query();
        return $result;
    }
    function get_leads_by_FieldExicutive($FieldExicutive)
    {        
        $this->db->select('data_id,mobile_number,profile_name,profile_email,follow_up_date,follow_up_time,follow_up_status,last_called_by, call_date, call_time, text_note');
        $this->db->from('mdt_customer_database');
        $this->db->where('field_executive', $FieldExicutive);
        $fuarray        =   array('13','14','18','19');
        $this->db->where('data_status', 1);
        $this->db->where_in('follow_up_status', $fuarray);
        $this->db->order_by('follow_up_date','ASC');
        $query = $this->db->get();
        $result = $query->result();     
        //echo $this->db->last_query();
        return $result;
    }
    function assigndatatoemp()
    {
        $data_id                        =   $this->input->post('data_id');
        $Users_id                       =   $this->input->post('Users_id');
        $updArr                         =   array('lead_owner'=>$Users_id);
        $data_id_array = explode(',',$data_id);
        $this->db->where_in('data_id', $data_id_array);
        //$this->db->where('lead_owner', 0);
        $this->db->update('mdt_customer_database', $updArr); 
        //echo $this->db->last_query();
        return TRUE;
    }
    function assigndatatoFE($data_id,$updArr,$skipp="")
    {
        $data_id_array = explode(',',$data_id);
        $this->db->where_in('data_id', $data_id_array);
        if(!$skipp)
        {
        $this->db->where('field_executive', 0);
        }
        $this->db->update('mdt_customer_database', $updArr); 
        echo $this->db->last_query();
        return TRUE;
    }
    function flushdata()
    {
        $data_id                        =   $this->input->post('data_id');
        if($data_id)
        {
        $updArr         =   array('lead_owner'=>0);
        $this->db->where('data_id', $data_id);
        $this->db->update('mdt_customer_database', $updArr); 
        }
        return TRUE;
    }
    function get_lead_count_for_employee()
    {
        $this->db->select('lead_owner, count(data_id) as cnt');
        $this->db->from('mdt_customer_database');
        $this->db->where('data_status', 1);
        $this->db->group_by('lead_owner');
        $query = $this->db->get();
        $result = $query->result();     
        //echo $this->db->last_query();
        return $result;
    }
    function datauserListing($roleId,$searchText ="",$searchDate="",$searchDateto="")
    {
        $this->db->select('BaseTbl.user_id,username,email,
        count(case when sdt_fu_status.fu_cat = 1 then 1 end) as newlead,
        count(case when sdt_fu_status.fu_cat = 2 then 1 end) as folloup,
        count(case when sdt_fu_status.fu_cat = 3 then 1 end) as converted,
        count(case when sdt_fu_status.fu_cat = 4 then 1 end) as rejected,
        count(case when sdt_fu_status.fu_cat > 4 then 1 end) as other
        ');
        $this->db->from('mdt_users as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.username  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('tdt_user_roles.ur_id =', $roleId);
        $this->db->where('BaseTbl.user_status =', 1);
        $this->db->where('BaseTbl.user_id !=1');
        $this->db->join('mdt_customer_database', 'mdt_customer_database.lead_owner = BaseTbl.user_id', 'left');
        $this->db->join('sdt_fu_status', 'sdt_fu_status.fu_id = mdt_customer_database.follow_up_status', 'left');
        $this->db->join('tdt_user_roles', 'tdt_user_roles.user_id = BaseTbl.user_id', 'left');
        $this->db->group_by('BaseTbl.user_id'); 
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result();        
        return $result;
    }
    function final_submit($data_id)
    {
        $this->db->select();
        $this->db->from('mdt_customer_database');
        $this->db->where('data_id', $data_id);
        $query              =   $this->db->get();
        $result_obj         =   $query->result();
        $result             =   $result_obj[0];
        ######################### BASIC DATA  // mdt_customer_database #####################################
        $data_id            =   $result->data_id;
        $mobile_number      =   $result->mobile_number;
        $profile_name       =   $result->profile_name;
        $profile_email      =   $result->profile_email;
        $profile_address    =   $result->profile_address;
        $cb_type            =   $result->cb_type;
        $country_id         =   $result->country_id;
        $state_id           =   $result->state_id;
        $city_id            =   $result->city_id;
        $data_added_by      =   $result->data_added_by;
        $lead_owner         =   $result->lead_owner;
        $field_executive    =   $result->field_executive;
        $follow_up_date     =   $result->follow_up_date;
        $follow_up_time     =   $result->follow_up_time;
        $follow_up_status   =   $result->follow_up_status;
        $last_called_by     =   $result->last_called_by;
        $call_status        =   $result->call_status;
        $call_date          =   $result->call_date;
        $call_time          =   $result->call_time;
        $text_note          =   $result->text_note;
        $data_status        =   $result->data_status;
        ######################### \\ BASIC DATA  // mdt_customer_database #####################################
        $this->db->select();
        $this->db->from('ldt_application_form');
        $this->db->where('data_id', $data_id);
        $query_application          =   $this->db->get();
        $Applresult_obj             =   $query_application->result();
        $applicationDT              =   $Applresult_obj[0];
        $form_id                    =   $applicationDT->form_id;
        $alternate_mobile           =   $applicationDT->alternate_mobile;
        $web_site                   =   $applicationDT->web_site;
        $contact_person             =   $applicationDT->contact_person;
        $scheme                     =   $applicationDT->scheme;
        $Scope                      =   $applicationDT->Scope;
        $Exclusions                 =   $applicationDT->Exclusions;
        $activites                  =   $applicationDT->activites;
        $sub_contractors            =   $applicationDT->sub_contractors;
        $type_of_work               =   $applicationDT->type_of_work;
        $total_work                 =   $applicationDT->total_work;
        $carried_out                =   $applicationDT->carried_out;
        $process_outsourced         =   $applicationDT->process_outsourced;
        $legal_obligation           =   $applicationDT->legal_obligation;
        $Product_Process            =   $applicationDT->Product_Process;
        $number_of_haccp            =   $applicationDT->number_of_haccp;
        $working_hours              =   $applicationDT->working_hours;
        $annual_energy              =   $applicationDT->annual_energy;
        $facilities_processes       =   $applicationDT->facilities_processes;
        $number_of_energy           =   $applicationDT->number_of_energy;
        $power_consumed             =   $applicationDT->power_consumed;
        $number_information_systems =   $applicationDT->number_information_systems;
        $number_of_it_platforms     =   $applicationDT->number_of_it_platforms;
        $number_of_servers_used     =   $applicationDT->number_of_servers_used;
        $number_of_workstations     =   $applicationDT->number_of_workstations;
        $remote_users               =   $applicationDT->remote_users;
        $number_of_networks         =   $applicationDT->number_of_networks;
        $remote_working             =   $applicationDT->remote_working;
        $certification_program      =   $applicationDT->certification_program;
        $third_party_certification  =   $applicationDT->third_party_certification;
        $convenient_date            =   $applicationDT->convenient_date;
        $ms_consultant_name         =   $applicationDT->ms_consultant_name;
        $ms_consultant_website      =   $applicationDT->ms_consultant_website;
        $name_signature             =   $applicationDT->name_signature;
        $application_date           =   $applicationDT->application_date;
        $form_status                =   $applicationDT->form_status;
        $approval_comment           =   $applicationDT->approval_comment;
        $status                     =   $applicationDT->status;
        $this->db->select();
        $this->db->from('ldt_application_site');
        $this->db->where('form_id', $form_id);
        $query_sitedt               =   $this->db->get();
        $siteresult_obj             =   $query_sitedt->result();
        $this->db->select();
        $this->db->from('ldt_application_quotation');
        $this->db->where('data_id', $data_id);
        $query_quotan               =   $this->db->get();
        $quotation_result           =   $query_quotan->result();
        ####################################################################
        ####################################################################
        $this->db->set('client_id', '(SELECT t.newcid FROM (SELECT COALESCE( MAX( client_id ) , 0 ) +1 as newcid FROM mdt_customer_master where 1) t WHERE 1) ',FALSE);
        $masterInsert['client_name']        = $profile_name;
        $masterInsert['cb_type']            = $cb_type;
        $masterInsert['consultant_id']      = $lead_owner;
        $masterInsert['location']           = $country_id;//1 local //2 forign
        $masterInsert['certificate_status'] = 1;//pending
        $masterInsert['scope']              = $Scope;
        $masterInsert['contact_name']       = $contact_person;
        $masterInsert['contact_address']    = $profile_address;
        $masterInsert['remarks']            = $this->input->post('comment');
        $masterInsert['note']               = "new Profile";
        $masterInsert['added_by']           = $this->session->userdata('userid');
        $masterInsert['data_id']            = $data_id;
        $masterInsert['user_name']          = $profile_email;
        $masterInsert['password']           = $this->encryption->encrypt("password");//default password is password
        $this->db->insert('mdt_customer_master', $masterInsert);
        //echo $this->db->last_query();
        $cm_id                      =   $this->db->insert_id();
        ##################################################################
        $contactInsert['cm_id']             = $cm_id;//insert Id
        $contactInsert['contact_number']    = $mobile_number;
        $contactInsert['contact_email']     = trim($profile_email);
        $contactInsert['is_primary']        = 1;
        $contactInsert['added_by']          = $this->session->userdata('userid');
        $this->db->insert('mdt_customer_contact', $contactInsert);
        ##################################################################
        if($siteresult_obj)
        {
            foreach($siteresult_obj         as $siteadd)
            {
            $siteDetail['cm_id']            =   $cm_id;//inserrtId
            $siteDetail['site_address']     =   $siteadd->site_address;
            $siteDetail['added_by']         =   $this->session->userdata('userid');
            $this->db->insert('mdt_customer_site', $siteDetail);
            }
        }
        #################################################################
        if($quotation_result)
        {
            foreach($quotation_result       as  $Qtn)
            {
                $scheemDetail['cm_id']      =   $cm_id;//insert Id
                $scheemDetail['scheme_id']  =   $Qtn->scheme;
                $scheemDetail['added_by']   =   $this->session->userdata('userid');
                $this->db->insert('mdt_customer_scheme', $scheemDetail);
            }
        }
        ###################################################################
        $updArr         =   array('follow_up_status'=>20, 'data_updated_on'=>date('Y-m-d H:i:s'));
        $this->db->where('data_id', $data_id);
        $this->db->update('mdt_customer_database', $updArr); 
    }
    public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
        //echo $this->db->last_query();
		return count($count);
	}
}