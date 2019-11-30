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
										Halaman ini di gunakan untuk manage data karyawan. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								<p>
									<label>Nomor Induk Karyawan ( NIK )</label>
										<input class="text-input small-input" type="text" id="nik" name="nik" value="<?php echo set_value('nik', isset($default['nik']) ? $default['nik'] : ''); ?>" />
										<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
										<br /><small>Isikan Nomor Induk Karyawan disini, asumsi NIK untuk tiap karyawan harus beda</small>										
								</p>
								
								<p>
									<label>Nama Karyawan</label>
									<input class="text-input medium-input" type="text" id="nama" name="nama" value="<?php echo set_value('nama', isset($default['nama']) ? $default['nama'] : ''); ?>" />
									<?php echo form_error('nama', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
								<p>
									<label>Tempat Lahir</label>
										<input class="text-input small-input" type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo set_value('tempat_lahir', isset($default['tempat_lahir']) ? $default['tempat_lahir'] : ''); ?>" />
								</p>
								
								<p>
									<label>Tanggal Lahir</label>
										<input class="text-input small-input" type="text" id="tgl_lahir" name="tgl_lahir" value="<?php echo set_value('tgl_lahir', isset($default['tgl_lahir']) ? $default['tgl_lahir'] : ''); ?>" />
										<br /><small>Format tanggal : YYYY-MM-DD contoh : 1990-03-23</small>
								</p>
								
								<p>
									<label>Jenis Kelamin</label>
									<input name="jenis_kelamin" type="radio" value="L" <?php echo set_radio('jenis_kelamin', 'L', isset($default['jenis_kelamin']) && $default['jenis_kelamin'] == 'L' ? TRUE : FALSE); ?> /> Laki-laki
		<input name="jenis_kelamin" type="radio" value="P" <?php echo set_radio('jenis_kelamin', 'P', isset($default['jenis_kelamin']) && $default['jenis_kelamin'] == 'P' ? TRUE : FALSE); ?> /> Perempuan
								</p>
								
								<p>
									<label>Agama</label>
									<?php echo form_dropdown('agama', $options_agama, isset($default['agama']) ? $default['agama'] : ''); ?>
								</p>
								
								<p>
									<label>Alamat Tinggal</label>
									<input type="text" class="text-input large-input" name="alamat_tinggal" value="<?php echo set_value('alamat_tinggal', isset($default['alamat_tinggal']) ? $default['alamat_tinggal'] : ''); ?>" />									
								</p>
								
								<p>
									<label>Alamat Asal</label>
									<input type="text" class="text-input large-input" name="alamat_asal" value="<?php echo set_value('alamat_asal', isset($default['alamat_asal']) ? $default['alamat_asal'] : ''); ?>" />									
								</p>
								
								<p>
									<label>Telepon</label>
										<input class="text-input small-input" type="text" id="telepon" name="telepon" value="<?php echo set_value('telepon', isset($default['telepon']) ? $default['telepon'] : ''); ?>" />
								</p>
								
								<p>
									<label>Status Perkawinan</label>
									<input name="status_perkawinan" type="radio" value="Belum Menikah" <?php echo set_radio('status_perkawinan', 'Belum Menikah', isset($default['status_perkawinan']) && $default['status_perkawinan'] == 'Belum Menikah' ? TRUE : FALSE); ?> />Belum Menikah
									<input name="status_perkawinan" type="radio" value="Menikah" <?php echo set_radio('status_perkawinan', 'Menikah', isset($default['status_perkawinan']) && $default['status_perkawinan'] == 'Menikah' ? TRUE : FALSE); ?> />Menikah
								</p>
							<p>
									<label>Join Date</label>
									<input type="text" class="text-input small-input" name="tgl_masuk" value="<?php echo set_value('tgl_masuk', isset($default['tgl_masuk']) ? $default['tgl_masuk'] : ''); ?>" />
								<br /><small>Format tanggal : YYYY-MM-DD contoh : 1990-03-23</small>						
								</p>
								
								<p>
									<label>Status karyawan</label>
									<?php echo form_dropdown('status_aktif', $options_statuskaryawan, isset($default['status_aktif']) ? $default['status_aktif'] : ''); ?>
								<br /><small>Pilih non-aktif untuk Ex-karyawan (karyawan yang resign)</small>
								</p>

								<p>
									<label>Upload foto</label>
										<input class="text-input small-input" type="file" id="file_upload" name="userfile" />
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