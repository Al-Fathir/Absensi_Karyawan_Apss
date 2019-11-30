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
					
					<h3 style="cursor: s-resize;">Actions To Do</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" style="display: block;">
					
						<h5>Edit Data</h5>
						<p>
						<?php echo ! empty($edit_link) ? $edit_link : ''; ?> untuk edit data.						
						</p>
						<br />
						<h5>Delete Data</h5>
						<p>
						<?php echo ! empty($delete_link) ? $delete_link : ''; ?> untuk hapus data.						
						</p>
						
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
						
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div>