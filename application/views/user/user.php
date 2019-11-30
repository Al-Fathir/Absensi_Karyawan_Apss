<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
			   
					<div class="tab-content default-tab"> <!-- This is the target div. id must match the href of this div's tab -->
					
						<?php 
						$flashmessage = $this->session->flashdata('message');
						echo ! empty($flashmessage) ? '<div class="notification success png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $flashmessage . '</div></div>': ''; 
						?>
						
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Halaman ini digunakan untuk manajemen user login. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
							</div>
						</div>
						
						<?php echo ! empty($message) ? '<div class="notification information png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $message . '</div></div>' : ''; ?>
						
						<?php echo ! empty($table) ? $table : ''; ?>
						
						<br />
						<div class="tabelfoot">
										<div class="bulk-actions align-left">
											<?php
											if ( ! empty($link))
											{
												foreach($link as $links)
												{
													echo $links . ' ';
												}
											}
											?>
										</div>
										
										<?php echo ! empty($pagination) ? '<div class="pagination">' . $pagination . '</div>' : ''; ?>
										<div class="clear"></div>
						</div>
					</div>    
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->