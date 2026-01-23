<?php
  session_start();
  require 'koneksi.php';
  require 'fungsi.php';

  /*
    Ambil nilai id dari GET dan lakukan validasi untuk 
    mengecek id harus angka dan lebih besar dari 0 (> 0).
    'options' => ['min_range' => 1] artinya id harus ≥ 1 
    (bukan 0, bahkan bukan negatif, bukan huruf, bukan HTML).
  */
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);
  /*
    Skrip di atas cara penulisan lamanya adalah:
    $id = $_GET['id'] ?? '';
    $id = (int)$id;

    Cara lama seperti di atas akan mengambil data mentah 
    kemudian validasi dilakukan secara terpisah, sehingga 
    rawan lupa validasi. Untuk input dari GET atau POST, 
    filter_input() lebih disarankan daripada $_GET atau $_POST.
  */

  /*
    Cek apakah $id bernilai valid:
    Kalau $id tidak valid, maka jangan lanjutkan proses, 
    kembalikan pengguna ke halaman awal (read.php) sembari 
    mengirim penanda error.
  */
  if (!$id) {
    $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
    redirect_ke('read_bio.php');
  }

  /*
    Ambil data lama dari DB menggunakan prepared statement, 
    jika ada kesalahan, tampilkan penanda error.
  */
  $stmt = mysqli_prepare($conn, "SELECT id, nim, namalengkap, tempat, tanggal, hobi, pasangan, pekerjaan, ortu, kakak, adik 
                                    FROM tbl_biodata WHERE id = ? LIMIT 1");
  if (!$stmt) {
    $_SESSION['flash_error_bio'] = 'Query tidak benar.';
    redirect_ke('read_bio.php');
  }

  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($res);
  mysqli_stmt_close($stmt);

  if (!$row) {
    $_SESSION['flash_error_bio'] = 'Record tidak ditemukan.';
    redirect_ke('read_bio.php');
  }

  #Nilai awal (prefill form)
  $nim  = $row['nim'] ?? '';
  $namalengkap = $row['namalengkap'] ?? '';
  $tempat = $row['tempat'] ?? '';
  $tanggal = $row['tanggal'] ?? '';
  $hobi = $row['hobi'] ?? '';
  $pasangan = $row['pasangan'] ?? '';
  $pekerjaan = $row['pekerjaan'] ?? '';
  $ortu = $row['ortu'] ?? '';
  $kakak = $row['kakak'] ?? '';
  $adik = $row['adik'] ?? '';

  #Ambil error dan nilai old_bio input kalau ada
  $flash_error_bio = $_SESSION['flash_error_bio'] ?? '';
  $old_bio = $_SESSION['old_bio'] ?? [];
  unset($_SESSION['flash_error_bio'], $_SESSION['old_bio']);
  if (!empty($old_bio)) {
    $nim  = $old_bio['nim'] ?? $nim;
    $namalengkap = $old_bio['namalengkap'] ?? $namalengkap;
    $tempat = $old_bio['tempat'] ?? $tempat;
    $tanggal = $old_bio['tanggal'] ?? $tanggal;
    $hobi = $old_bio['hobi'] ?? $hobi;
    $pasangan = $old_bio['pasangan'] ?? $pasangan;
    $pekerjaan = $old_bio['pekerjaan'] ?? $pekerjaan;
    $ortu = $old_bio['ortu'] ?? $ortu;
    $kakak = $old_bio['kakak'] ?? $kakak;
    $adik = $old_bio['adik'] ?? $adik;
  }
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judul Halaman</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Ini Header</h1>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle Navigation">
        &#9776;
      </button>
      <nav>
        <ul>
          <li><a href="#home">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#contact">Kontak</a></li>
        </ul>
      </nav>
    </header>

    <main>
    <section id="biodata">
      <h2>Biodata Sederhana Mahasiswa</h2>

      <?php if (!empty($flash_sukses_bio)): ?>
        <div style="padding:10px; margin-bottom:10px; background:#d4edda; color:#155724; border-radius:6px;">
          <?= $flash_sukses_bio; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($flash_error_bio)): ?>
        <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius:6px;">
          <?= $flash_error_bio; ?>
        </div>
      <?php endif; ?>

      <form action="proses_update_bio.php" method="POST">

        <label for="txtNim"><span>NIM:</span>
          <input type="number" id="txtNimEd" name="txtNimEd" placeholder="Masukkan NIM" required value="<?= !empty($nim) ? $nim : '' ?>">
        </label>

        <label for="txtNmLengkap"><span>Nama Lengkap:</span>
          <input type="text" id="txtNmLengkapEd" name="txtNmLengkapEd" placeholder="Masukkan Nama Lengkap" required value="<?= !empty($namalengkap) ? $namalengkap : '' ?>">
        </label>

        <label for="txtT4Lhr"><span>Tempat Lahir:</span>
          <input type="text" id="txtT4LhrEd" name="txtT4LhrEd" placeholder="Masukkan Tempat Lahir" required value="<?= !empty($tempat) ? $tempat : '' ?>">
        </label>

        <label for="txtTglLhr"><span>Tanggal Lahir:</span>
          <input type="text" id="txtTglLhrEd" name="txtTglLhrEd" placeholder="Masukkan Tanggal Lahir" required value="<?= !empty($tanggal) ? $tanggal : '' ?>">
        </label>

        <label for="txtHobi"><span>Hobi:</span>
          <input type="text" id="txtHobiEd" name="txtHobiEd" placeholder="Masukkan Hobi" required value="<?= !empty($hobi) ? $hobi : '' ?>">
        </label>

        <label for="txtPasangan"><span>Pasangan:</span>
          <input type="text" id="txtPasanganEd" name="txtPasanganEd" placeholder="Masukkan Nama Pasangan" required value="<?= !empty($pasangan) ? $pasangan : '' ?>">
        </label>

        <label for="txtKerja"><span>Pekerjaan:</span>
          <input type="text" id="txtKerjaEd" name="txtKerjaEd" placeholder="Masukkan Pekerjaan" required value="<?= !empty($pekerjaan) ? $pekerjaan : '' ?>">
        </label>

        <label for="txtNmOrtu"><span>Nama Orang Tua:</span>
          <input type="text" id="txtNmOrtuEd" name="txtNmOrtuEd" placeholder="Masukkan Nama Orang Tua" required value="<?= !empty($ortu) ? $ortu : '' ?>">
        </label>

        <label for="txtNmKakak"><span>Nama Kakak:</span>
          <input type="text" id="txtNmKakakEd" name="txtNmKakakEd" placeholder="Masukkan Nama Kakak" required value="<?= !empty($kakak) ? $adik : '' ?>">
        </label>

        <label for="txtNmAdik"><span>Nama Adik:</span>
          <input type="text" id="txtNmAdikEd" name="txtNmAdikEd" placeholder="Masukkan Nama Adik" required value="<?= !empty($adik) ? $adik : '' ?>">
        </label>

        <button type="submit">Kirim</button>
        <button type="reset">Batal</button>
      </form>
    </section>
    </main>

    <script src="script.js"></script>
  </body>
</html>