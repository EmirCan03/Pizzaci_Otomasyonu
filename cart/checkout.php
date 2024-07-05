<?php
    include 'connection.php';   /*veritabanıyla bağlantı kurmak için gerekli PHP kodunu içerir*/
    if(isset($_POST['order_btn'])){
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $payment_method = $_POST['payment-method'];
        $flate = $_POST['flate'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $pin = $_POST['pin'];

        $cart_query=mysqli_query($conn, "SELECT *FROM `cart`");
        $price_total = 0;
        if(mysqli_num_rows($cart_query)>0){
            while($product_item=mysqli_fetch_assoc($cart_query)){
                $product_name[]=$product_item['name'].' ('.$product_item['quantity'].')';
                $product_price=$product_item['price']*$product_item['quantity'];
                $price_total+=$product_price;
            }
        }
        $total_product=implode(', ', $product_name);
        $detail_query = mysqli_query($conn, "INSERT INTO `orders`( `name`, `number`, `email`, `method`, `flat`, `street`, `city`, `country`, `pin`, `total_products`, `total_price`) VALUES ('$name','$number','$email','$payment_method','$flate','$street','$city','$country','$pin','$total_product','$price_total')");
        if($cart_query && $detail_query){
            echo"
            <div class='order-confirm-container'>
            <div class='message-container'>
                <h3>Alışveriş için teşekkür ederiz</h3>
                <div class='order-detail'>
                    <span>".$total_product."</span>
                    <span class='total'>toplam : ".$price_total."₺/-</span>
                </div>
                <div class='customer-details'>
                    <p>İsim : <span>".$name."</span></p>
                    <p>Numara : <span>".$number."</span></p>
                    <p>Email : <span>".$email."</span></p>
                    <p>Adres : <span>".$flate.",".$street.",".$city.",".$country.",".$pin."</span></p>
                    <p>Ödeme metodu : <span>".$payment_method."</span></p>
                    <p class='pay'> (*Ürün Geldiğinde Ödeme Yapın*) </p>
                </div>
                <a href='products.php' class='btn'>alışverişe devam et</a>
            </div>
        </div>
            ";
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

    <div class="checkout-form">
        <h1>ödeme işlemi</h1>
        <div class="display-order">
            <?php 
                $select_cart=mysqli_query($conn, "SELECT * FROM `cart`");
                $total=0;
                $grand_total=0;
                if(mysqli_num_rows($select_cart)>0){
                    while($fetch_cart=mysqli_fetch_assoc($select_cart)){
                        $total_price = $fetch_cart['price'] *  $fetch_cart['quantity'];
                        $grand_total = $total += $total_price;
            ?>
            <span><?=$fetch_cart['name']; ?>(<?=$fetch_cart['quantity'];?>)</span>
            <?php            
                    }
                }
            ?>
            <span class="grand-total">Ödenecek toplam tutar : <?=$grand_total; ?>₺/-</span>
        </div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <form action="post">
                <div class="input-field">
                    <span>Adınız</span>
                    <input type="text" name="name" placeholder="Adınızı girin" required>
                </div>
                <div class="input-field">
                    <span>Telefon numarası</span>
                    <input type="number" name="number" placeholder="Numaranızı girin" required>
                </div>
                <div class="input-field">
                    <span>Email</span>
                    <input type="email" name="email" placeholder="email girin" required>
                </div>
                <div class="input-field">
                    <span>Ödeme metodu</span>
                    <select name="payment-method">
                        <option value="Kapıda ödeme">Kapıda ödeme</option>
                        <option value="Kredi kartı">kredi kartı</option>
                        <option value="Telefonla ödeme">telefonla ödeme</option>
                    </select>
                </div>
                <div class="input-field">
                    <span>Mahalle</span>
                    <input type="text" name="flate" placeholder="örn. Köyceğiz" required>
                </div>
                <div class="input-field">
                    <span>İlçe</span>
                    <input type="text" name="street" placeholder="örn. meram" required>
                </div>
                <div class="input-field">
                    <span>Şehir</span>
                    <input type="text" name="city" placeholder="örn. Konya" required>
                </div>
                <div class="input-field">
                    <span>Ülke</span>
                    <input type="text" name="country" placeholder="örn. Türkiye" required>
                </div>
                <div class="input-field">
                    <span>pin kodu</span>
                    <input type="text" name="pin" placeholder="örn. 123" required>
                </div>
                <input type="submit" name="order_btn" placeholder="order now" class="btn">
                </form>
            </div>
    
</body>
</html>