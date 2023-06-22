<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="welcome.css">

  <title>Ders Programı</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
    
      // Çıkış yap linkine tıklandığında
      $("#logout-link").click(function(e) {
        e.preventDefault();

        $.ajax({
          type: "GET",
          url: "logout.php",
          success: function(response) {
            window.location.href = "index.php";
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>
   
</head>

<body>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: index.php");
  exit;
}

$email = $_SESSION['email'];
echo '<div style="position: absolute; top: 10px; right: 10px;">Hoş geldiniz: ' . $email . '</div>';
?>
  </div>
  
  <h1>Ders Ekle</h1>
  <form id="" method="post" action="" >
	<label for="ders_adi">Ders Adı:</label>
	<input type="text" id="ders_adi" name="ders_adi" required><br>

	<label for="ogretmen">Öğretmen Adı:</label>
	<input type="text" id="ogretmen" name="ogretmen" required><br>

	<label for="sinif">Sınıf:</label>
	<select id="sinif" name="sinif" required>
		<option value="turkuazamfi">TurkuazAmfi</option>
		<option value="bordoamfi">BordoAmfi</option>
		<option value="yesilamfi">YesilAmfi</option>
		<option value="ZK01">ZK01</option>
		<option value="ZK02">ZK02</option>
		<option value="ZK03">ZK03</option>
	</select><br>

	<label for="gun">Gün:</label>
	<select id="gun" name="gun" required>
		<option value="Pazartesi">Pazartesi</option>
		<option value="Salı">Salı</option>
		<option value="Çarşamba">Çarşamba</option>
		<option value="Perşembe">Perşembe</option>
		<option value="Cuma">Cuma</option>
	</select><br>

	<label for="baslangic_saat">Başlangıç Saati:</label>
	<input type="time" id="baslangic_saat" name="baslangic_saat" required><br>

	<label for="bitis_saat">Bitiş Saati:</label>
	<input type="time" id="bitis_saat" name="bitis_saat"required > <br>
  

  <label for="ogrenci_sayisi">Öğrenci Sayisi:</label>
    <input type="number" id="ogrenci_sayisi" name="ogrenci_sayisi" required><br>

	<input type="submit" value="Kaydet">
</form>
<br>
<div id="uyari-mesaji" style="color: red;"></div>

<h1>Ders sil</h1>
<form method="post" action="" name="">
    <!-- Ders silme formu -->
    <label for="sil_ders_adi">Ders Adı:</label>
    <input type="text" id="sil_ders_adi" name="sil_ders_adi" required><br>

    <label for="sil_ogretmen">Öğretmen Adı:</label>
    <input type="text" id="sil_ogretmen" name="sil_ogretmen" required><br>

    <label for="sil_sinif">Sınıf:</label>
    <select id="sil_sinif" name="sil_sinif" required>
      <option value="turkuazamfi">TurkuazAmfi</option>
      <option value="bordoamfi">BordoAmfi</option>
      <option value="yesilamfi">YesilAmfi</option>
      <option value="ZK01">ZK01</option>
      <option value="ZK02">ZK02</option>
      <option value="ZK03">ZK03</option>
    </select><br>

    <label for="sil_gun">Gün:</label>
    <select id="sil_gun" name="sil_gun" required>
    <option value="Pazartesi">Pazartesi</option>
      <option value="Salı">Salı</option>
      <option value="Çarşamba">Çarşamba</option>
      <option value="Perşembe">Perşembe</option>
      <option value="Cuma">Cuma</option>
    </select><br>
    
    <label for="sil_baslangic_saat">Başlangıç Saati:</label>
    <input type="time" id="sil_baslangic_saat" name="sil_baslangic_saat" required><br>
    
    <label for="sil_bitis_saat">Bitiş Saati:</label>
    <input type="time" id="sil_bitis_saat" name="sil_bitis_saat" required><br>

    
    
    <input type="submit" value="Sil">
  </form>
  
  <br>
  
  <table border="1" id="dersler-tablo">
    <!-- Tablo içeriği burada güncellenecek -->
  </table>
  
  <br>
  
  <a href="#" id="logout-link">Çıkış Yap</a>
  
  <?php

if (!isset($_SESSION['email'])) {
  echo "Oturum açmış bir kullanıcı yok.";
  exit;
}

// Başarı mesajını görüntüle
if (isset($_SESSION['success'])) {
  echo $_SESSION['success'];
  unset($_SESSION['success']); // Mesajı temizle
}
if (isset($_SESSION['error'])) {
  echo $_SESSION['error'];
  unset($_SESSION['error']);
} 
$email = $_SESSION['email'];

// Veritabanı bağlantısı
$conn = mysqli_connect("sql109.infinityfree.com","if0_34397881","VnWOHI8EwlBl","if0_34397881_codeaddict");
if (!$conn) {
  die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

$sinif_kapasiteleri = array(
  'turkuazamfi' => 120,
  'bordoamfi' => 120,
  'yesilamfi' => 120,
  'ZK01' => 60,
  'ZK02' => 60,
  'ZK03' => 60,
);

// POST verilerini al
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['ders_adi'], $_POST['ogretmen'], $_POST['sinif'], $_POST['gun'], $_POST['baslangic_saat'], $_POST['bitis_saat'])) {
    $ders_adi = $_POST['ders_adi'];
    $ogretmen = $_POST['ogretmen'];
    $sinif = $_POST['sinif'];
    $gun = $_POST['gun'];
    $baslangic_saat = $_POST['baslangic_saat'];
    $bitis_saat = $_POST['bitis_saat'];
    $ogrenci_sayisi = $_POST['ogrenci_sayisi'];

    // Kayıt var mı kontrol et
    $sql = "SELECT COUNT(*) AS count FROM dersler WHERE sinif = ? AND gun = ? AND ((baslangic_saat >= ? AND baslangic_saat < ?) OR (baslangic_saat <= ? AND bitis_saat >= ?))";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $sinif, $gun, $baslangic_saat, $bitis_saat, $baslangic_saat, $baslangic_saat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
      echo '<div class="error-message">Seçilen sınıf bu saatlerde dolu, lütfen başka bir saat veya sınıf seçin.</div>';
    } elseif ($ogrenci_sayisi > $sinif_kapasiteleri[$sinif]) {
      echo '<div class="error-message">Seçilen sınıfın kapasitesi bu ders için yeterli değildir, lütfen başka derslik seçiniz.</div>';
    }else {
      // Yeni kayıt ekleme
      function getUserId($conn, $email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if ($row = mysqli_fetch_assoc($result)) {
            return $row['id'];
          }
          return null;
        }
         
        $user_id = getUserId($conn, $email); // Kullanıcının ID'sini al
      
        if ($user_id) {
          $sql = "INSERT INTO dersler (ders_adi, ogretmen, sinif, gun, baslangic_saat, bitis_saat, user_id, ogrenci_sayisi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "ssssssii", $ders_adi, $ogretmen, $sinif, $gun, $baslangic_saat, $bitis_saat, $user_id, $ogrenci_sayisi);
      
          if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Kayıt başarıyla eklendi."; "Kayıt başarıyla eklendi.";
      
            // Sayfa yenilemeden tabloyu güncelleyebilmek için JavaScript kodu
            echo "<script>$('#dersler-tablo').load(location.href + ' #dersler-tablo');</script>";
      
            // Formu sıfırla
            echo "<script>document.getElementsByName('ekleForm')[0].reset();</script>";

            header("Location: welcome.php");
            
            exit; // İşlemi sonlandır

          } else {
            echo "Kayıt sırasında bir hata oluştu: " . mysqli_error($conn);
          }
        } else {
          echo "Kullanıcı bulunamadı.";
        }
      }
    } else {
      echo '<div class="error-message">eksik veri gönderildi.</div>';
    }
  }

  // Kullanıcının oturum açtığı email adresi
$userEmail = $_SESSION['email'];

// Silme işlemi için form gönderildiğinde çalışacak kod
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sil_ders_adi'], $_POST['sil_ogretmen'], $_POST['sil_sinif'], $_POST['sil_gun'], $_POST['sil_baslangic_saat'], $_POST['sil_bitis_saat'])) {
  $ders_adi = $_POST['sil_ders_adi'];
  $ogretmen = $_POST['sil_ogretmen'];
  $sinif = $_POST['sil_sinif'];
  $gun = $_POST['sil_gun'];
  $baslangic_saat = $_POST['sil_baslangic_saat'];
  $bitis_saat = $_POST['sil_bitis_saat'];

  // Dersin sahibi olan kullanıcının oturum açmış email adresi ile dersi silme kontrolü
  $sql = "DELETE dersler FROM dersler 
          INNER JOIN users ON dersler.user_id = users.id 
          WHERE ders_adi = ? AND ogretmen = ? AND sinif = ? AND gun = ? AND baslangic_saat = ? AND bitis_saat = ? AND users.email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sssssss", $ders_adi, $ogretmen, $sinif, $gun, $baslangic_saat, $bitis_saat, $userEmail);

  if (mysqli_stmt_execute($stmt)) {
    $deletedRows = mysqli_stmt_affected_rows($stmt);
    if ($deletedRows > 0) {
      $_SESSION['success'] = "Ders başarıyla silindi.";
      // Sayfa yenilemeden tabloyu güncelleyebilmek için JavaScript kodu
      echo "<script>$('#dersler-tablo').load(location.href + ' #dersler-tablo');</script>";
      header("Location: welcome.php");
      exit; // İşlemi sonlandır
    } else {
      echo '<div class="error-message">Ders silmeye yetkiniz yok.</div>';
    }
  } else {
    echo "Ders silme sırasında bir hata oluştu: " . mysqli_error($conn);
  }
}


  


  $saatler = array("08:00", "08:20", "08:30", "08:50", "09:00", "09:20","09:30","09:50","10:00", "10:20", "10:30", "10:50", "11:00", "11:20", "11:30", "11:50", "12:00", "12:20", "12:30", "12:50", "13:00", "13:20", "13:30","13:50", "14:00", "14:20", "14:30", "14:50", "15:00", "15:20", "15:30", "15:50", "16:00", "16:20", "16:30", "16:50", "17:00","17:20");
  $gunler = array("Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma");

  echo '<table border="1">';
  echo '<thead><tr><th>Saat / Gün</th>';

  foreach ($gunler as $gun) {
    echo '<th>' . $gun . '</th>';
  }

  echo '</tr></thead><tbody>';

  foreach ($saatler as $saat) {
    echo '<tr>';
    echo '<td>' . $saat . '</td>';

    foreach ($gunler as $gun) {
      $sql = "SELECT ders_adi, ogretmen, sinif FROM dersler 
              INNER JOIN users ON dersler.user_id = users.id 
              WHERE users.email = ? AND dersler.gun = ? AND ? BETWEEN dersler.baslangic_saat AND dersler.bitis_saat";
              $stmt = mysqli_prepare($conn, $sql);
              mysqli_stmt_bind_param($stmt, "sss", $email, $gun, $saat);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              if ($row = mysqli_fetch_assoc($result)) {
                echo '<td>' . $row['ders_adi'] . '<br>' . $row['ogretmen'] . '<br>' . $row['sinif'] . '</td>';
              } else {
                echo '<td></td>';
              }
            }
        
            echo '</tr>';
          }
        
          echo '</tbody></table>';
        
          // Veritabanı bağlantısını kapat
          mysqli_close($conn);
        ?>
  

  

  <script>
  // Bitiş saatini kontrol et
  document.getElementById('bitis_saat').addEventListener('blur', function() {
    var input = document.getElementById('bitis_saat');
    var value = input.value;

    var selectedHour = parseInt(value.split(':')[0]);
    var selectedMinute = parseInt(value.split(':')[1]);

    if (selectedMinute !== 20 && selectedMinute !== 50) {
     
      input.value = '';
      input.focus();
    }
  });
</script>

<script>
  // Bitiş saatini kontrol et
  document.getElementById('baslangic_saat').addEventListener('blur', function() {
    var input = document.getElementById('baslangic_saat');
    var value = input.value;

    var selectedHour = parseInt(value.split(':')[0]);
    var selectedMinute = parseInt(value.split(':')[1]);

    if (selectedMinute !== 00 && selectedMinute !== 30) {
     
      input.value = '';
      input.focus();
    }
  });
</script>

  <script>
    
$("#uyari-mesaji").html("UYARI ! <br>Başlangıç saati sadece xx:00 veya xx:30 seçilebilir <br>Bitiş saati sadece xx:20 veya xx:50 seçilebilir ");
    </script>
    
</body>

</html>