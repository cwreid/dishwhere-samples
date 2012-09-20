<?php
	$this->load->view('includes/member/header');
?>	
	<div id="results" class="<?php echo $dish ?>">
	<div id="location" class="<?php echo $zip ?>"></div>
<?php	
	$this->load->view($main_content);
	$this->load->view('includes/member/footer');	
?>