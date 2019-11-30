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
										Halaman ini untuk manage data keluarga. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								<p>
									<label>Pilih Karyawan</label>
									<?php echo form_dropdown('nik', $options_karyawan, isset($default['nik']) ? $default['nik'] : ''); ?>
								</p>
								
								<p>
									<label>Nama Keluarga</label>
									<input class="text-input small-input" type="text" id="nama" name="nama" value="<?php echo set_value('nama', isset($default['nama']) ? $default['nama'] : ''); ?>" />
									<?php echo form_error('nama', '<span class="input-notification error png_bg">', '</span>');?>
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
									<label>Tempat Lahir</label>
									<input class="text-input small-input" type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo set_value('tempat_lahir', isset($default['tempat_lahir']) ? $default['tempat_lahir'] : ''); ?>" />
								</p>
								
								<p>
									<label>Tanggal Lahir</label>
									<input class="text-input small-input" type="text" id="tgl_lahir" name="tgl_lahir" value="<?php echo set_value('tgl_lahir', isset($default['tgl_lahir']) ? $default['tgl_lahir'] : ''); ?>" />
									<br /><small>Format tanggal : YYYY-MM-DD contoh : 1990-03-23</small>
								</p>
								
								<p>
									<label>Status Hubungan Keluarga</label>
									<input class="text-input small-input" type="text" id="status" name="status" value="<?php echo set_value('status', isset($default['status']) ? $default['status'] : ''); ?>" />
									<?php echo form_error('status', '<span class="input-notification error png_bg">', '</span>');?>
									<br /><small>Contoh pengisian : Istri, Anak 1, Anak 2, dst.</small>
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