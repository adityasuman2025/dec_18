<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//////////////	//////////////	//////////////	////////////// ///////////
//////////////			get Holidays CntFor Tech 	        /// //////////
/////////////	//////////////	//////////////	//////////////	//////////	
function get_saturday_count_technology($puMonth, $puYear, $totalDays)
{
	$weekCnt			=	0;
	for($i=0;$i<=$totalDays;$i++)
	{
		if($i<10)
		{
			$dateVal	=	$puYear."-".$puMonth."-0".$i;
		}
		else
		{
			$dateVal	=	$puYear."-".$puMonth."-".$i;
		}
		$weekVal		=	date('w', strtotime( $dateVal));
		if($weekVal		==	6)
		{
			$weekCnt++;
		}
	}
	if($weekCnt			==	1)
	{
		$weekCnt;
	}
	else if($weekCnt	==	2)
	{
		$weekCnt		=	1;
	}
	else if($weekCnt	==	3	||	$weekCnt	==	4)
	{
		$weekCnt		=	2;
	}
	else if($weekCnt	>=	5)
	{
		$weekCnt		=	3;
	}
	return $weekCnt;
}
function rand_string( $length ) 
{
    $chars 								= 	"SKYHIDE";
    return substr(str_shuffle($chars),0,$length);
}
/* 
to get permission page  emp/manager/admin 

eg:  activity_permission_page(module_name,sub_module,page_name);
*/
if ( ! function_exists('activity_permission_page'))
{	
	function activity_permission_page($module="",$submodule="",$page="") 
	{
		if($module)
		{
			$ci						=	&get_instance(); 
			$menu_list				=	$ci->session->userdata('menuelist');
			$result					=	"";
			$menudata				=	$menu_list;
			$module_name			=	strtoupper($module);
			$menudata				=	$menu_list[$module_name];
			if($submodule)
			{
				$menudata			=	$menu_list[$module_name][$submodule];
			}
			foreach($menudata as $key=>$val)
			{
				$e_page_name		=	(isset($val['Methord']))?$val['Methord']:"";				
				if(($e_page_name	==	$page.'_s')	||	($e_page_name	==	$page.'_e')	||	($e_page_name	==	$page.'_m')	||	($e_page_name	==	$page.'_hr')) 
				{
					 $result 		=	$module.'/'.$e_page_name;
				}
			}
			return ($result)?$result:$module.'/dashboard';
		}
	}
}
?>