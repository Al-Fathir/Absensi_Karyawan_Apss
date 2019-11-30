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
					
						<form name="tunjangan_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini untuk manage data gaji. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								
								<p>
									<label for="id_kelas">Pilih Karyawan:</label>
									<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
									<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Gaji Pokok</label>
										<input class="text-input small-input" type="text" id="gajipokok" name="gajipokok" value="<?php echo set_value('gajipokok', isset($default['gajipokok']) ? $default['gajipokok'] : ''); ?>" />
									<?php echo form_error('gajipokok', '<span class="input-notification error png_bg">', '</span>');?>
								</p>								
								<p>
									<label>Tunjangan Tetap</label>
										<input class="text-input small-input" type="text" id="tunjangan_tetap" name="tunjangan_tetap" value="<?php echo set_value('tunjangan_tetap', isset($default['tunjangan_tetap']) ? $default['tunjangan_tetap'] : ''); ?>" />
										<?php echo form_error('tunjangan_tetap', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Tunjangan Tidak Tetap</label>
										<input class="text-input small-input" type="text" id="tunjangan_tidak_tetap" name="tunjangan_tidak_tetap" value="<?php echo set_value('tunjangan_tidak_tetap', isset($default['tunjangan_tidak_tetap']) ? $default['tunjangan_tidak_tetap'] : ''); ?>" />
										<?php echo form_error('tunjangan_tidak_tetap', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								<p>
									<label>Tunjangan Lainnya</label>
										<input class="text-input small-input" type="text" id="tunjangan_lainnya" name="tunjangan_lainnya" value="<?php echo set_value('tunjangan_lainnya', isset($default['tunjangan_lainnya']) ? $default['tunjangan_lainnya'] : ''); ?>" />
										<?php echo form_error('tunjangan_lainnya', '<span class="input-notification error png_bg">', '</span>');?>
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