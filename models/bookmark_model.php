<?php
class Bookmark_model extends CI_Model {
	
	//add or remove bookmark records based on userid and itemid 
	function addremove()
	{
		$itemID = $this->input->post('itemid');
		$addy = $this->input->post('restadd');
		$userID = $this->session->userdata('userid');
		
		$this->db->where('item_id', $itemID);
		$this->db->where('user_id', $userID);
		$query = $this->db->get('favedishes');
		
		if($query->num_rows > 0)
		{
			$this->db->where('item_id', $itemID);
			$this->db->where('user_id', $userID);
			$this->db->delete('favedishes');
			return true;
		}else{
			$fave_insert_data = array (
				'item_id' => $itemID,
				'user_id' => $userID,
				'restadd' => $addy
			);
			
			$this->db->insert('favedishes', $fave_insert_data);
			return false;
		}
	}
	
	//returns user bookmarks based on userid
	function getuserbookmarks()
	{
		$userID = $this->session->userdata('userid');
		$this->db->where('user_id', $userID);
		$this->db->select('item_id, restadd');
		$query = $this->db->get('favedishes');
		
		return $query;
	}
	
	//returns number of bookmarks based on itemid
	function getbookmarkcount($itemid)
	{
		$this->db->where('item_id', $itemid);
		$query = $this->db->get('favedishes');
		return $query->num_rows;
	}
	
	//returns boolean based on whether user has bookmarked a specific item
	function isbookmarked($itemid)
	{
		$userID = $this->session->userdata('userid');
		$this->db->where('item_id', $itemid);
		$this->db->where('user_id', $userID);
		$query = $this->db->get('favedishes');
		if($query->num_rows > 0)
		{
			return true;
		}else{
			return false;
		}
	}
}