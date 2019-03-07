<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_sms_info_list'))
{
	function get_sms_info_list($A_Id, $searchCond)
	{
		if($A_Id)
		{
			$ci 				=&	get_instance();
			$ci->load->model('old_emp_to_associate_leadInfo_sms_log_model');
			$smsQry 			=	"SELECT t3.Product_Name as prodName, t4.* FROM products t3 JOIN(SELECT t1.AL_Id, t1.A_Id, rtitle, etalsl_text, etalsl_sent_on FROM emp_to_associate_leadInfo_sms_log t1 JOIN associate_leads t2 ON t1.AL_Id = t2.AL_Id AND t1.A_Id = ".$A_Id." ".$searchCond." ORDER BY etalsl_sent_on DESC) t4 ON t3.Product_Id = t4.rtitle";
			$smsResult			=	$ci->Old_emp_to_associate_leadInfo_sms_log_model->getLeadInfoSMSDetailsQry($smsQry);
			return $smsResult;
		}	   
	}	
}
if ( ! function_exists('get_recharge_status'))
{
	function get_recharge_status($A_Id, $sms_date)
	{
		if($A_Id && $sms_date)
		{
			$ci 				=&	get_instance();
			$ci->load->model('Old_asc_wallet_model');
			$walletCond 		=	"asc_id =".$A_Id." AND aw_added_on BETWEEN '".$sms_date."' AND '".$sms_date."' + INTERVAL 7 DAY  AND aw_status = 2";
			$walletFields 		=	"asc_id, aw_credit, aw_offer";
			$smsResult			=	$ci->Old_asc_wallet_model->getWalletDeatilsByCond($walletCond, $walletFields);
			return $smsResult;
		}	   
	}
}
?>