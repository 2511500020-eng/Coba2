<?php
  session_start();
  require __DIR__ . './koneksi.php';
  require_once __DIR__ . '/fungsi.php';

  #validasi id wajib angka dan > 0
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);

  if (!$id) {
    $_SESSION['flash_error_bio'] = 'id Tidak Valid.';
    redirect_ke('readBio.php');
  }

  /*
    Prepared statement untuk anti SQL injection.
    menyiapkan query UPDATE dengan prepared statement 
    (WAJIB WHERE id = ?)
  */
  $stmt = mysqli_prepare($conn, "DELETE FROM tbl_biodata
                                WHERE id = ?");
  if (!$stmt) {
    #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
    $_SESSION['flash_error_bio'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('readBio.php');
  }

  #bind parameter dan eksekusi (s = string, i = integer)
  mysqli_stmt_bind_param($stmt, "i", $id);

  if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value
    /*
      Redirect balik ke read.php dan tampilkan info sukses.
    */
    $_SESSION['flash_sukses_bio'] = 'Terima kasih, data Anda sudah dihapus.';
    redirect_ke('readBio.php'); #pola PRG: kembali ke data dan exit()
  } else {
    $_SESSION['flash_error_bio'] = 'Data gagal dihapus. Silakan coba lagi.';
  }
  #tutup statement
  mysqli_stmt_close($stmt);

  redirect_ke('readBio.php');