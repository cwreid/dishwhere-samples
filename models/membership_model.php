<?php
class Membership_model extends CI_Model {

	//validate login form
	function validate()
	{
		$this->db->where('email', $this->input->post('login_email'));
		$this->db->where('password', md5($this->input->post('login_pass')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			$userid = $query->row()->id;
			return $userid;
		}else{
			return FALSE;
		};
	}
	
	//add new member to database user table
	function create_member()
	{
		$new_member_insert_data = array (
			'firstname'	=> $this->input->post('reg_firstname'),
			'email'		=> $this->input->post('reg_email'),
			'password'	=> md5($this->input->post('reg_pass')),
			'favedish'	=> $this->input->post('reg_favedish')
		);
		
		$insert = $this->db->insert('users', $new_member_insert_data);
		return $insert;
	}
	
	//update membership data of current user
	function update()
	{
		
		$userid = $this->session->userdata('userid');
		$this->db->where('id', $userid);
		$this->db->where('password', md5($this->input->post('update_oldpass')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			$member_update_data = array (
				'firstname'	=> $this->input->post('update_firstname'),
				'email'	=> $this->input->post('update_email'),
				'password'	=> md5($this->input->post('update_newpass'))
			);
			
		$update = $this->db->update('users', $member_update_data, array('email' => $email));	
			return TRUE;
		}else{
			return FALSE;
		};
	}	
}