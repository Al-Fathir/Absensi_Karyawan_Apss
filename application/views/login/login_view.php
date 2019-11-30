<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'images/stikom.ico';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/invalid.css'; ?>");</style>

<title>Login| Airlangga Group</title>
</head>

<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<!-- Logo (222px width) -->
				<img id="logo" width="320" src="<?php echo base_url(); ?>/asset/images/" alt="Simpla Admin logo" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<?php
					$attributes = array('name' => 'login_form', 'id' => 'login_form');
					echo form_open('login/process_login', $attributes);
				?>
				
					
					<?php echo form_error('username', '<div class="notification information png_bg"><div>', '</div></div>');?>
					<?php echo form_error('password', '<div class="notification information png_bg"><div>', '</div></div>');?>
					<?php 
						$message = $this->session->flashdata('message');
						echo $message == '' ? '' : '<div class="notification information png_bg"><div>' . $message . '</div></div>';
					?>
					
					<p>
						<label>Username</label>
						<input class="text-input" type="text" name="username" value="<?php echo set_value('username');?>" />
					</p>
					<div class="clear"></div>
					<p>
						<label>Password</label>
						<input class="text-input" type="password" name="password" value="<?php echo set_value('password');?>" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" />Remember me
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" name="submit" id="submit" value="Sign In" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>