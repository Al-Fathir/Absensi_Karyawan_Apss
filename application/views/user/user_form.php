<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
				
			<div class="tab-content default-tab">
					
					<?php 
						echo ! empty($message) ? '<p><strong>' . $message . '</strong></p>': '';
					
						$flashmessage = $this->session->flashdata('message');
						echo ! empty($flashmessage) ? '<p><strong>' . $flashmessage . '</strong></p>': '';
					?>
					
						<form name="karyawan_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
									Halaman ini digunakan untuk manajemen user login. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
								</div>
								</div>
										
								<input class="text-input small-input" type="hidden" id="id_user" name="id_user" value="<?php echo set_value('id_user', isset($default['id_user']) ? $default['id_user'] : ''); ?>" />
										
								<p>
									<label>Username</label>
										<input class="text-input small-input" type="text" id="username" name="username" value="<?php echo set_value('username', isset($default['username']) ? $default['username'] : ''); ?>" />
										<?php echo form_error('username', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Password</label>
										<input class="text-input small-input" type="password" id="password" name="password" value="<?php echo set_value('password', isset($default['password']) ? $default['password'] : ''); ?>" />
										<?php echo form_error('password', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Nama Lengkap</label>
										<input class="text-input small-input" type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo set_value('nama_lengkap', isset($default['nama_lengkap']) ? $default['nama_lengkap'] : ''); ?>" />
										<?php echo form_error('nama_lengkap', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Alamat</label>
										<input class="text-input medium-input" type="text" id="alamat" name="alamat" value="<?php echo set_value('alamat', isset($default['alamat']) ? $default['alamat'] : ''); ?>" />
								</p>
								<p>
									<label>Telepon</label>
										<input class="text-input small-input" type="text" id="telepon" name="telepon" value="<?php echo set_value('telepon', isset($default['telepon']) ? $default['telepon'] : ''); ?>" />
								</p>
																
								<p>
									<input class="button" type="reset" value="Reset" />
									<input class="button" type="submit" value="Simpan" />									
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
						
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