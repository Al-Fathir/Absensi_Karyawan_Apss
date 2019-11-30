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
<form name="pengalamankerja_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini untuk manage data pengalaman kerja. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>

							<p>
								<label>Pilih Karyawan</label>
       							 		<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
										<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
								<p>
								<label>Nama Perusahaan </label>
								<input class="text-input small-input" type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?php echo set_value('nama_perusahaan', isset($default['nama_perusahaan']) ? $default['nama_perusahaan'] : ''); ?>" />
								<?php echo form_error('nama_perusahaan', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
								<p>
								<label>Alamat perusahaan </label>
								<input class="text-input medium-input" type="text" id="alamat_perusahaan" name="alamat_perusahaan" value="<?php echo set_value('alamat_perusahaan', isset($default['alamat_perusahaan']) ? $default['alamat_perusahaan'] : ''); ?>" />
								</p>
																
								<p>
								<label>Tahun Bekerja</label>
								<?php echo form_dropdown('tahun_mulai_kerja', $options_thn, isset($default['tahun_mulai_kerja']) ? $default['tahun_mulai_kerja'] : ''); ?> - <?php echo form_dropdown('tahun_selesai_kerja', $options_thn, isset($default['tahun_selesai_kerja']) ? $default['tahun_selesai_kerja'] : ''); ?>
								<br /><small>Masukkan tahun mulai dan tahun selesai bekerja</small>
								</p>
								
								<p>
								<label>Jabatan </label>
								<input class="text-input small-input" type="text" id="jabatan" name="jabatan" value="<?php echo set_value('jabatan', isset($default['jabatan']) ? $default['jabatan'] : ''); ?>" />
								<?php echo form_error('jabatan', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
									<p>
									<label>Alasan Berhenti</label>
									<textarea class="text-input textarea wysiwyg" id="alasan_berhenti" name="alasan_berhenti" cols="79" rows="15"><?php echo set_value('alasan_berhenti', isset($default['alasan_berhenti']) ? $default['alasan_berhenti'] : ''); ?></textarea>
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