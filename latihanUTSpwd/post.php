<?php
  session_start();
  $sesnama = "";
  if (isset($_SESSION["nama"])):
      $sesnama = $_SESSION["nama"];
  endif;

  $sesemail = "";
  if (isset($_SESSION["email"])):
      $sesemail = $_SESSION["email"];
  endif;

  $sespesan = "";
  if (isset($_SESSION["pesan"])):
      $sespesan = $_SESSION["pesan"];
  endif;
?>
<!DOCTYPE html>
<html lang="en">

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
        <li><a href="#biodata">Biodata</a></li>
        <li><a href="#contact">Kontak</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section id="home">
      <h2>Selamat Datang</h2>
      <?php
      echo "halo dunia!<br>";
      echo "nama saya hadi";
      ?>
      <p>Ini contoh paragraf HTML.</p>
    </section>

    <section id="about">
      <?php
      $nim = $_SESSION["nim"];
      $nama = $_SESSION["namaLengkap"];
      ?>
      <h2>Tentang Saya</h2>
      <p><strong>NIM:</strong>
        <?php
        echo $nim;
        ?>
      </p>
      <p><strong>Nama Lengkap:</strong>
        <?php
        echo $nama;
        ?> &#128526;
      </p>
      <p><strong>Tempat Lahir:</strong> Pangkalpinang</p>
      <p><strong>Tanggal Lahir:</strong> 1 Januari 2000</p>
      <p><strong>Hobi:</strong> Memasak, coding, dan bermain musik &#127926;</p>
      <p><strong>Pasangan:</strong> Belum ada &hearts;</p>
      <p><strong>Pekerjaan:</strong> Dosen di ISB Atma Luhur &copy; 2025</p>
      <p><strong>Nama Orang Tua:</strong> Bapak Setiawan dan Ibu Maria</p>
      <p><strong>Nama Kakak:</strong> Antonius Setiawan</p>
      <p><strong>Nama Adik:</strong> Christina Setiawan</p>
    </section>

    <section id="biodata">
      <h2>Biodata</h2>
      <form action="post_proses.php" method="POST">

        <label for="txtNim"><span>Nim:</span>
          <input type="text" id="txtNim" name="txtNim" placeholder="Masukkan NIM" required autocomplete="nim">
        </label>

        <label for="txtNamaLengkap"><span>Nama:</span>
          <input type="text" id="txtNamaLengkap" name="txtNamaLengkap" placeholder="Masukkan nama lengkap" required autocomplete="namaLengkap">
        </label>

        <button type="submit">Simpan</button>
        <button type="reset">Batal</button>
      </form>
    </section>

    <section id="contact">
      <h2>Kontak Kami</h2>
      <form action="post_proses.php" method="POST">

        <label for="txtNama"><span>Nama:</span>
          <input type="text" id="txtNama" name="txtNama" placeholder="Masukkan nama" required autocomplete="name">
        </label>

        <label for="txtEmail"><span>Email:</span>
          <input type="email" id="txtEmail" name="txtEmail" placeholder="Masukkan email" required autocomplete="email">
        </label>

        <label for="txtPesan"><span>Pesan Anda:</span>
          <textarea id="txtPesan" name="txtPesan" rows="4" placeholder="Tulis pesan anda..." required></textarea>
          <small id="charCount">0/200 karakter</small>
        </label>


        <button type="submit">Kirim</button>
        <button type="reset">Batal</button>
      </form>

      <?php if(!empty($sesnama)): ?>
      <p>Terima kasih sudah menghubungi kami:
        <label>Nama: <strong><?php echo $sesnama ?></strong></label>
        <label>Email: <strong><?php echo $sesemail ?></strong></label>
        <label>Pesan: <strong><?php echo $sespesan ?></strong></label>
      </p>
      <?php endif; ?>

    </section>
  </main>

  <footer>
    <p>&copy; 2025 Yohanes Setiawan Japriadi [0344300002]</p>
  </footer>

  <script src="script.js"></script>
</body>

</html>