<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
				
			<div class="tab-content default-tab">
					
					<?php 
						$flashmessage = $this->session->flashdata('message');
						echo ! empty($flashmessage) ? '<div class="notification success png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $flashmessage . '</div></div>': ''; 
					?>
					
						<?php echo form_open_multipart('upload/do_upload');?>
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification attention png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini digunakan untuk upload data log presensi dengan format file excel. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								
								<p>
									<label>Upload Log Presensi</label>
										<input class="text-input small-input" type="file" id="file_upload" name="userfile" />
								</p>
																
								<p>
									<input class="button" type="submit" value="Upload" />									
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						<?php echo form_close();?>
						
								<?php
									if ( ! empty($link))
									{
										foreach($link as $links)
										{
											echo $links . ' ';
										}
									}
								?>

						
					</div> <!-- End .tab-content -->

				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->