<!DOCTYPE html>
<?php
	session_start();
	include('../crud/crudKategori.php');
	include('../crud/crudLowongan.php');
	include('../fungsiTanggal.php');
	include('../session.php');
	$now  = date('Y-m-d');
	$id_kategori = $_GET['id_kategori'];
	$sql      = "select perusahaan.*, kota.*, jurusan_pendidikan.*, kategori.*, lowongan.* from lowongan 
				  inner join perusahaan on perusahaan.id_perusahaan = lowongan.id_perusahaan
				  inner join kota on kota.id_kota = lowongan.id_kota
				  inner join jurusan_pendidikan on jurusan_pendidikan.id_jurusan = lowongan.id_jurusan
				  inner join kategori on  kategori.id_kategori = lowongan.id_kategori
				  where status='publik' and kategori.id_kategori = '$id_kategori' order by id_lowongan desc";
	$data     = bacaLowongan($sql);
	
	$email = $_SESSION['email'];
	$id    = "select * from perusahaan where email ='$email'";
	$data2 = bacaNama($id);
	$sql2     = "select * from kategori";
	$sql3    = "select * from kategori where id_kategori = '$id_kategori'";
	$kategori = bacaKategori($sql2);
	$kategori2 = bacaKategori($sql3);
?>

<html>
<head>
	<title>Job Seeker</title>
	<link rel="stylesheet" href="../stylecss/styleheader.css" type="text/css">
	<link rel="stylesheet" href="../stylecss/stylebutton.css" type="text/css">
</head>
<body>
<div id="header">
		<div>
			<div id="logo">
				<a href="../beranda_perusahaan.php"><img src="../images/logo.png" alt="LOGO"></a>
			</div>
			<ul id="navigation">
				<li class="selected">
					<a href="../beranda_perusahaan.php">Beranda</a>
				</li>
				<li>
					<a href="../data_tampil/profile_perusahaan.php">Profil</a>
				</li>
				<li>
					<a href="../data_tampil/lowongan_perusahaan.php">Lowongan</a>
				</li>
				<li>
					<a href="../login/logout_perusahaan.php">Logout</a>
				</li>
			</ul>
		</div>
	</div>
	
	<?php
		foreach($data2 as $baris){
			$nama_perusahaan  = $baris['nama_perusahaan'];
		}
	?>
	
	<?php 
		foreach($kategori2 as $baris2){
			$nama_kat   = $baris2['nama_kategori'];
		}
	?>
	
	<div align='center'>
	<?php
		echo "<br/> <fieldset>
				<h3> -- Selamat Datang ".$nama_perusahaan." --</h3>
			  </fieldset>";
	?>
	</div>
	
<table>	
<tr valign='top'>
	<td width='70%'>
	<table>
		<tr>
			<td width='1100px'>
				<h2>Lowongan Bidang <?php echo $nama_kat; ?></h2>
				<fieldset><center>
					Halaman 1, terdapat 3 Lowongan dari 8 Data Lowongan
				</fieldset></center>
			</td>
		</tr>
		<?php
		if($data == null){
			echo "
				<tr>
					<td width='1100px' align='center'>
					<fieldset><i> - Maaf data tidak ditemukan - </i></fieldset>
					</td>
				</tr>
			";
		}
		if($data != null)
		foreach($data as $baris){
			$id_perusahaan   = $baris['id_perusahaan'];
			$nama_perusahaan = $baris['nama_perusahaan'];
			$pekerjaan       = $baris['pekerjaan'];
			$tawaran_gaji    = $baris['tawaran_gaji'];
			$kota 			 = $baris['nama_kota'];
			$batas           = $baris['batas_lamaran'];
			$tanggal = tanggalIndo($batas);
			$tgl_batas = strtotime($batas);
			$tgl_now   = strtotime($now);
			
			if($tgl_batas >= $tgl_now){
			echo "
			<tr>
				<td width='1100px'> <fieldset> <pre>
	<b>$nama_perusahaan</b><br/>
	Pekerjaan    : $pekerjaan <br/> 
	Tawaran Gaji : $tawaran_gaji <br/>
	Lokasi Kerja : $kota <br/>
	Batas Lamar  : $tanggal <br/>
	<a href='../data_tampil/detail_lowongan_perusahaan.php?id_lowongan={$baris['id_lowongan']}'><img src='../images/detail.jpg'></a>
				</fieldset> </pre> </td> 
			";	}
		}
		?>
	</table>

	</td>
	<td valign='top'> <br/> 
		<form action='pencarian_kota2.php' method='post'>
			<input type='text' name='cari' size='30' placeholder='Cari Perusahaan atau Lokasi' /> 
			&nbsp;&nbsp;<input type='submit' name='cariKota' value='Cari'>
		</form>
		<h3>Berdasarkan Kategori</h3>
			<?php
				foreach($kategori as $baris2){
				$id_kategori   = $baris2['id_kategori'];
				$nama_kategori = $baris2['nama_kategori'];				
			?>
			<ul>
			<?php
				echo "
				<li>
					<a href='pencarian_kategori2.php?id_kategori={$baris2['id_kategori']}'>$nama_kategori</a>
				</li>";
			?>
			</ul> 
			<?php } ?>
	</td></tr>
</tr>
</table>
</body>
</html>
