<?php
    include 'connection.php';   /*veritabanıyla bağlantı kurmak için gerekli PHP kodunu içerir*/
    if (isset($_POST['add_product'])){   /*'ad_product' adında bir POST değişkeninin gönderildiğini kontrol eder. Eğer gönderildiyse, içindeki kod bloğunu çalıştırır.*/
        $name=$_POST['p_name'];
        $price=$_POST['p_price'];
        $p_image=$_FILES['p_image']['name'];    /* formdan alınan verileri ilgili değişkenlere atar. $_POST ile formdan gönderilen metin verilerine ulaşılırken, $_FILES ile yüklenen dosyalara ulaşılır. */
        $p_image_temp_name=$_FILES['p_image']['tmp_name'];
        $p_image_folder='image/'.$p_image;

        $query = "INSERT INTO `products` (`name`, `price`, `image`) VALUES ('$name', '$price', '$p_image')"; /*'name','price','image' değerlerine '$name','$price','$p_image' değerleri atanır*/
        $insert_query = mysqli_query($conn,$query);

        if($insert_query){      /*bağlantının başarılı olup olmadığını gösterir  */
            move_uploaded_file($p_image_temp_name,$p_image_folder); /*yüklediği resmi kalıcı bir konuma kaydeder.*/
            $messge[] = 'Ürün ekleme başarılı';
            header('location:admin.php');
        }else{
            $messge[] = 'Ürün ekleme başarısız';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">  <!-- alışveriş sepeti iconu-->
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Ürün Ekle</title>
</head>
<body>

<header>
        <div class="flex">  <!--başlık bölümündeki öğeleri yatay olarak hizalamak için bir esnek (flex) kutu oluşturur.-->
            <a href="" class="logo">gurme lezzetler-Admin Paneli</a> <!--web sitesinin logosunu içeren bir bağlantıdır. Boş href değeri, bu bağlantının tıklanabilir olduğunu ancak henüz yönlendireceği bir adres olmadığını belirtir.-->
            <div class="navbar">    <!-- navigasyon bağlantılarını içeren bir bölümdür.-->
            </div>
            <?php 
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
            ?>
        
        </div>
    </header>

    
    <?php
        if(isset($message)) {   /*mesajı ekrana yazdırmak için kullanılıyor*/
            foreach ($message as $message) {    /*$message dizisinin her bir öğesini $message adlı geçici bir değişkene atar ve döngü içinde bu değişkeni kullanır. */ 
                echo '
                    <div class="message">
                        <span>'.$messge.'<i class="bi bi-x"
                            onclick="this.parentElement.style.display=\'none\'"></i></span>
                    </div>'       
                ;
            }
        }
    ?>
    <div class="form">      <!--ürün ekleme formu-->
        <form method="post" enctype="multipart/form-data">
            <h3>Yeni Ürün Ekle</h3>
            <input type="text" name="p_name" placeholder="Ürünün ismini giriniz" required>
            <input type="number" name="p_price" min="0" placeholder="Ürünün fiyatını giriniz" required>
            <input type="file" name="p_image" accept="image/png,image/jpg,image/jpeg" required>
            <input type="submit" name="add_product" value="Ürün Ekle" class="btn">
        </form>
    </div>
</body>
</html>