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
					
						<form name="rekap_form" method="post" action="<?php echo $form_action; ?>">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification attention png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
										Halaman ini digunakan untuk rekap per individu, silahkan pilih tanggal presensi. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								
								<p>
									<label for="id_kelas">Rekap Presensi Per Individu Bulan :</label>
									<?php echo form_dropdown('bln', $options_bln, isset($default['bln']) ? $default['bln'] : ''); ?> -
									<?php echo form_dropdown('thn', $options_thn, isset($default['thn']) ? $default['thn'] : ''); ?>
								</p>
								
								<p>
									<label for="id_kelas">Tampilkan :</label>
									<?php echo form_dropdown('karyawan', $options_karyawan, isset($default['karyawan']) ? $default['karyawan'] : ''); ?>
								</p>
																
								<p>
									<input class="button" type="submit" value="Show" />									
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
								
								<?php echo ! empty($table) ? $table : ''; ?>
								
								<br />
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