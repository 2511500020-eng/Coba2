<?php
session_start();
require_once __DIR__ . '/fungsi.php';
require_once __DIR__ . '/koneksi.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
  redirect_ke('index.php#biodata');
}

$nim = bersihkan($_POST['txtNim'] ?? '');
$namalengkap = bersihkan($_POST['txtNmLengkap'] ?? '');
$tempat = bersihkan($_POST['txtT4Lhr'] ?? '');
$tanggal = bersihkan($_POST['txtTglLhr'] ?? '');
$hobi = bersihkan($_POST['txtHobi'] ?? '');
$pekerjaan = bersihkan($_POST['txtKerja'] ?? '');
$pasangan = bersihkan($_POST['txtPasangan'] ?? '');
$ortu = bersihkan($_POST['txtNmOrtu'] ?? '');
$kakak = bersihkan($_POST['txtNmKakak'] ?? '');
$adik = bersihkan($_POST['txtNmAdik'] ?? '');

$errors = [];

if ($nim === '') {
  $errors[] = 'NIM wajib diisi.';
}

if ($namalengkap === '') {
  $errors[] = 'Nama wajib diisi.';
}

if ($tempat === '') {
  $errors[] = 'Tempat tinggal wajib diisi.';
}

if ($tanggal === '') {
  $errors[] = 'Tanggal lahir wajib diisi.';
}

if ($hobi === '') {
  $errors[] = 'Hobi wajib diisi.';
}

if ($pekerjaan === '') {
  $errors[] = 'Pekerjaan wajib diisi.';
}

if ($pasangan === '') {
  $errors[] = 'Pasangan wajib diisi.';
}

if ($ortu === '') {
  $errors[] = 'Nama orang tua wajib diisi.';
}

if ($kakak === '') {
  $errors[] = 'Nama kakak wajib diisi.';
}

if ($adik === '') {
  $errors[] = 'Nama adik wajib diisi.';
}

if (!empty($errors)) {
  $_SESSION['old'] = [
    'nim' => $nim,
    'namalengkap' => $namalengkap,
    'tempat' => $tempat,
    'tanggal' => $tanggal,
    'hobi' => $hobi,
    'pekerjaan' => $pekerjaan,
    'pasangan' => $pasangan,
    'ortu' => $ortu,
    'kakak' => $kakak,
    'adik' => $adik,
  ];

  $_SESSION['flash_error_bio'] = implode('<br>', $errors);
  redirect_ke('index.php#biodata');
}

$sql = "INSERT INTO tbl_biodata (nim, namalengkap, tempat, tanggal, hobi, pekerjaan, pasangan, ortu, kakak, adik) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  $_SESSION['flash_error_bio'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#biodata');
}

mysqli_stmt_bind_param($stmt, "ssssssssss", $nim, $namalengkap, $tempat, $tanggal, $hobi, $pekerjaan, $pasangan, $ortu, $kakak, $adik);

if (mysqli_stmt_execute($stmt)) {
  unset($_SESSION['old']);
  $_SESSION['flash_sukses_bio'] = 'Terima kasih, data Anda sudah tersimpan.';
  redirect_ke('index.php#biodata');
} else {
  $_SESSION['old'] = [
    'nim' => $nim,
    'namalengkap' => $namalengkap,
    'tempat' => $tempat,
    'tanggal' => $tanggal,
    'hobi' => $hobi,
    'pekerjaan' => $pekerjaan,
    'pasangan' => $pasangan,
    'ortu' => $ortu,
    'kakak' => $kakak,
    'adik' => $adik,
  ];
  $_SESSION['flash_error_bio'] = 'Data gagal disimpan. Silakan coba lagi.';
  redirect_ke('index.php#biodata');
}

mysqli_stmt_close($stmt);

?>