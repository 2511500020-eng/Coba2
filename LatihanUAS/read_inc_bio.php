<?php
require 'koneksi.php';

$fieldBiodata = [
      "nim" => ["label" => "NIM:", "suffix" => ""],
      "namalengkap" => ["label" => "Nama Lengkap:", "suffix" => ""],
      "tempat" => ["label" => "Tempat Lahir:", "suffix" => ""],
      "tanggal" => ["label" => "Tanggal Lahir:", "suffix" => ""],
      "hobi" => ["label" => "Hobi:", "suffix" => ""],
      "pasangan" => ["label" => "Pasangan:", "suffix" => ""],
      "pekerjaan" => ["label" => "Pekerjaan:", "suffix" => ""],
      "ortu" => ["label" => "Nama Orang Tua:", "suffix" => ""],
      "kakak" => ["label" => "Nama Kakak:", "suffix" => ""],
      "adik" => ["label" => "Nama Adik:", "suffix" => ""],
    ];

$sql = "SELECT * FROM tbl_biodata ORDER BY id DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
    echo "<p>Gagal membaca data biodata: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} elseif (mysqli_num_rows($q) === 0) {
    echo "<p>Belum ada data biodata yang tersimpan.</p>";
} else {
    while ($row = mysqli_fetch_assoc($q)) {
        $arrBiodata = [
            "nim" => $row["nim"] ?? "",
            "namalengkap" => $row["namalengkap"] ?? "",
            "tempat" => $row["tempat"] ?? "",
            "tanggal" => $row["tanggal"] ?? "",
            "hobi" => $row["hobi"] ?? "",
            "pasangan" => $row["pasangan"] ?? "",
            "pekerjaan" => $row["pekerjaan"] ?? "",
            "ortu" => $row["ortu"] ?? "",
            "kakak" => $row["kakak"] ?? "",
            "adik" => $row["adik"] ?? "",
        ];
    echo tampilkanBiodata($fieldBiodata, $arrBiodata);
    }
}
?>