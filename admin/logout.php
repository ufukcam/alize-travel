<?php
require_once '../includes/config.php';

// Session'ı temizle
session_destroy();

// Giriş sayfasına yönlendir
header('Location: index.php');
exit;
?>
