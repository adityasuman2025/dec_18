<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cdt_leave_calendar_model extends CI_Model {
	function __construct() {
		$this->tableName = 'cdt_leave_calendar';
	}
    public function getCount($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                          =	$query->result();
		return count($count);
	}
	function chekAnualLeav($cond)
	{
		$query 			        		=	$this->db->select("cal_leave_id")
												->from($this->tableName)
												->where($cond)
												->get();
        return $query->result_array();
	}
	function getallLeaves($cond)
	{
		$this->db->select("*");
		$this->db->from($this->tableName);
		$this->db->where($cond);
		$query                          =	$this->db->get();
		return $query->result_array();
	}    
	function getLeaveDays($from, $to)
	{
		$this->load->model('time_model');
		$getLeaveCal					=	$this->getallLeaves("cal_leave_full_date between '".$from."' and '".$to."'");
		$calLeaves						=	array();
		foreach($getLeaveCal as $val)
		{
			array_push($calLeaves,$val['cal_leave_full_date']);
			
		}
		$allDates						=	$this->time_model->getDatesFromRange($from, $to);
		$result 						= 	array_diff($allDates, $calLeaves);
		$result							=	implode(',',$result);
		return $result;
	}   
    public function get_cal_leave($year,$month)
	{
		$this->db->select('cal_leave_full_date,cal_leave_desc');
		$this->db->where('cal_leave_year',$year);
		$this->db->where('cal_leave_month',$month);
		$result                         =	$this->db->get($this->tableName)->result();        
        return $result;
	}    
    public function get_cal_leave_desc($date)
	{
		$this->db->select('cal_leave_desc');
		$this->db->where('cal_leave_full_date',$date);
		$result                         =	$this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->cal_leave_desc;
        }
		else
        {
            return '';
        }
	} 
	public function get_total_holidays($puYear,$puMonth, $emp_doj="", $endDate="", $type="")
	{
		$cond	                        =	"";
		if($endDate && $emp_doj && $type==	1)
		{
			$cond                       =	" and cal_leave_full_date between '".$emp_doj."' and '".$endDate."'";
		}
		$where							=	"cal_leave_month = ".$puMonth." and cal_leave_year = ".$puYear."  and cal_leave_full_date < date(now()) ".$cond;
		$this->db->where($where);
		$count                          =	$this->db->get($this->tableName)->result();
		return count($count);
	}
	public function check_is_the_day_holiday($emp_id,$date)
	{
		$this->load->model('tdt_user_roles_model');
        $this->load->model('sdt_roles_model');
        $primary_role                   =   $this->tdt_user_roles_model->get_emp_primary_role($emp_id); 
        if($primary_role)
        {
            $to_be_present_days         =   $this->sdt_roles_model->get_to_be_present_days($primary_role);
            if($to_be_present_days)
            {
                $to_be_present_days_arr =   str_replace("1","Mon",$to_be_present_days);
                $to_be_present_days_arr =   str_replace("2","Tue",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("3","Wed",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("4","Thu",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("5","Fri",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("6","Sat",$to_be_present_days_arr);
                $to_be_present_days_arr =   explode(',',$to_be_present_days_arr); 
            }
            $day                   		=   date('D', strtotime($date));
            if(in_array($day, $to_be_present_days_arr))
            {
                return 0; // working
            }    
            else
            {
                return 1;   // holiday
            }           
        }
        else
        {
            return 0;
        }           
	}
	public function leave_count_from_to($from,$to)
	{
		$query	                        =	$this->db->where("cal_leave_full_date between '".$from."' and '".$to."'")
					                        ->get($this->tableName);
		$count                          =	$query->result();
		return count($count);
	}
    public function get_cal_leaves($cal_year,$cal_month)
	{
        $this->db->select('cal_leave_dates,cal_leave_desc');
		$this->db->where('cal_leave_year',$cal_year);
		$this->db->where('cal_leave_month',$cal_month);
		$query                          =	$this->db->get($this->tableName);
		if($query                      !=	null)
        {
            $retarray					=	array();
            $arr    					=	array();
            $arr_desc 					=	array();
            $result 					=	$query->result_array();
            foreach($result             as $arr_all)
            {
                $arr[]					=	$arr_all['cal_leave_dates'];
                $dat					=	$arr_all['cal_leave_dates'];
                $arr_desc[$dat]			=	$arr_all['cal_leave_desc'];	
            }
            array_push($retarray,$arr);
            array_push($retarray,$arr_desc);
            return $retarray;
        }
		else
        {
            return '';
        }
	}
    public function add_cal()
    {   
		$cal_id								=	0; 
		$leave_year							=	$this->input->post('cal_leave_year');
		$leave_month						=	$this->input->post('cal_leave_month');
		$deleteExisting						=	$this->delete("cal_leave_year='".$leave_year."' and cal_leave_month='".$leave_month."'");
		$date_arr							=	$this->input->post('leave_status');
		$leave_desc_arr						=	$this->input->post('leave_desc');
        $ins_arr['cal_leave_year']          =   $leave_year;
        $ins_arr['cal_leave_month']         =   $leave_month;
		foreach($date_arr                   as $date_all)
		{	
			$ins_arr['cal_leave_dates']		=	$date_all;
			$key							=	$date_all -1;
			$ins_arr['cal_leave_desc']		=	$leave_desc_arr[$key];
			$digmonth						=	sprintf("%02d",$leave_month);
			$digday							=	sprintf("%02d",$date_all);
			$ins_arr['cal_leave_full_date']	=	$leave_year."-".$digmonth."-".$digday;
			$cal_id							=	$this->insert($ins_arr);
		}
        return true;
    }
    public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();        
    }
    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);        
    }
    public function delete($cond)
	{
        $this->db->where($cond);
        $this->db->delete($this->tableName);
        return true;        
    }
}
?>