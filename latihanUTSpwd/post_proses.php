<?php
session_start();
$_SESSION["nama"] = $_POST["txtNama"];
$_SESSION["email"] = $_POST["txtEmail"];
$_SESSION["pesan"] = $_POST["txtPesan"];

$_SESSION["namaLengkap"] = $_POST["txtNamaLengkap"];
$_SESSION["nim"] = $_POST["txtNim"];

echo $_SESSION["nama"] . " " . $_SESSION["email"] . " " . $_SESSION["pesan"];
header("Location: post.php");
?>