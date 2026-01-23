<?php
  session_start();
  require __DIR__ . './koneksi.php';
  require_once __DIR__ . '/fungsi.php';

  #cek method form, hanya izinkan POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
    redirect_ke('read_bio.php');
  }

  #validasi id wajib angka dan > 0
  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);

  if (!$id) {
    $_SESSION['flash_error_bio'] = 'id Tidak Valid.';
    redirect_ke('edit_bio.php?id='. (int)$id);
  }

  #ambil dan bersihkan (sanitasi) nilai dari form
  $nim  = bersihkan($_POST['txtNimEd']  ?? '');
  $namalengkap = bersihkan($_POST['txtNmLengkapEd'] ?? '');
  $tempat = bersihkan($_POST['txtT4LhrEd'] ?? '');
  $tanggal = bersihkan($_POST['txtTglLhrEd'] ?? '');
  $hobi = bersihkan($_POST['txtHobiEd'] ?? '');
  $pasangan = bersihkan($_POST['txtPasanganEd'] ?? '');
  $pekerjaan = bersihkan($_POST['txtKerjaEd'] ?? '');
  $ortu = bersihkan($_POST['txtNmOrtuEd'] ?? '');
  $kakak = bersihkan($_POST['txtNmKakakEd'] ?? '');
  $adik = bersihkan($_POST['txtNmAdikEd'] ?? '');

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
      redirect_ke('edit_bio.php?id='. (int)$id);
    }

  /*
    Prepared statement untuk anti SQL injection.
    menyiapkan query UPDATE dengan prepared statement 
    (WAJIB WHERE id = ?)
  */
  $stmt = mysqli_prepare($conn, "UPDATE tbl_biodata 
                                SET nim = ?, namalengkap = ?, tempat = ?, tanggal = ?, hobi = ?, pasangan = ?, pekerjaan = ?, ortu = ?, kakak = ?, adik = ? 
                                WHERE id = ?");
  if (!$stmt) {
    #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
    $_SESSION['flash_error_bio'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit_bio.php?id='. (int)$id);
  }

  #bind parameter dan eksekusi (s = string, i = integer)
  mysqli_stmt_bind_param($stmt, "isssssssssi", $nim, $namalengkap, $tempat, $tanggal, $hobi, $pasangan, $pekerjaan, $ortu, $kakak, $adik, $id);

  if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old_bio value
    unset($_SESSION['old_bio']);
    /*
      Redirect balik ke read_bio.php dan tampilkan info sukses.
    */
    $_SESSION['flash_sukses_bio'] = 'Terima kasih, data Anda sudah diperbaharui.';
    redirect_ke('read_bio.php'); #pola PRG: kembali ke data dan exit()
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
    $_SESSION['flash_error_bio'] = 'Data gagal diperbaharui. Silakan coba lagi.';
    redirect_ke('edit_bio.php?id='. (int)$id);
  }
  #tutup statement
  mysqli_stmt_close($stmt);

  redirect_ke('edit_bio.php?id='. (int)$id);