<?php
session_start();
$_SESSION["nama"] = $_GET["txtNama"];
$_SESSION["email"] = $_GET["txtEmail"];
$_SESSION["pesan"] = $_GET["txtPesan"];
header("Location: get.php");
?>