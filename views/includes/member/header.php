<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width">
	<title>DishWhere - Your Favorite Dish, Just Around the Corner</title>
	<!--[if lt IE 9]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  	<![endif]-->
  	<link href='http://fonts.googleapis.com/css?family=Petrona|PT+Sans' rel='stylesheet' type='text/css'>
	<!-- 	<link rel="stylesheet" type="text/css" media="screen and (max-device-width: 480px)" href="retina.css" /> -->
	<link rel="stylesheet" type="text/css" media="screen and (min-width: 480px)" href="<?=base_url()?>css/main.css" />
	<!-- favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/favicon.ico">
	<!-- JS Modernizr for HTML5 functionality-->
	<script src="<?=base_url()?>js/libs/modernizr.custom.js"></script>
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/libs/jquery.validate.min.js"></script>
	<!-- Google Analytics -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-32039626-1']);
		_gaq.push(['_setDomainName', 'dishwhere.com']);
		_gaq.push(['_trackPageview']);
		
		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>

<body>
	<header>
		<nav id="topNav">
			<ul>
				<li><?=anchor('member/about', 'About');?></li>
				<li><?=anchor('member/logout', 'Logout');?></li>
				<li><a href="#">Profile</a> 
				<?php
						echo form_open('member/profile');
						$data = array(
			            	'name'        => 'update_firstname',
			            	'placeholder' => 'First Name',
			            );
						echo form_input($data);
						echo form_error('update_firstname');
						$data = array(
			            	'name'        => 'update_email',
			            	'placeholder' => 'Email',
			            );
			            echo form_input($data);
			            echo form_error('update_email');
			            $data = array(
			            	'name'        => 'update_oldpass',
			            	'placeholder' => 'Old Password',
			            );
			            echo form_password($data);
			            echo form_error('update_oldpass');
			            $data = array(
			            	'name'        => 'update_newpass',
			            	'placeholder' => 'New Password',
			            );
			            echo form_password($data);
			            echo form_error('update_newpass');
			            echo form_submit('update_submit', 'Submit');
			            echo form_close();
					?></li>
				<li><?=anchor('member/saved', 'Saved');?></li>
			</ul>
		</nav>
		<?= $this->messages->output_messages();  ?>
	</header>
	<div id="contentwrapper">
		<div id="content">
			<div id="search">
				<a href="<?php echo site_url('member/saved') ?>">
					<div class="brandingsmall">
						<h1>DishWhere</h1>
					</div><!-- close #brandingsmall div -->
				</a>
				
				<?php
					echo form_open('member/search');
					$data = array(
			            	'name'        => 'search_dish',
			            	'placeholder' => 'What Do You Wish You Were Eating Right Now?',
			        );
			        echo form_input($data);
			        echo form_error('search_dish');
			        echo "<br />";
			        $data = array(
			            	'id'		  => 'zip',
			            	'name'        => 'search_loc',
			            	'placeholder' => 'Zip Code',
			        );
			        echo form_input($data);
			        echo form_error('search_loc');
			        echo "<br />";
			        echo form_submit('search_submit', 'Search');
			        echo form_close();
				?>
				
				
				<a href="http://openmenu.org/">Powered By OpenMenu</a>
			</div><!-- close #search div -->	