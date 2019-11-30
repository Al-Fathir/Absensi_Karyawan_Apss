<ul id="menu_tab">	
	<li id="tab_user"><?php echo anchor('user', 'User');?></li>
	<li id="tab_user"><?php echo anchor('departemen', 'Departemen');?></li>
	<li id="tab_absen"><?php echo anchor('karyawan', 'Karyawan');?></li>
	<li id="tab_user"><?php echo anchor('pendidikan', 'Pendidikan');?></li>
	<li id="tab_user"><?php echo anchor('keluarga', 'Keluarga');?></li>
	<li id="tab_user"><?php echo anchor('jabatan', 'Jabatan');?></li>
	<li id="tab_user"><?php echo anchor('tunjangan', 'Tunjangan');?></li>
	<li id="tab_rekap"><?php echo anchor('kehadiran', 'Kehadiran');?></li>
	<li id="tab_siswa"><?php echo anchor('perbulan', 'Rekap Per Bulan');?></li>
	<li id="tab_semester"><?php echo anchor('perindividu', 'Rekap Individu');?></li>
	<li id="tab_kelas"><?php echo anchor('statistik', 'Statistik');?></li>
	<li id="tab_logout"><?php echo anchor('login/process_logout', 'Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?></li>
</ul>