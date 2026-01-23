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
    $nama  = $old_bio['nama'] ?? $nama;
    $email = $old_bio['email'] ?? $email;
    $pesan = $old_bio['pesan'] ?? $pesan;
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
      <section id="contact">
        <h2>Edit Buku Tamu</h2>
        <?php if (!empty($flash_error_bio)): ?>
          <div style="padding:10px; margin-bottom:10px; 
            background:#f8d7da; color:#721c24; border-radius:6px;">
            <?= $flash_error_bio; ?>
          </div>
        <?php endif; ?>
        <form action="proses_update.php" method="POST">

          <input type="text" name="id" value="<?= (int)$id; ?>">

          <label for="txtNama"><span>Nama:</span>
            <input type="text" id="txtNama" name="txtNamaEd" 
              placehold_bioer="Masukkan nama" required autocomplete="name"
              value="<?= !empty($nama) ? $nama : '' ?>">
          </label>

          <label for="txtEmail"><span>Email:</span>
            <input type="email" id="txtEmail" name="txtEmailEd" 
              placehold_bioer="Masukkan email" required autocomplete="email"
              value="<?= !empty($email) ? $email : '' ?>">
          </label>

          <label for="txtPesan"><span>Pesan Anda:</span>
            <textarea id="txtPesan" name="txtPesanEd" rows="4" 
              placehold_bioer="Tulis pesan anda..." 
              required><?= !empty($pesan) ? $pesan : '' ?></textarea>
          </label>

          <label for="txtCaptcha"><span>Captcha 2 x 3 = ?</span>
            <input type="number" id="txtCaptcha" name="txtCaptcha" 
              placehold_bioer="Jawab Pertanyaan..." required>
          </label>

          <button type="submit">Kirim</button>
          <button type="reset">Batal</button>
          <a href="read.php" class="reset">Kembali</a>
        </form>
      </section>
    </main>

    <script src="script.js"></script>
  </body>
</html>