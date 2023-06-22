<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "codeaddict");
if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

$ders_adi = $_POST['ders_adi'];
$ogretmen = $_POST['ogretmen'];
$sinif = $_POST['sinif'];
$gun = $_POST['gun'];
$baslangic_saat = $_POST['baslangic_saat'];
$bitis_saat = $_POST['bitis_saat'];

// Kullanıcının oturum açtığı email adresi
$userEmail = $_SESSION['email'];

// Dersin sahibi olan kullanıcının oturum açmış email adresi ile dersi silme kontrolü
$sql = "DELETE dersler FROM dersler 
        INNER JOIN users ON dersler.user_id = users.id 
        WHERE ders_adi = ? AND ogretmen = ? AND sinif = ? AND gun = ? 
        AND baslangic_saat = ? AND bitis_saat = ? AND users.email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $ders_adi, $ogretmen, $sinif, $gun, $baslangic_saat, $bitis_saat, $userEmail);

if (mysqli_stmt_execute($stmt)) {
    $deletedRows = mysqli_stmt_affected_rows($stmt);
    if ($deletedRows > 0) {
        echo "Ders başarıyla silindi.";
    } else {
        echo "Dersi silmeye yetkiniz yok.";
    }

    // Tabloyu yeniden oluşturarak güncel verileri döndür
    $saatler = array("08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "13:00","13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00");
    $gunler = array("Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma");



    mysqli_close($conn);
} else {
    echo "Silme işlemi sırasında bir hata oluştu: " . mysqli_error($conn);
}