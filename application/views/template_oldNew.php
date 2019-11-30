<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css" type="text/css" media="all" />
<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
	//BrowserDetect.isAllowed();
	function popUp(URL) {
		newwindow=window.open(URL,'name','toolbar=0,scrollbars=yes,location=0,statusbar=0,menubar=0,resizable=0,width=730,height=630,left=250,top=0');
		if (window.focus) {newwindow.focus()}
		}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'images/favicon.gif';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'asset/css/invalid.css'; ?>");</style>

		<!-- jQuery -->
		<!-- <script type="text/javascript" src="<?php echo base_url() . 'asset/scripts/jquery-1.3.2.min.js'; ?>"></script> -->
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="<?php echo base_url() . 'asset/scripts/simpla.jquery.configuration.js'; ?>"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="<?php echo base_url() . 'asset/scripts/facebox.js'; ?>"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="<?php echo base_url() . 'asset/scripts/jquery.wysiwyg.js'; ?>"></script>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php $this->load->view('sidebar'); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
					</div>
				</div>
			</noscript>
			
			<!-- Page Head -->
			<h2>Welcome <?php echo ucfirst($this->session->userdata('username')); ?></h2>
			<p></p>
			<div class="clear"></div> <!-- End .clear -->
			
			<?php $this->load->view($main_view); ?>
			
			<div class="clear"></div>
			
			<?php $this->load->view('footer'); ?>
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>