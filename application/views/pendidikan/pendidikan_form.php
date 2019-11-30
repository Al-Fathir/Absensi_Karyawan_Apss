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
										Halaman ini untuk manage data pendidikan. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								<p>
								<label for="id_kelas">Pilih Karyawan</label>
								<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
								<?php echo form_error('nik', '<span class="input-notification error png_bg">', '</span>');?>
							</p>											
								<p>
									<label>Jenjang Pendidikan</label>
										<?php echo form_dropdown('status_pendidikan', $options_pendidikan, isset($default['status_pendidikan']) ? $default['status_pendidikan'] : ''); ?>
								</p>
								
							<p>
									<label>Nama Sekolah</label>
										<input class="text-input small-input" type="text" id="nama_sekolah" name="nama_sekolah" value="<?php echo set_value('nama_sekolah', isset($default['nama_sekolah']) ? $default['nama_sekolah'] : ''); ?>" />
										<?php echo form_error('nama_sekolah', '<span class="input-notification error png_bg">', '</span>');?>
								<br /><small>Masukkan nama sekolah disini. contoh : Universitas Gajah Mada</small>											
								</p>
								
							<p>
									<label>Jurusan</label>
										<input class="text-input small-input" type="text" id="jurusan" name="jurusan" value="<?php echo set_value('jurusan', isset($default['jurusan']) ? $default['jurusan'] : ''); ?>" />
								</p>
								
							<p>
								<label>Tahun Sekolah</label>
								<?php echo form_dropdown('tahun_masuk', $options_thn, isset($default['tahun_masuk']) ? $default['tahun_masuk'] : ''); ?> - <?php echo form_dropdown('tahun_lulus', $options_thn, isset($default['tahun_lulus']) ? $default['tahun_lulus'] : ''); ?>
								<br /><small>Masukkan tahun masuk dan tahun lulus sekolah</small>
								</p>
														
							<p>
								<label>Nomor Ijazah</label>
								<input class="text-input small-input" type="text" id="no_ijazah" name="no_ijazah" value="<?php echo set_value('no_ijazah', isset($default['no_ijazah']) ? $default['no_ijazah'] : ''); ?>" />
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
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}
?>