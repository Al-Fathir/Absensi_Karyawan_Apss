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
<form name="hubungankerja_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini untuk manage hubungan kerja. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								<p>
										<label>Pilih Karyawan</label>
       							 		<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
										<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
								</p>										
								<p>
								<label for="tanggal_masuk">Tanggal Awal</label>
								<input class="text-input small-input" type="text" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo 	set_value('tanggal_masuk', isset($default['tanggal_masuk']) ? $default['tanggal_masuk'] : ''); ?>" />
								<?php echo form_error('tanggal_masuk', '<span class="input-notification error png_bg">', '</span>');?>
								<br /><small>Format tanggal : YYYY-MM-DD contoh : 1990-03-23</small>
							</p>
							<p>
								<label for="tanggal_keluar">Tanggal Akhir</label>
								<input class="text-input small-input" type="text" id="tanggal_keluar" name="tanggal_keluar" value="<?php echo 	set_value('tanggal_keluar', isset($default['tanggal_keluar']) ? $default['tanggal_keluar'] : ''); ?>" />
								<?php echo form_error('tanggal_keluar', '<span class="input-notification error png_bg">', '</span>');?>
								<br /><small>Format tanggal : YYYY-MM-DD contoh : 1990-03-23</small>
							</p>
							<p>
									<label>Status Hubungan Kerja</label>
									<input class="text-input small-input" type="text" id="statuspekerjaan" name="statuspekerjaan" value="<?php echo 	set_value('statuspekerjaan', isset($default['statuspekerjaan']) ? $default['statuspekerjaan'] : ''); ?>" />
								<?php echo form_error('statuspekerjaan', '<span class="input-notification error png_bg">', '</span>');?>
								<br /><small>Contoh pengisian : Training, Kontrak 1, Kontrak 2, dst.</small>
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