<?php 
include 'connection.php';

if(isset($_POST['submit'])){
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_users->bind_param("s", $email);
    $select_users->execute();
    $select_users->store_result();

    if($select_users->num_rows > 0){
        $message[] = 'Email daha önce kullanılmış';
    } elseif($pass != $cpass){
        $message[] = 'Şifreler eşleşmiyor';
    } else {
        $insert_user = $conn->prepare("INSERT INTO `users` (`name`, `email`, `password`) VALUES (?, ?, ?)");
        $insert_user->bind_param("sss", $name, $email, $pass);
        $insert_user->execute();
        if($insert_user->affected_rows > 0){
            header('location: login.php'); // Yönlendirme
            exit(); // İşlemi sonlandır
        } else {
            $message[] = 'Kayıt işlemi başarısız oldu';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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
                    <h1>Kaydol</h1>
                    <form action="" method="post">
                        <div class="inputBx">
                            <label>Kullanıcı adı</label>
                            <input type="text" name="name" maxlength="50" required>
                        </div>
                        <div class="inputBx">
                            <label>Email</label>
                            <input type="email" name="email" maxlength="50" required>
                        </div>
                        <div class="inputBx">
                            <label>Kullanıcı şifresi</label>
                            <input type="password" name="pass" maxlength="50" required>
                        </div>
                        <div class="inputBx">
                            <label>Şifreyi onaylayın</label>
                            <input type="password" name="cpass" maxlength="50" required>
                        </div>
                        <input type="submit" name="submit" value="Şimdi kaydol" class="btn">
                    </form>
                    <p>zaten bir hesabım var <a href="login.php">Giriş yap</a></p>
                </div> 
            </div>
        </section>
    </body>    
</html>
