<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome kütüphanesi için link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap kütüphanesi için link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Özel stiller için link -->
    <link rel="stylesheet" href="login.css">
    <!-- Sayfa başlığı -->
    <title>Form | Login</title>
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    
    
</head>
<body>

    <header>
        <nav class="nav2">
            <ul>
                <li>
                <h1> DERS PROGRAMI DÜZENLEME WEB SİTESİ </h1>
                   <h1>GİRİŞ YAP</h1>
                </li>
            </ul>
        </nav>
    </header>


    <main>
    <section class="main-section1">
    
    <div class="form-box">
        <?php
        if(isset($_POST['login_btn']))
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $con = mysqli_connect("sql109.infinityfree.com","if0_34397881","VnWOHI8EwlBl","if0_34397881_codeaddict");
            $sql = "SELECT*FROM `users` WHERE `email`='$email' AND `password`='$password'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_num_rows($result);
            if($row>0)
            {
                session_start();
                $_SESSION['email'] = $email;
                header("location: welcome.php");
            }
            else{
                echo "<div class='alert alert-danger' role='alert'>Gecersiz Kimlik Bilgileri.</div>";
            }
        }
        
        ?>
      

        <form action="index.php" method="POST">

        <input type="email" name="email" class="form-control" placeholder="Email">
        <input type="password" name="password" class="form-control"  placeholder="Şifre">
                    
        <button class="btn btn-outline-primary" name="login_btn">GiRiŞ YAP</button>

        <a href="signup.php" class="btn btn-outline-success" style="margin-top: 10px;">KAYIT OL</a>
        <div class="login-link">
        <p>Henüz Bir Hesabın Yok Mu? <a href="signup.php">Kayıt Ol</a></p>
        <p><a href="forgot.php">Şifremi Unuttum</a></p>
        </div>
        </form>
      </div>
       

    </section>
</main>
    
</body>
</html>
    
</body>
</html>