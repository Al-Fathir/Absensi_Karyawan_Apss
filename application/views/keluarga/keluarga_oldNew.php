<script>
	
	function editKeluarga(idnya){
		$.ajax({
		url: 'keluarga/readBook',
			data: "idnya="+idnya,
			dataType:"json",
			success:function(data){
				teman = "<ol>";
				$.each(data, function(i,n){
					teman = teman + "<li>" + n["nama"] + " hub. keluarga : " + n["status"] + "</li>";
				});
				teman = teman + "</ol>";
				$("#form_input").append(teman);
			}
    });
		$( "#form_input" ).dialog({
		
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			show: 'drop',
			hide: 'drop',
			buttons: {				
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				$("#form_input").dialog("destroy");
			}
		});
		$('#form_input').dialog('open');
	}
	
	$(function() {
	
		$( "#form_input" ).dialog({
		
			autoOpen: false,
			height: 300,
			width: 350,
			modal: false,
			show: 'drop',
			hide: 'drop',
			buttons: {				
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
		});

	});
	
	</script>
	
<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3><?php echo ! empty($h2_title) ? $h2_title : ''; ?></h3>
										
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
			   
					<div class="tab-content default-tab"> <!-- This is the target div. id must match the href of this div's tab -->
					
						<?php 
						$flashmessage = $this->session->flashdata('message');
						echo ! empty($flashmessage) ? '<div class="notification success png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $flashmessage . '</div></div>': ''; 
						?>
						
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="<?php echo base_url(); ?>/asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.
							</div>
						</div>
						
		<!--<p>
    	<a onclick="editKeluarga(1202)" href="javascript:void(0)">1202</a>
		<a onclick="editKeluarga(1203)" href="javascript:void(0)">1203</a>
		</p>-->

	<div id="form_input" title="View Detail Data Keluarga">
      <table>
        <tr >
            <td><strong>Data Keluarga Karyawan</strong></td>
            <td>&nbsp;</td>
        </tr>
		 <tr >
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
      </table>
    </div>
						
						<?php echo ! empty($message) ? '<div class="notification information png_bg"><a class="close" href="#"><img alt="close" title="Close this notification" src="'.base_url().'/asset/images/icons/cross_grey_small.png"></a><div>' . $message . '</div></div>' : ''; ?>
						
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