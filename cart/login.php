<?php 
include 'connection.php';

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->bind_param("s", $email);
    $select_user->execute();
    $result = $select_user->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        // Veritabanından gelen parolayı kontrol edelim
        if($pass == $row['password']){
            // Doğrulama başarılıysa oturum başlat
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            header('location: products.php');
            exit();
        } else {
            $message[] = 'Geçersiz email veya şifre (parola yanlış)';
        }
    } else {
        $message[] = 'Geçersiz email veya şifre (email bulunamadı)';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https:/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <section>
        <div class="box">
            <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo '
                             <div class="message">
                                <span>'.$message.'</span>
                                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                        </div>
                        ';
                    }
                }
            ?>
            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="square" style="--i:5;"></div>

            <div class="container">
                <div class="form">
                    <h1>Giriş yap</h1>
                    <form action="" method="post">
                        <div class="inputBx">
                            <label>Email</label>
                            <input type="email" name="email" maxlength="50" required>
                        </div>
                        <div class="inputBx">
                            <label>Kullanıcı şifresi</label>
                            <input type="password" name="pass" maxlength="50" required>
                        </div>
                        <input type="submit" name="submit" value="Şimdi Giriş Yap" class="btn">
                    </form>
                    <p>Bir hesaba sahip değilim <a href="register.php">Kaydol</a></p>
                </div> 
            </div>
        </section>
    </body>    
</html>
