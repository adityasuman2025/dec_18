<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Time_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        return true;
    }
    public function timeDiference($in_time,$out_time,$add='')
    {	
        if($in_time                    !=  '00:00:00'   &&  $out_time  !=  '00:00:00')
        {
            $inTime						=	strtotime($in_time);
            $lateTime 					=	strtotime($out_time);
            $diffTime 					=	$inTime - $lateTime;
            $diffTime                   =   $diffTime+$add;
            $total_Diff					=	$this->secondsToTime($diffTime);
            $hrs						=	(strlen($total_Diff['h']) == 1)?'0'.$total_Diff['h']:$total_Diff['h'];
            $mins						=	(strlen($total_Diff['m']) == 1)?'0'.$total_Diff['m']:$total_Diff['m'];
            $secs						=	(strlen($total_Diff['s']) == 1)?'0'.$total_Diff['s']:$total_Diff['s'];
            return $hrs. ":".$mins.":".$secs;
        }
		else
        {
            return "00:00:00";
        }
    }
    
    function sum_the_time($times) 
	{
      $seconds = 0;
      foreach ($times as $time)
      {
        list($hour,$minute,$second) = explode(':', $time);
        $seconds += $hour*3600;
        $seconds += $minute*60;
        $seconds += $second;
      }
      $hours = floor($seconds/3600);
      $seconds -= $hours*3600;
      $minutes  = floor($seconds/60);
      $seconds -= $minutes*60;
      return "{$hours}:{$minutes}:{$seconds}";
	}
    public function secondsToTime($seconds)
    {	
		$hours 							= 	floor($seconds / (60 * 60));				// extract hours
	 	$divisor_for_minutes 			= 	$seconds % (60 * 60);						// extract minutes
		$minutes 						=	floor($divisor_for_minutes / 60);
		$divisor_for_seconds 			= 	$divisor_for_minutes % 60;					// extract the remaining seconds
		$seconds 						= 	ceil($divisor_for_seconds);
		$obj 							= 	array(	"h" => (int) $hours,"m" => (int) $minutes,	"s" => (int) $seconds, );
		return $obj;	
    }
	function getToday() 
	{
		return date('Y-m-d');
	}
	function getXday($x) 
	{
		return date('Y-m-d',strtotime("-".$x." days"));
	}
	function countWeekendDays($start, $end)
	{
		$start_ts 						= 	strtotime($start); // start time stamp
		$end_ts 						= 	strtotime($end); // end time stamp
		$day_sec			 			= 	86400;
		$interval 						= 	($end_ts - $start_ts)/$day_sec; // number of days
		$count 							= 	0;
		$working_ts 					= 	$start_ts;
		while ($working_ts <= $end_ts) 
		{ // loop through each day to find saturdays
			$day 						= 	date('w', $working_ts);
			if ($day 					== 	6)
			{ // this is a saturday
				  $count++;
			}
			$working_ts 				= 	$working_ts + $day_sec;
		}
		return $count;
	}
	public function getDatesFromRange($startDate, $endDate)
	{
		$return 						= 	array($startDate);
		$start 							= 	$startDate;
		$i								=	1;
		if (strtotime($startDate) 		< 	strtotime($endDate))
		{
		   while (strtotime($start) 	< 	strtotime($endDate))
			{
				$start 					= 	date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
				$return[] 				= 	$start;
				$i++;
			}
		}
		return $return;
	}
	public function get_valid_date_ranges($startDate, $endDate)
	{
		$return 						= 	array($startDate);
		$start 							= 	$startDate;
		$i								=	1;
		if (strtotime($startDate) 		< 	strtotime($endDate))
		{
		   while (strtotime($start) 	< 	strtotime($endDate))
			{
				$start 					= 	date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                if($this->check_valid_date($start))
                {
                    $return[] 			= 	$start;
                }				
				$i++;
			}
		}
		return $return;
	}
	public function check_valid_date($date)
	{
        $d                              =   DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
	}
	function get_total_days($start, $end)
	{
		return round(abs(strtotime($end) - strtotime($start))/86400) + 1;
	}
}
?>