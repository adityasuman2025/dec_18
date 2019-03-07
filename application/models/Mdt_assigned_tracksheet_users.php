<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Mdt_assigned_tracksheet_users extends CI_Model 
	{
		function __construct() 
		{
			$this->tableName = 'mdt_assigned_tracksheet_users';
		}
		
	//function to assign the users to a tracksheet
		public function assign_users_to_tracksheet($tracksheet_id, $type, $user_ids)
		{
			foreach ($user_ids as $key => $user_id) 
	        {
	            $user_id = $user_id;

	            $data = array(
	            		'tracksheet_id'=>$tracksheet_id,
	            		'user_id'=>$user_id,
	            		'type'=>$type,
	            		'status'=> 1
	            	);
	            
				$query_run = $this->db->insert('mdt_assigned_tracksheet_users', $data);
	        }			
		}

	//function to get assigned users of a tracksheet
		public function get_assigned_users_of_tracksheet($tracksheet_id)
		{
			$query = "SELECT mdt_assigned_tracksheet_users.*, mdt_users.username FROM mdt_assigned_tracksheet_users, mdt_users WHERE mdt_assigned_tracksheet_users.tracksheet_id = $tracksheet_id AND mdt_users.user_id = mdt_assigned_tracksheet_users.user_id AND mdt_assigned_tracksheet_users.type = 2 ";			
			$query_run = $this->db->query($query);		
			$result = $query_run->result_array();

			return $result;
		}
	}
?>