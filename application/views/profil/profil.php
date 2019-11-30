<br />
<div class="notification information png_bg">
	<a class="close" href="#"><img alt="close" title="Close this notification" src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png"></a>
	<div>
		Halaman ini merupakan informasi user login anda. Untuk menutup notifikasi ini klik tanda silang pada pojok kanan atas.
	</div>
</div>
<br /><br /><br />
<div class="content-box column-left"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
			   
					<div class="tab-content default-tab"> <!-- This is the target div. id must match the href of this div's tab -->
					
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
											
							<div class="clear"></div>
						</div>
					</div>    
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			

<div class="content-box column-right"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3 style="cursor: s-resize;">Informasi</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" style="display: block;">
					
						<p>
						Gunakan minimal 8 karakter password namun jangan lebih dari 10 karakter untuk memudahkan anda mengingatnya.<br /><br />
						Sebaiknya karakter yang digunakan merupakan kombinasi huruf, angka dan atau simbol lainnya.<br /><br />
						Ganti Password secara Periodik dan jangan pernah memberitahukan password anda kepada orang lain.						
						</p>
												
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div>