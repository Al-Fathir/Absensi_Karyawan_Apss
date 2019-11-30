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
<form name="jabatan_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini untuk manage data jabatan. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								<p>
										<label>Pilih Karyawan</label>
       							 		<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
										<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
								<p>
								<label>Pilih Jabatan</label>
       							 		<?php echo form_dropdown('id_jabatan', $options_jabatan, isset($default['id_jabatan']) ? $default['id_jabatan'] : ''); ?>
								</p>
								
								<p>
									<label>Golongan</label>
									<input class="text-input small-input" type="text" id="golongan" name="golongan" value="<?php echo set_value('golongan', isset($default['golongan']) ? $default['golongan'] : ''); ?>" />
								</p>

								<p>
									<label>Masa Kerja</label>
									<input class="text-input small-input" type="text" id="masa_kerja" name="masa_kerja" value="<?php echo set_value('masa_kerja', isset($default['masa_kerja']) ? $default['masa_kerja'] : ''); ?>" />
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