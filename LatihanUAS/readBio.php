<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

$sql = "SELECT * FROM tbl_biodata ORDER BY id DESC";
$q = mysqli_query($conn, $sql);
$no = 1;
if (!$q) {
    die("Query error: " . mysqli_error($conn));
}
?>

<?php
    $flash_sukses = $_SESSION['flash_sukses_bio'] ?? '';
    $flash_error = $_SESSION['flash_error_bio'] ?? '';
    unset($_SESSION['flash_sukses_bio'], $_SESSION['flash_error_bio']);
?>

<?php if (!empty($flash_sukses)): ?>
    <div style="padding:10px; margin-bottom:10px; background:#d4edda; color:#155724; border-radius:6px;">
        <?= $flash_sukses ?>
    </div>
<?php endif; ?>

<?php if (!empty($flash_error)): ?>
    <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius:6px;">
        <?= $flash_error ?>
    </div>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Aksi</th>
        <th>ID</th>
        <th>NIM</th>
        <th>Nama Lengkap</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Hobi</th>
        <th>Pasangan</th>
        <th>Pekerjaan</th>
        <th>Orang Tua</th>
        <th>Kakak</th>
        <th>Adik</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($q)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td>
                <a href="editBio.php?id=<?= (int)$row['id']; ?>">Edit</a>
                <a onclick="return confirm('Hapus <?= htmlspecialchars($row['namalengkap']); ?>?')" href="proses_delete_bio.php?id=<?= (int)$row['id']; ?>">Delete</a>
            </td>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['nim']); ?></td>
            <td><?= htmlspecialchars($row['namalengkap']); ?></td>
            <td><?= nl2br(htmlspecialchars($row['tempat'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['tanggal'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['hobi'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['pasangan'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['pekerjaan'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['ortu'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['kakak'])); ?></td>
            <td><?= nl2br(htmlspecialchars($row['adik'])); ?></td>
        </tr>
    <?php endwhile; ?>
</table>