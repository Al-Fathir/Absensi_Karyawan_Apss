<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<fieldset>
<legend>Pilih Tanggal </legend>	
<form name="rekap_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="id_kelas">Tanggal :</label>
        <?php echo form_dropdown('tgl', $options_tgl, isset($default['tgl']) ? $default['tgl'] : ''); ?> -
		<?php echo form_dropdown('bln', $options_bln, isset($default['bln']) ? $default['bln'] : ''); ?> -
		<?php echo form_dropdown('thn', $options_thn, isset($default['thn']) ? $default['thn'] : ''); ?>
	</p>
	
	<p>
		<input type="submit" name="submit" id="submit" value=" O K " />
	</p>
</form>
</fieldset>
<?php echo ! empty($active_class) ? 'Kelas : ' . $active_class . '<br />' : ''; ?>
<?php echo ! empty($semester) ? 'Semester : ' . $semester : ''; ?>
<?php echo ! empty($table) ? $table : ''; ?>
	
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