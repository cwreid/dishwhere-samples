<?php
	class Member extends CI_Controller {
		
		
		function __construct()
		{
			parent::__construct();
			$this->is_logged_in();
		}
		
		//display dishes that member has previously saved
		function saved()
		{
			$model = $this->load->model('bookmark_model');
			$bookmarks = $this->bookmark_model->getuserbookmarks();
			if ($bookmarks->num_rows() > 0)
			{
				$data['main_content'] = 'member_saved';
				$data['menu_items'] = $bookmarks;
				$data['model'] = $model;
				$this->load->view('includes/member_saved_template', $data);	
			}else{
				$data['main_content'] = 'member_savednocontent';
				$this->load->view('includes/member', $data);
			};								
		}
		
		//add or remove bookmark from database
		function bookmark()
		{
			$this->load->model('bookmark_model');
			$addremove = $this->bookmark_model->addremove();
		}
		
		//check database to see how many members have saved specified menu item
		function favecount()
		{
			$itemid = $this->input->post('itemid');
			$this->load->model('bookmark_model');
			$bookmarkcount = $this->bookmark_model->getbookmarkcount($itemid);
			echo $bookmarkcount;
		}
		
		//check database to see if member has bookmarked menu item and returns boolean
		function isbookmarked()
		{
			$itemid = $this->input->post('itemid');
			$this->load->model('bookmark_model');
			$isbookmarked = $this->bookmark_model->isbookmarked($itemid);
			echo $isbookmarked;
		}
		
		//display about page
		function about()
		{
			$data['main_content'] = 'member_about';
			$this->load->view('includes/member', $data);
		}
		
		//send email to site admin based on member contact form
		function sendemail()
		{
			$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			$this->form_validation->set_rules('contact_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('contact_email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('contact_message', 'Message', 'trim|required');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->about();
			}else{
				//validation works, send email
				$name = $this->input->post('contact_name');
				$email = $this->input->post('contact_email');
				$message = $this->input->post('contact_message');
				
				$this->load->library('email');
				$this->email->set_newline("\r\n");
				$this->email->from($email, $name);
				$this->email->to('YOUR_EMAIL@gmail.com');
				$this->email->subject('DishWhere comment from '.$name);
				$this->email->message($message);
			
				if($this->email->send())
				{
					$this->messages->add('Message Sent!', 'success');
					$this->about();
				}else{
					$this->messages->add('Error, Message Not Sent!', 'message');
					$this->about();
				};
				
			};
			
		}
		
		//update member profile based on profile form submitted by member
		function profile()
		{
			$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			$this->form_validation->set_rules('update_firstname', 'Password', 'trim|required');
			$this->form_validation->set_rules('update_email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('update_oldpass', 'Old Password', 'trim|required');
			$this->form_validation->set_rules('update_newpass', 'New Password', 'trim|required');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->index();
			}else{
				$this->load->model('membership_model');
				$query = $this->membership_model->update();
				
				if($query)
				{
					$this->messages->add('Profile Updated!', 'success');
					$data['main_content'] = 'member_saved';
					$this->load->view('includes/member', $data);
				}else{
					$this->messages->add('Invalid Password, Profile Not Updated!', 'message');
					$data['main_content'] = 'member_saved';
					$this->load->view('includes/member', $data);
				};
			};		
		}
		
		//validate search form and display search results
		function search()
		{	
			$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			$this->form_validation->set_rules('search_dish', 'Dish', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('search_loc', 'Zip', 'trim|min_length[5]');
			if($this->form_validation->run() == FALSE){
				$this->index();
			}else{
				$data['main_content'] = 'member_search';
				$data['dish'] = $this->input->post('search_dish');
				$data['zip'] = $this->input->post('search_loc');
				$this->load->view('includes/member_search_template', $data);	
			}			
		}
		
		//logs member out
		function logout()
		{
			$this->session->sess_destroy();
			redirect('main');
		}
		
		//checks to make sure member is logged in
		function is_logged_in()
		{
			$is_logged_in = $this->session->userdata('is_logged_in');
			
			if(!isset($is_logged_in) || $is_logged_in != true)
			{
				redirect('main');
			}
		}
	}
?>