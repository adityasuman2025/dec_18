<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_address_sent_mobile_log_model extends CI_Model
{
	function __construct()
	{
		$this->tableName = 'ldt_address_sent_mobile_log';
	}
	
	public function set_phonenumbers_into_DB($numberArr,$address,$user_id)
	{
		foreach ($numberArr as $number)
		{
			$data = array(
							'asm_mobile' => $number,
							'asm_sms_content' => $address,
							'asm_sent_by' => $user_id,
							'asm_status' => '1'
						);

			$this->db->insert($this->tableName, $data);
			
		}//end of foreach
	}//end of set_phonenumbers_into_DB
}
?>