<?php
    include 'connection.php';   /*veritabanıyla bağlantı kurmak için gerekli PHP kodunu içerir*/
    /*alışveriş sepetindeki öğenin miktarını güncellemeyi amaçlıyor */
    if(isset($_POST['update_btn'])){    /*değeri set edilip edilmediği kontrol edilir. */
        $update_value = $_POST['update_quantity'];
        $update_id = $_POST['update_quantity_id'];
        /*cart tablosunda ID'si $update_id olan kaydın miktarını (quantity sütunu) $update_value değeriyle günceller. */
        $update_query = mysqli_query($conn, "UPDATE `cart` SET `quantity`='$update_value' WHERE `id`='$update_id'") or die('query failed');
        if($update_query){
            header('location:cart.php');
        }
    }

    if(isset($_GET['remove'])){
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM `cart` WHERE id='$remove_id'");
        header('location:cart.php');
    }
    if(isset($_GET['delete_all'])){
        mysqli_query($conn, "DELETE FROM `cart`");
        header('location:cart.php');
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
    <div class="cart-container">
        <h1>Alışveriş Sepeti</h1>
        <table>
        <thead>
            <th>resim</th>
            <th>isim</th>
            <th>fiyat</th>
            <th>miktar</th>
            <th>toplam fiyat</th>
            <th>aksiyon</th>
        </thead>
        <tbody>
            <?php 
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
                $grand_total= 0;
                if(mysqli_num_rows($select_cart)>0){
                    while($fetch_cart=mysqli_fetch_assoc($select_cart)){
                
            ?>
            <tr>
                <td><img src="image/<?php echo $fetch_cart['image'];?>"></td>
                <td><?php echo $fetch_cart['name']; ?></td>
                <td><?php echo $fetch_cart['price']; ?>₺/-</td>
                <td class="quantity">
                    <form method="post">
                        <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id'];?>">
                        <input type="number" min="1" name="update_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                        <input type="submit" name="update_btn" value="güncelle">
                    </form>
                </td>
                <td><?php echo $sub_total = $fetch_cart['price']*$fetch_cart['quantity'];?>₺</td>
                <td><a href="cart.php?remove=<?php echo $fetch_cart['id'];?>"onclick="return confirm('Ürünü sepetten kaldır');" class="delete-btn">sil</a></td>
                        
            </tr>
            <?php
                $grand_total+=$sub_total;
                    }}
            ?>
          <tr class="table-bottom">
            <td><a href="products.php" class="option-btn">alışverişe devam et</a></td>
            <td colspan="3"><h1>Ödenecek toplam tutar</h1></td>
            <td style="font-weight: bold;"><?php echo $grand_total;?>₺</td>
            <td><a href="cart.php?delete_all" onclick="return confirm('Sepetteki tüm ürünleri silmek istediğinizden emin misiniz?');" class="delete-btn">hepsini sil</a></td>
          </tr>
          
          
        </tbody>
        </table>
            <div class="checkout-btn">
                <a href="checkout.php" class="btn <?=($grand_total>1)?'':'disabled'?>">ödeme işlemine geç</a>
            </div>
    </div>
</body>
</html>