<?php
    session_start();
    require __DIR__ . '/koneksi.php';
    require_once __DIR__ . '/fungsi.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['flash_error_bio'] = 'Akses tidak valid.';
        redirect_ke('read.php');
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    if (!$id) {
        $_SESSION['flash_error_bio'] = 'id tidak valid.';
        redirect_ke('edit.php?id='. (int)$id);
    }

    $nim = bersihkan($_POST['txtNimEd'] ?? '');
    $namalengkap = bersihkan($_POST['txtNmLengkapEd'] ?? '');
    $tempat = bersihkan($_POST['txtT4LhrEd'] ?? '');
    $tanggal = bersihkan($_POST['txtTglLhrEd'] ?? '');
    $hobi = bersihkan($_POST['txtHobiEd'] ?? '');
    $pekerjaan = bersihkan($_POST['txtKerjaEd'] ?? '');
    $pasangan = bersihkan($_POST['txtPasanganEd'] ?? '');
    $ortu = bersihkan($_POST['txtNmOrtuEd'] ?? '');
    $kakak = bersihkan($_POST['txtNmKakakEd'] ?? '');
    $adik = bersihkan($_POST['txtNmAdikEd'] ?? '');

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
        redirect_ke('editBio.php?id='. (int)$id);
    }

    $stmt = mysqli_prepare($conn, "UPDATE tbl_biodata SET nim = ?, namalengkap = ?, tempat = ?, tanggal = ?, hobi = ?, pekerjaan = ?, pasangan = ?, ortu = ?, kakak = ?, adik = ? WHERE id = ?");

    if (!$stmt) {
        $_SESSION['flash_error_bio'] = 'Terjadi kesalahan sistem (prepare gagal).';
        redirect_ke('editBio.php?id='. (int)$id);
    }

    mysqli_stmt_bind_param($stmt, "ssssssssssi", $nim, $namalengkap, $tempat, $tanggal, $hobi, $pekerjaan, $pasangan, $ortu, $kakak, $adik, $id);

    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['old']);
        $_SESSION['flash_sukses_bio'] = 'Terima kasih, data Anda sudah diperbaharui.';
        redirect_ke('readBio.php');
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
        $_SESSION['flash_error_bio'] = 'Data gagal diperbaharui. Silakan coba lagi.';
        redirect_ke('editBio.php?id='. (int)$id);
    }

mysqli_stmt_close($stmt);

redirect_ke('editBio.php?id='. (int)$id);

?>