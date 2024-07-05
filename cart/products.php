<?php
    include 'connection.php';   /*veritabanıyla bağlantı kurmak için gerekli PHP kodunu içerir*/
    if (isset($_POST['add_to_cart'])){   /*'ad_product' adında bir POST değişkeninin gönderildiğini kontrol eder. Eğer gönderildiyse, içindeki kod bloğunu çalıştırır.*/
        $name=$_POST['name'];
        $price=$_POST['price'];
        $image=$_POST['image'];    /* formdan alınan verileri ilgili değişkenlere atar. $_POST ile formdan gönderilen metin verilerine ulaşılırken, $_FILES ile yüklenen dosyalara ulaşılır. */
        $quantity=1;

        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE `name`='$name'");
        if(mysqli_num_rows($select_cart)>0) {
            $message[] = 'ürün zaten sepetinize eklendi';
        }else{
            $query = "INSERT INTO `cart` (`name`, `price`, `image`, `quantity`) VALUES ('$name', '$price', '$image', '$quantity')"; /*'name','price','image' değerlerine '$name','$price','$p_image' değerleri atanır*/
            $insert_query = mysqli_query($conn,$query);
            $message[] = 'ürün sepetinize eklendi';
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
    <title>Ana Sayfa</title>
</head>
<body>

<header> <!--sayfanın üst kısmında yer alan başlık bölümünü tanımlar.-->
        <div class="flex">  <!--başlık bölümündeki öğeleri yatay olarak hizalamak için bir esnek (flex) kutu oluşturur.-->
            <a href="" class="logo">gurme lezzetler</a> <!--web sitesinin logosunu içeren bir bağlantıdır. Boş href değeri, bu bağlantının tıklanabilir olduğunu ancak henüz yönlendireceği bir adres olmadığını belirtir.-->
            <div class="navbar">    <!-- navigasyon bağlantılarını içeren bir bölümdür.-->
                <a href="products.php">mağaza</a>   <!--mağaza paneline yönlendiren bir bağlantıdır-->
            </div>
            <?php 
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
            ?>
            <a href="cart.php" class="cart"><i class="bi bi-cart-check-fill"></i><span><?php echo $row_count;?></span></a> <!--alışveriş sepetine yönlendiren bir bağlantıdır. Sepet simgesi ve sepetteki ürün sayısını gösteren bir sayı içerir -->
        </div>
    </header>

    
    <?php
        if(isset($message)) {   /*mesajı ekrana yazdırmak için kullanılıyor*/
            foreach ($message as $messge) {    /*$message dizisinin her bir öğesini $message adlı geçici bir değişkene atar ve döngü içinde bu değişkeni kullanır. */ 
                echo '
                    <div class="message">
                        <span>'.$messge.'<i class="bi bi-x"
                            onclick="this.parentElement.style.display=\'none\'"></i></span>
                    </div>'       
                ;
            }
        }
    ?>
    <div class="product-container">
        <h1>Ürünlerimiz</h1><!--veritabanından alınan her ürün bilgisi ayrı bir form içinde görüntülenir ve kullanıcının sepete eklemesi için form gönderimi sağlanır-->
        <div class="product-item-container">
            <?php       
                $select_products=mysqli_query($conn, "SELECT * FROM `products`");
                if(mysqli_num_rows($select_products)>0){
                    while($fetch_products=mysqli_fetch_assoc($select_products)){
                
            ?>
            <form method="post">    <!--POST yöntemiyle veriyi gönderir.Her ürün için ayrı bir form oluşturur. -->
                <div class="box">   <!--Her bir ürün için bir kutu oluşturur.-->
                    <img src="image/<?php echo $fetch_products['image'];?>">    <!--Ürünün resmini gösterir-->
                    <h3><?php echo $fetch_products['name'];?></h3>              <!--Ürünün ismini gösterir-->
                    <div class="price"><?php echo $fetch_products['price'];?>₺/-</div> <!--ürünün fiyatını gösterir-->
                    <input type="hidden" name="name" value="<?php echo $fetch_products['name'];?>">
                    <input type="hidden" name="price" value="<?php echo $fetch_products['price'];?>">
                    <input type="hidden" name="image" value="<?php echo $fetch_products['image'];?>">
                    <input type="submit" name="add_to_cart" value="Sepete Ekle" class="btn">
                </div>
            </form>
            <?php 
                }}
            ?>
        </div>
    </div> 
    
</body>
</html>