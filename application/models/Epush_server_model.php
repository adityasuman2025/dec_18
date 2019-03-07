<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Epush_server_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        
            
    }
	public function epush_function($query,$ins_id="")
	{	   
		 
		echo  $query;	
	} 
	public function read_csv()
	{	
        $month                          =   $this->input->post('month');
		$year                           =   $this->input->post('year');
        $cur_month                      =   ($month)?($month):round(date('m'));
        $cur_year                       =   ($year)?($year):date('Y');
        $table                          =   'DeviceLogs_'.$cur_month.'_'.$cur_year;  
		$added_on						=	date('Y-m-d H:i:s');
		if ($_FILES['csv']['size']		> 	0) 
		{
			$file		 				= 	$_FILES['csv']['tmp_name'];
			$handle	 					= 	fopen($file,"r");
			$insCnt						=	0;
			while ($data 				= 	fgetcsv($handle,1000,",","'"))
			{
				if ($data[1]            && $data[0]) 
				{
                    $DownloadDate       =   date('Y-m-d H:i:s');
                    $DeviceId           =   1;
                    $UserId             =	$data[0];
                    $LogDate            =   $data[1];
                    $Direction          =   'in';  
                    $C1                 =   'in'; 
                    $C4                 =   0;
                    $C5                 =   1;
                    $C6                 =   0;
                    $C7                 =   0;
                    $WorkCode           =   0;                        
                    $qry                =   'INSERT IGNORE INTO '.$table.'( DownloadDate, DeviceId, UserId, LogDate, Direction, C1, C4, C5, C6, C7, WorkCode) VALUES ("'.$DownloadDate.'","'.$DeviceId.'","'.$UserId.'","'.$LogDate.'","'.$Direction.'","'.$C1.'","'.$C4.'","'.$C5.'","'.$C6.'","'.$C7.'","'.$WorkCode.'")';
                    $this->epush_function($qry);                    
					$insCnt				=	$insCnt + 1;
				}
			}            
		}
	}  
}
?>