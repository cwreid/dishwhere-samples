<?php
class Main extends CI_Controller {
	
	//display home page
	function index()
	{
		$this->load->view('includes/index');
	}
	
	//display about page
	function about()
	{
		$data['main_content'] = 'about';
		$this->load->view('includes/template', $data);
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
	
	//validate search form and display search results
	function search()
	{
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('search_dish', 'Dish', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('search_loc', 'Zip', 'trim|min_length[5]');
		if($this->form_validation->run() == FALSE){
			$this->index();
		}else{
			$data['main_content'] = 'search';
			$data['dish'] = $this->input->post('search_dish');
			$data['zip'] = $this->input->post('search_loc');
			$this->load->view('includes/search_template', $data);	
		}	
	}
	
	//check database to see how many members have saved specified menu item
	function favecount()
	{
		$itemid = $this->input->post('itemid');
		$this->load->model('bookmark_model');
		$bookmarkcount = $this->bookmark_model->getbookmarkcount($itemid);
		echo $bookmarkcount;
	}
	
	//check credentials against database users  to allow member access
	function validate_credentials()
	{	
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('login_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('login_pass', 'Password', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}else{
			$this->load->model('membership_model');
			$query = $this->membership_model->validate();
			
			if($query)
			{
				$userid = $query;
				$data = array(
					'email' => $this->input->post('login_email'),
					'is_logged_in' => true,
					'userid' => $userid
				);
				
				$this->session->set_userdata($data);
				redirect('member/saved');
			}else{
				$this->messages->add('Error, Incorrect Login/Password!', 'message');
				$this->index();
			};
		};		
	}
	
	//allow new users to register for member access
	function create_member()
	{
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('reg_firstname', 'Name', 'trim|required');
		$this->form_validation->set_rules('reg_email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('reg_pass', 'Password', 'trim|required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('reg_favedish', 'Favorite Dish', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}else{
			$this->load->model('membership_model');
			$query = $this->membership_model->create_member();
			if($query)
			{
				$this->messages->add('Account Created!', 'success');
				$this->index();
			}else{
				$this->messages->add('Error, Account Not Created!', 'message');
				$this->index();
			};
		};
	}
}

?>