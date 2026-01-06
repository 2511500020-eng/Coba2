<?php
    session_start();
    require 'koneksi.php';
    require 'fungsi.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    if (!$id) {
        $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
        redirect_ke('readBio.php');
    }

    $stmt = mysqli_prepare($conn, "SELECT id, nim, namalengkap, tempat, tanggal, hobi, pekerjaan, pasangan, ortu, kakak, adik FROM tbl_biodata WHERE id = ? LIMIT 1");
    if (!$stmt) {
        $_SESSION['flash_error_bio'] = 'Query tidak benar.';
        redirect_ke('readBio.php');
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if(!$row) {
        $_SESSION['flash_error_bio'] = 'Record tidak ditemukan.';
        redirect_ke('readBio.php');
    }

    $nim = $row["nim"] ?? "";
    $namalengkap = $row["namalengkap"] ?? "";
    $tempat = $row["tempat"] ?? "";
    $tanggal = $row["tanggal"] ?? "";
    $hobi = $row["hobi"] ?? "";
    $pasangan = $row["pasangan"] ?? "";
    $pekerjaan = $row["pekerjaan"] ?? "";
    $ortu = $row["ortu"] ?? "";
    $kakak = $row["kakak"] ?? "";
    $adik = $row["adik"] ?? "";

    $flash_error_bio = $_SESSION['flash_error_bio'] ?? '';
    $old = $_SESSION['$old'] ?? [];
    unset($_SESSION['flash_error_bio'], $_SESSION['old']);
    if (!empty($old)) {
      $nim = $row["nim"] ?? $nim;
      $namalengkap = $row["namalengkap"] ?? $namalengkap;
      $tempat = $row["tempat"] ?? $tempat;
      $tanggal = $row["tanggal"] ?? $tanggal;
      $hobi = $row["hobi"] ?? $hobi;
      $pasangan = $row["pasangan"] ?? $pasangan;
      $pekerjaan = $row["pekerjaan"] ?? $pekerjaan;
      $ortu = $row["ortu"] ?? $ortu;
      $kakak = $row["kakak"] ?? $kakak;
      $adik = $row["adik"] ?? $adik;
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
      <h2>Edit Biodata</h2>

      <?php if (!empty($flash_sukses_bio)): ?>
        <div style="padding:10px; margin-bottom:10px; background:#d4edda; color:#155724; border-radius:6px">
          <?= $flash_sukses_bio; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($flash_error_bio)): ?>
        <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius:6px">
          <?= $flash_error_bio; ?>  
        </div>
      <?php endif; ?>

      <form action="proses_update_bio.php" method="POST">

        <input type="text" name="id" value="<?= (int)$id; ?>">
    
        <label for="txtNim"><span>NIM:</span>
          <input type="text" id="txtNim" name="txtNimEd" placeholder="Masukkan NIM" required value="<?= !empty($nim) ? $nim : '' ?>">
        </label>

        <label for="txtNmLengkap"><span>Nama Lengkap:</span>
          <input type="text" id="txtNmLengkap" name="txtNmLengkapEd" placeholder="Masukkan Nama Lengkap" required value="<?= !empty($namalengkap) ? $namalengkap : '' ?>">
        </label>

        <label for="txtT4Lhr"><span>Tempat Lahir:</span>
          <input type="text" id="txtT4Lhr" name="txtT4LhrEd" placeholder="Masukkan Tempat Lahir" required value="<?= !empty($tempat) ? $tempat : '' ?>">
        </label>

        <label for="txtTglLhr"><span>Tanggal Lahir:</span>
          <input type="text" id="txtTglLhr" name="txtTglLhrEd" placeholder="Masukkan Tanggal Lahir" required value="<?= !empty($tanggal) ? $tanggal : '' ?>">
        </label>

        <label for="txtHobi"><span>Hobi:</span>
          <input type="text" id="txtHobi" name="txtHobiEd" placeholder="Masukkan Hobi" required value="<?= !empty($hobi) ? $hobi : '' ?>">
        </label>

        <label for="txtPasangan"><span>Pasangan:</span>
          <input type="text" id="txtPasangan" name="txtPasanganEd" placeholder="Masukkan Pasangan" required value="<?= !empty($pasangan) ? $pasangan : '' ?>">
        </label>

        <label for="txtKerja"><span>Pekerjaan:</span>
          <input type="text" id="txtKerja" name="txtKerjaEd" placeholder="Masukkan Pekerjaan" required value="<?= !empty($pekerjaan) ? $pekerjaan : '' ?>">
        </label>

        <label for="txtNmOrtu"><span>Nama Orang Tua:</span>
          <input type="text" id="txtNmOrtu" name="txtNmOrtuEd" placeholder="Masukkan Nama Orang Tua" required value="<?= !empty($ortu) ? $ortu : '' ?>">
        </label>

        <label for="txtNmKakak"><span>Nama Kakak:</span>
          <input type="text" id="txtNmKakak" name="txtNmKakakEd" placeholder="Masukkan Nama Kakak" required value="<?= !empty($kakak) ? $kakak : '' ?>">
        </label>

        <label for="txtNmAdik"><span>Nama Adik:</span>
          <input type="text" id="txtNmAdik" name="txtNmAdikEd" placeholder="Masukkan Nama Adik" required value="<?= !empty($adik) ? $adik : '' ?>">
        </label>

        <button type="submit">Kirim</button>
        <button type="reset">Batal</button>
        <a href="readBio.php" class="reset">Kembali</a>
      </form>
    </section>
  </main>

  <script src="script.js"></script>
</body>
</html>