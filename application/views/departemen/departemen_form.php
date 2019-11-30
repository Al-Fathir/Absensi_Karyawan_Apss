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

<form name="departemen_form" action="<?php echo $form_action; ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<div class="notification information png_bg">
									<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
									<div>
									Halaman ini digunakan untuk manajemen departemen perusahaan. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
									</div>
								</div>
								
										<input class="text-input small-input" type="hidden" id="id_departemen" name="id_departemen" value="<?php echo set_value('id_departemen', isset($default['id_departemen']) ? $default['id_departemen'] : ''); ?>" />
								
								<p>
									<label>Nama Departemen</label>
										<input class="text-input small-input" type="text" id="nama_departemen" name="nama_departemen" value="<?php echo set_value('nama_departemen', isset($default['nama_departemen']) ? $default['nama_departemen'] : ''); ?>" />
										<?php echo form_error('nama_departemen', '<span class="input-notification error png_bg">', '</span>');?>
								</p>
								
							<p>
									<label>Keterangan / Singkatan Departemen</label>
									<textarea class="text-input textarea wysiwyg" id="keterangan" name="keterangan" cols="79" rows="15"><?php echo set_value('keterangan', isset($default['keterangan']) ? $default['keterangan'] : ''); ?></textarea>
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