<?php $uri = $this->uri->segment(1); ?>
<ul id="main-nav">  <!-- Accordion Menu -->
				
				<li>
					<?php echo $uri=="home" || $uri=="profil" || $uri=="handbook" ? anchor('', 'Home', array('class' => 'nav-top-item current')) : anchor('', 'Home', array('class' => 'nav-top-item')); ?>       
					<ul>
						<li><?php echo $uri=="home" ? anchor('home', 'Dashboard Admin', array('class' => 'current')) : anchor('home', 'Dashboard Admin'); ?></li>
						<li><?php echo $uri=="profil" ? anchor('profil', 'Your Profile', array('class' => 'current')) : anchor('profil', 'Your Profile'); ?></li>
						<li><?php echo $uri=="handbook" ? anchor('handbook', 'Download Manual Book', array('class' => 'current')) : anchor('handbook', 'Download Manual Book'); ?></li>
					</ul>
				</li>
				
				<li> 
					<?php echo $uri=="karyawan" || $uri=="hubungankerja" || $uri=="keluarga" || $uri=="pendidikan" || $uri=="pengalamankerja" || $uri=="jabatan" || $uri=="gaji" ? anchor('', 'Kepegawaian', array('class' => 'nav-top-item current')) : anchor('', 'Kepegawaian', array('class' => 'nav-top-item')); ?>
					
					<ul>
						<li><?php echo $uri=="karyawan" ? anchor('karyawan', 'Manage Karyawan 	', array('class' => 'current')) : anchor('karyawan', 'Manage Karyawan'); ?></li>
						<li><?php echo $uri=="hubungankerja" ? anchor('hubungankerja', 'Manage Hubungan Kerja', array('class' => 'current')) : anchor('hubungankerja', 'Manage Hubungan Kerja'); ?></li>
						<li><?php echo $uri=="keluarga" ? anchor('keluarga', 'Manage Keluarga', array('class' => 'current')) : anchor('keluarga', 'Manage Keluarga'); ?></li>
						<li><?php echo $uri=="pendidikan" ? anchor('pendidikan', 'Manage Pendidikan', array('class' => 'current')) : anchor('pendidikan', 'Manage Pendidikan'); ?></li>
						<li><?php echo $uri=="pengalamankerja" ? anchor('pengalamankerja', 'Manage Pengalaman Kerja', array('class' => 'current')) : anchor('pengalamankerja', 'Manage Pengalaman Kerja'); ?></li>
						<li><?php echo $uri=="jabatan" ? anchor('jabatan', 'Manage Jabatan Karyawan', array('class' => 'current')) : anchor('jabatan', 'Manage Jabatan Karyawan'); ?></li>
						<li><?php echo $uri=="gaji" ? anchor('gaji', 'Manage Gaji Karyawan', array('class' => 'current')) : anchor('gaji', 'Manage Gaji Karyawan'); ?></li>
					</ul>
				</li>
				
				<li>
					<?php echo $uri=="uploadpresensi" || $uri=="upload" || $uri=="kehadiran" || $uri=="perbulan" || $uri=="perindividu" ? anchor('', 'Presensi', array('class' => 'nav-top-item current')) : anchor('', 'Presensi', array('class' => 'nav-top-item')); ?>
					
					<ul>
						<li><?php echo $uri=="uploadpresensi" ? anchor('uploadpresensi', 'Upload Presensi', array('class' => 'current')) : anchor('uploadpresensi', 'Upload Presensi'); ?></li>
						<li><?php echo $uri=="upload" ? anchor('upload', 'Upload Log Presensi', array('class' => 'current')) : anchor('upload', 'Upload Log Presensi'); ?></li>
						<li><?php echo $uri=="kehadiran" ? anchor('kehadiran', 'Rekap Kehadiran', array('class' => 'current')) : anchor('kehadiran', 'Rekap Kehadiran'); ?></li>
						<li><?php echo $uri=="perbulan" ? anchor('perbulan', 'Rekap Per Bulan', array('class' => 'current')) : anchor('perbulan', 'Rekap Per Bulan'); ?></li>
						<li><?php echo $uri=="perindividu" ? anchor('perindividu', 'Rekap Per Individu', array('class' => 'current')) : anchor('perindividu', 'Rekap Per Individu'); ?></li>
						<li><?php echo $uri=="karyawan_terbaik" ? anchor('karyawan_terbaik', 'Rekap Karyawan Terbaik', array('class' => 'current')) : anchor('karyawan_terbaik', 'Rekap Karyawan Terbaik'); ?></li>
						<!--<li><a href="#">Statistik</a></li>-->
					</ul>
				</li>
				
				<li>
					<?php echo $uri=="lapkaryawan" || $uri=="lappresensi" ? anchor('', 'Laporan', array('class' => 'nav-top-item current')) : anchor('', 'Laporan', array('class' => 'nav-top-item')); ?>
					
					<ul>
						<li><?php echo $uri=="lapkaryawan" ? anchor('lapkaryawan', 'Laporan Kepegawaian', array('class' => 'current')) : anchor('lapkaryawan', 'Laporan Kepegawaian'); ?></li>
						<!--<li><a href="#">Laporan Presensi</a></li>-->
					</ul>
				</li>
								
				<li>
					<?php echo $uri=="user" || $uri=="departemen" || $uri=="departemen_jabatan" ? anchor('', 'Setting', array('class' => 'nav-top-item current')) : anchor('', 'Setting', array('class' => 'nav-top-item'));   ?>
					<ul>
						<li><?php echo $uri=="user" ? anchor('user', 'Manage User', array('class' => 'current')) : anchor('user', 'Manage User'); ?></li>
						<li><?php echo $uri=="departemen" ? anchor('departemen', 'Manage Departemen', array('class' => 'current')) : anchor('departemen', 'Manage Departemen'); ?></li>
						<li><?php echo $uri=="departemen_jabatan" ? anchor('departemen_jabatan', 'Manage Jabatan', array('class' => 'current')) : anchor('departemen_jabatan', 'Manage Jabatan'); ?></li>
					</ul>
				</li>      
				
			</ul> <!-- End #main-nav -->