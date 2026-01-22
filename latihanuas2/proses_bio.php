<?php
session_start();
require __DIR__ . './koneksi.php';
require_once __DIR__ . '/fungsi.php';

#cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
  redirect_ke('index.php#biodata');
}

#ambil dan bersihkan nilai dari form

$nim  = bersihkan($_POST['txtNim']  ?? '');
$namalengkap = bersihkan($_POST['txtNmLengkap'] ?? '');
$tempat = bersihkan($_POST['txtT4Lhr'] ?? '');
$tanggal = bersihkan($_POST['txtTglLhr'] ?? '');
$hobi = bersihkan($_POST['txtHobi'] ?? '');
$pasangan = bersihkan($_POST['txtPasangan'] ?? '');
$pekerjaan = bersihkan($_POST['txtKerja'] ?? '');
$ortu = bersihkan($_POST['txtNmOrtu'] ?? '');
$kakak = bersihkan($_POST['txtNmKakak'] ?? '');
$adik = bersihkan($_POST['txtNmAdik'] ?? '');

#Validasi sederhana
$errors_bio = []; #ini array untuk menampung semua error yang ada

if ($nim === '') {
  $errors_bio[] = 'Nim wajib diisi.';
}

if ($namalengkap === '') {
  $errors_bio[] = 'Nama wajib diisi.';
}

if ($tempat === '') {
  $errors_bio[] = 'Tempat lahir wajib diisi.';
}

if ($tanggal === '') {
  $errors_bio[] = 'Tanggal lahir wajib diisi.';
}

if ($hobi === '') {
  $errors_bio[] = 'Hobi wajib diisi.';
}

if ($pasangan === '') {
  $errors_bio[] = 'Nama pasangan wajib diisi.';
}

if ($pekerjaan === '') {
  $errors_bio[] = 'Pekerjaan wajib diisi.';
}

if ($ortu === '') {
  $errors_bio[] = 'Nama orang tua wajib diisi.';
}

if ($kakak === '') {
  $errors_bio[] = 'Nama kakak wajib diisi.';
}

if ($adik === '') {
  $errors_bio[] = 'Nama adik wajib diisi.';
}

if (mb_strlen($namalengkap) < 3) {
  $errors_bio[] = 'Nama minimal 3 karakter.';
}

/*
kondisi di bawah ini hanya dikerjakan jika ada error, 
simpan nilai lama dan pesan error, lalu redirect (konsep PRG)
*/
if (!empty($errors_bio)) {
  $_SESSION['old_bio'] = [
    'nim'  => $nim,
    'namalengkap' => $namalengkap,
    'tempat' => $tempat,
    'tanggal' => $tanggal,
    'hobi' => $hobi,
    'pasangan' => $pasangan,
    'pekerjaan' => $pekerjaan,
    'ortu' => $ortu,
    'kakak' => $kakak,
    'adik' => $adik,
  ];

  $_SESSION['flash_error_bio'] = implode('<br>', $errors_bio);
  redirect_ke('index.php#biodata');
}

#menyiapkan query INSERT dengan prepared statement
$sql = "INSERT INTO tbl_biodata (nim, namalengkap, tempat, tanggal, hobi, pasangan, pekerjaan, ortu, kakak, adik) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  #jika gagal prepare, kirim pesan error ke pengguna (tanpa detail sensitif)
  $_SESSION['flash_error_bio'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#biodata');
}
#bind parameter dan eksekusi (s = string)
mysqli_stmt_bind_param($stmt, "isssssssss", $nim, $namalengkap, $tempat, $tanggal, $hobi, $pasangan, $pekerjaan, $ortu, $kakak, $adik);

if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old_bio value, beri pesan sukses
  unset($_SESSION['old_bio']);
  $_SESSION['flash_sukses_bio'] = 'Terima kasih, data Anda sudah tersimpan.';
  redirect_ke('index.php#biodata'); #pola PRG: kembali ke form / halaman home
} else { #jika gagal, simpan kembali old_bio value dan tampilkan error umum
  $_SESSION['old_bio'] = [
    'nim'  => $nim,
    'namalengkap' => $namalengkap,
    'tempat' => $tempat,
    'tanggal' => $tanggal,
    'hobi' => $hobi,
    'pasangan' => $pasangan,
    'pekerjaan' => $pekerjaan,
    'ortu' => $ortu,
    'kakak' => $kakak,
    'adik' => $adik,
  ];
  $_SESSION['flash_error_bio'] = 'Data gagal disimpan. Silakan coba lagi.';
  redirect_ke('index.php#biodata');
}
#tutup statement
mysqli_stmt_close($stmt);

$arrBiodata = [
  "nim" => $_POST["txtNim"] ?? "",
  "nama" => $_POST["txtNmLengkap"] ?? "",
  "tempat" => $_POST["txtT4Lhr"] ?? "",
  "tanggal" => $_POST["txtTglLhr"] ?? "",
  "hobi" => $_POST["txtHobi"] ?? "",
  "pasangan" => $_POST["txtPasangan"] ?? "",
  "pekerjaan" => $_POST["txtKerja"] ?? "",
  "ortu" => $_POST["txtNmOrtu"] ?? "",
  "kakak" => $_POST["txtNmKakak"] ?? "",
  "adik" => $_POST["txtNmAdik"] ?? ""
];
$_SESSION["biodata"] = $arrBiodata;

header("location: index.php#about");
