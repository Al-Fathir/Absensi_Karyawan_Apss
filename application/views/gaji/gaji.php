<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
			   
					<div class="tab-content default-tab"> <!-- This is the target div. id must match the href of this div's tab -->
					
					
						<div class="single-info">				
						<?php
							if ( ! empty($download))
							{
								foreach($download as $downloadlinks)
								{
									echo $downloadlinks . ' ';
								}
							}
						?>
						</div>
					
						<?php 
						$flashmessage = $this->session->flashdata('message');
						echo ! empty($flashmessage) ? '<div class="notification success png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $flashmessage . '</div></div>': ''; 
						?>
												
						<?php echo ! empty($message) ? '<div class="notification information png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $message . '</div></div>' : ''; ?>
						
						
						<form name="form1" method="post" action="">
						<p>
							<label>Pencarian data berdasarkan :</label>
							<select name="field" id="field">
								<option value="gaji.nik">NIK</option>
								<option value="karyawan.nama_karyawan">Nama Karyawan</option>
							</select>
							<input type="text" value="" name="kunci" id="kunci" class="text-input small-input">
							<?php echo form_dropdown('departemen', $options_departemen, isset($default['departemen']) ? $default['departemen'] : ''); ?>
							<input type="submit" name="cari" id="cari" value="search" class="button">
							<input type="submit" name="cari" id="cari" value="refresh" class="button">
							<br><small>Pilih field pencarian data lalu masukkan keyword pencarian anda disini</small>										
						</p>
						</form>
						<?php echo ! empty($table) ? $table : ''; ?>
						
						<br />
						<div class="tabelfoot">
										<div class="bulk-actions align-left">
											<?php
											if ( ! empty($link))
											{
												foreach($link as $links)
												{
													echo $links . ' ';
												}
											}
											?>
										</div>
										
										<?php echo ! empty($pagination) ? '<div class="pagination">' . $pagination . '</div>' : ''; ?>
										<div class="clear"></div>
						</div>
					</div>    
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->