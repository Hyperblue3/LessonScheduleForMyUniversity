<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>Form | Signup</title>
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    
</head>
<body>

    <header>
        <nav class="nav2">
            <ul>
                <li>
                <h1> DERS PROGRAMI DÜZENLEME WEB SİTESİ </h1>
                   <h1>KAYIT OL</h1>
                </li>
            </ul>
        </nav>
    </header>


    <main>
    <section class="main-section1">
        
    <div class="form-box">

        <?php
        if(isset($_POST['reg_btn']))
        {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
    
            $con = mysqli_connect("sql109.infinityfree.com","if0_34397881","VnWOHI8EwlBl","if0_34397881_codeaddict");
            $sql = "INSERT INTO `users`(`email`,`password`,`confirm_password`) 
            VALUES ('$email','$password','$confirm_password')";
            $sql2 = "SELECT * FROM `users` WHERE `email`='$email'";
            $result = mysqli_query($con,$sql2);
            $row = mysqli_num_rows($result);
            if($row>0)
{
    echo "<div class='alert alert-danger' role='alert'>Bu e-posta adresi zaten kayıtlı.</div>";
}
elseif($password != $confirm_password)
{
    echo "<div class='alert alert-danger' role='alert'>Şifreler eşleşmiyor.</div>";
}
else{
    $result2 = mysqli_query($con, $sql);
    echo "<div class='alert alert-success' role='alert'>Kayıt başarıyla oluşturuldu.</div>";
}

        }

       
        
        ?>

   
        <form action="signup.php" method="POST">

        <input type="email" class="form-control" name="email" placeholder="Email">
        <input type="password" class="form-control" name="password" placeholder="Şifrenizi giriniz">
        <input type="password" class="form-control" name="confirm_password" placeholder="Tekrar şifrenizi giriniz">
                    
        <button class="btn btn-outline-primary" name="reg_btn" >KAYIT OL</button>
        <div class="login-link">
        <p>Kaydını Tamamladın mı? <a href="index.php">Giriş Yap</a></p>
        </div>
        
        </form>
      </div>
       

    </section>
</main>
    
</body>
</html>
    
</body>
</html>