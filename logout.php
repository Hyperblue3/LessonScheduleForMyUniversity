<?php
// Oturumu sonlandırma ve oturum bilgilerini temizleme
session_start();
session_destroy();

// Kullanıcıyı giriş sayfasına yönlendirme
header("Location: index.php");
exit;
?>