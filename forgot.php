

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Forgot</title>
    <?php 
session_start();
$error = array();


require "mail.php";
	if(!$con = mysqli_connect("sql109.infinityfree.com","if0_34397881","VnWOHI8EwlBl","if0_34397881_codeaddict")){

		die("could not connect");
	}

	$mode = "enter_email";
	if(isset($_GET['mode'])){
		$mode = $_GET['mode'];
	}

	//something is posted
	if(count($_POST) > 0){

		switch ($mode) {
			case 'enter_email':
				// code...
				$email = $_POST['email'];
				//validate email
				if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					$error[] = "Lütfen geçerli bir email giriniz";
				}elseif(!valid_email($email)){
					$error[] = "Bu email bulunamadı";
				}else{

					$_SESSION['forgot']['email'] = $email;
					send_email($email);
					header("Location: forgot.php?mode=enter_code");
					die;
				}
				break;

			case 'enter_code':
				// code...
				$code = $_POST['code'];
				$result = is_code_correct($code);

				if($result == "Kod doğrulandı"){

					$_SESSION['forgot']['code'] = $code;
					header("Location: forgot.php?mode=enter_password");
					die;
				}else{
					$error[] = $result;
				}
				break;

			case 'enter_password':
				// code...
				$password = $_POST['password'];
				$password2 = $_POST['password2'];

				if($password !== $password2){
					$error[] = "Şifreler eşleşmiyor";
				}elseif(!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])){
					header("Location: forgot.php");
					die;
				}else{
					
					save_password($password);
					if(isset($_SESSION['forgot'])){
						unset($_SESSION['forgot']);
					}

					header("Location: index.php");
					die;
				}
				break;
			
			default:
				// code...
				break;
		}
	}

	function send_email($email){
		
		global $con;

		$expire = time() + (60 * 1);
		$code = rand(10000,99999);
		$email = addslashes($email);

		$query = "insert into codes (email,code,expire) value ('$email','$code','$expire')";
		mysqli_query($con,$query);

		//send email here
		send_mail($email,'Sifre Yenileme',"Kodunuz " . $code,);
	}
	
	function save_password($password){
		
		global $con;

		//$password = password_hash($password, PASSWORD_DEFAULT);
		$email = addslashes($_SESSION['forgot']['email']);

		$query = "update users set password = '$password' where email = '$email' limit 1";
		mysqli_query($con,$query);

	}
	
	function valid_email($email){
		global $con;

		$email = addslashes($email);

		$query = "select * from users where email = '$email' limit 1";		
		$result = mysqli_query($con,$query);
		if($result){
			if(mysqli_num_rows($result) > 0)
			{
				return true;
 			}
		}

		return false;

	}

	function is_code_correct($code){
		global $con;

		$code = addslashes($code);
		$expire = time();
		$email = addslashes($_SESSION['forgot']['email']);

		$query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
		$result = mysqli_query($con,$query);
		if($result){
			if(mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_assoc($result);
				if($row['expire'] > $expire){

					return "Kod doğrulandı";
				}else{
					return "kodun süresi geçti";
				}
			}else{
				return "Kod yanlış";
			}
		}

		return "Kod yanlış";
	}

	
?>
</head>
<body>
<style type="text/css">
	body {

  background-color:#98c5f6;
  font-family: Arial, Helvetica, sans-serif;
 
}
	*{
		font-family: tahoma;
		font-size: 15px;
	}

	form{
    max-width: 400px;
    background-color: white;
    border: 1px solid white;
    border-radius: 20px;
    padding: 20px;
    width: 400px;
    opacity: 0.7;
    margin-left: 790px;
    margin-top: 252px;
    
	}
    form-box:hover{

  transform: scale(1.2);
  transition: 0.7s ease;
  opacity: 1;


}

	.textbox{
		padding: 7px;
		width: 382px;
        border: 1px solid-white;
        border-radius: 6px;
	}
    .textbox:hover{
         background-color: antiquewhite;
  transition: 0.7s ease;
    }
    .btn {
  background-color: #007bff;
  border: none;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
  padding: 10px;
  width: 100%;
  margin-top: 10px;
}
.btn:hover {

  background-color: #0069d9;
  color: aqua;
  transition: 0.7s ease ;
  
}
.header{

  text-align: center;
  font-size: 30px;
  

}

</style>

		<?php 

			switch ($mode) {
				case 'enter_email':
					// code...
					?>
						<form method="post" action="forgot.php?mode=enter_email"> 
							<h1  class="header">Şifremi Unuttum</h1>
							<h3>Mailinizi Giriniz</h3>
							<span style="font-size: 12px;color:red;">
							<?php 
								foreach ($error as $err) {
									// code...
									echo $err . "<br>";
								}
							?>
							</span>
							<input class="textbox" type="email" name="email" placeholder="Email"><br>
							<br style="clear: both;">
							<input  class="btn btn-outline-primary" type="submit" value="İlerle">
							<br><br>
							<div><a href="index.php">Giriş yap</a></div>
						</form>
					<?php				
					break;

				case 'enter_code':
					// code...
					?>
						<form method="post" action="forgot.php?mode=enter_code"> 
							<h1 class="header" >Şifremi Unuttum</h1>
							<h3>Mailinize Gönderilen Kodu Giriniz</h3>
							<span style="font-size: 12px;color:red;">
							<?php 
								foreach ($error as $err) {
									// code...
									echo $err . "<br>";
								}
							?>
							</span>

							<input class="textbox" type="text" name="code" placeholder="12345"><br>
							<br style="clear: both;">
							<input class="btn btn-outline-primary" type="submit" value="İlerle" style="float: right;">
							<a href="forgot.php">
								<input class="btn btn-outline-primary" type="button" value="Baştan başla">
							</a>
							<br><br>
							<div><a href="index.php">Giriş yap</a></div>
						</form>
					<?php
					break;

				case 'enter_password':
					// code...
					?>
						<form method="post" action="forgot.php?mode=enter_password"> 
							<h1 class="header" >Şifremi Unuttum</h1>
							<h3>Yeni Şifrenizi Girininiz</h3>
							<span style="font-size: 12px;color:red;">
							<?php 
								foreach ($error as $err) {
									// code...
									echo $err . "<br>";
								}
							?>
							</span>

							<input class="textbox" type="password" name="password" placeholder="Password"><br>
                            <br>
							<input class="textbox" type="password" name="password2" placeholder="Retype Password"><br>
							<br style="clear: both;">
							<input  class="btn btn-outline-primary" type="submit" value="İlerle" style="float: right;">
							<a href="forgot.php">
								<input class="btn btn-outline-primary" type="button" value="Baştan başla">
							</a>
							<br><br>
							<div><a href="index.php">Giriş Yap</a></div>
						</form>
					<?php
					break;
				
				default:
					// code...
					break;
			}

		?>


</body>
</html>